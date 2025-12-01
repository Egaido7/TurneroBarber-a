<?php

namespace App\Controllers;

use App\Models\Turnos_db;
use App\Models\Clientes_db;
use App\Models\Barberos_db;
use App\Models\Servicios;
use App\Models\DiasBloqueados;
use App\Models\horariosModel;
// use Twilio\Rest\Client; // Ya no lo usamos, se puede quitar

class Turnos extends BaseController
{
    /**
     * Procesa la reserva ORIGINAL del usuario (Alta de turno)
     */
    public function procesar()
    {
        $clientesModel = new Clientes_db();
        $turnosModel = new Turnos_db();
        $serviciosModel = new Servicios();
        $horariosModel = new horariosModel();
        $barberosModel = new Barberos_db(); 
$diasBloqueadosModel = new DiasBloqueados();
        try {
            $fecha = $this->request->getPost('fecha'); 
            
            // Asumiendo que tienes el método esDiaBloqueado en tu modelo DiasBloqueados
            if ($diasBloqueadosModel->esDiaBloqueado($fecha)) {
                session()->setFlashdata('error', 'Error: La fecha seleccionada no está disponible para turnos.');
                return redirect()->to(site_url('/')); 
            }

            // --- VALIDACIÓN 2: CONTROL DE HORARIO VACÍO (Fix del error null) ---
            $idHorario = $this->request->getPost('horario');
            
            if (empty($idHorario)) {
                session()->setFlashdata('error', 'Por favor, selecciona un horario disponible antes de continuar.');
                return redirect()->to(site_url('/')); // Volvemos al inicio para que elija bien
            }
            // 1. Obtener datos
            $clienteData = [
                'nombre'   => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'telefono' => $this->request->getPost('telefono'),
                'email'    => $this->request->getPost('email')
            ];
            
            $idCliente = $clientesModel->insertarClientes($clienteData);
            $token = bin2hex(random_bytes(32)); 

            $turnoData = [
                'fecha'              => $this->request->getPost('fecha'),
                'id_hora_fk'         => $this->request->getPost('horario'),
                'estado'             => 'pendiente_pago',
                'fecha_notificacion' => date('Y-m-d H:i:s'),
                'estado_msj'         => 'enviado',
                'id_cliente_fk'      => $idCliente,
                'id_servicio_fk'     => $this->request->getPost('id_servicio'),
                'id_barbero_fk'      => $this->request->getPost('id_barbero'),
                'token_reprogramar'  => $token
            ];
            
            $turnosModel->crearTurno($turnoData); 

            // --- PREPARAR DATOS PARA VISTA Y MAIL ---
            $servicio = $serviciosModel->find($this->request->getPost('id_servicio'));
            $horario = $horariosModel->find($this->request->getPost('horario'));
            $barbero = $barberosModel->traerBarbero($this->request->getPost('id_barbero'));
            
            $servicioNombre = $servicio['nombre'] ?? 'Servicio';
            $fechaTurno = date('d/m/Y', strtotime($this->request->getPost('fecha')));
            $horaTurno = substr($horario['horario'] ?? '00:00', 0, 5);
            $barberoNombre = ($barbero['nombre'] ?? '') . ' ' . ($barbero['apellido'] ?? '');
            
            session()->setFlashdata('exito', '¡Tu turno fue registrado correctamente! Te enviamos un email con los detalles.');
            session()->setFlashdata('servicio_nombre', $servicioNombre);
            session()->setFlashdata('precio_total', $servicio['precio_total']);
            session()->setFlashdata('monto_seña', $servicio['monto_seña']);
            session()->setFlashdata('fecha', $this->request->getPost('fecha'));
            session()->setFlashdata('horario', $horaTurno);
            session()->setFlashdata('token', $token); 

            // --- ENVÍO DE EMAIL (CONFIRMACIÓN INICIAL) ---
            $emailService = \Config\Services::email();
            // IMPORTANTE: Asegúrate de configurar el SMTP en tu .env
            $emailService->setFrom('leanstylenegocios@gmail.com', 'LeanBarber Reservas'); 
            $emailService->setTo($this->request->getPost('email'));
            $emailService->setSubject('Confirmación de Turno - LeanBarber 💈');

            $mensajeHTML = view('emails/turno_confirmado', [
                'nombre' => $this->request->getPost('nombre'),
                'fecha' => $fechaTurno,
                'hora' => $horaTurno,
                'servicio' => $servicioNombre,
                'barbero' => $barberoNombre,
                'precio' => $servicio['precio_total'],
                'sena' => $servicio['monto_seña'],
                'link_reprogramar' => site_url('turnos/cambiar/' . $token)
            ]);

            $emailService->setMessage($mensajeHTML);
            
            if (!$emailService->send()) {
                 log_message('error', 'Error enviando email: ' . $emailService->printDebugger(['headers']));
            }

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Ocurrió un error: ' . $e->getMessage());
        }
        
        return redirect()->to(site_url('proceso-reserva'));
    }


    public function resultado()
    {
        return view('proceso');
    }

    // --- VALIDACIONES PRIVADAS ---
    private function _checkRestriccion12Horas($fechaTurno, $horaTurno) {
        $fechaHoraTurno = $fechaTurno . ' ' . $horaTurno; 
        $timestampTurno = strtotime($fechaHoraTurno);
        $timestampAhora = time(); 
        
        $diferenciaHoras = ($timestampTurno - $timestampAhora) / 3600;

        if ($diferenciaHoras < 12) {
            return false;
        }
        return true;
    }

    private function _checkRestriccion30Dias($nuevaFecha) {
        $limite = strtotime('+30 days');
        $fechaElegida = strtotime($nuevaFecha);

        if ($fechaElegida > $limite) {
            return false;
        }
        return true;
    }


    // --- FUNCIONES ADMIN ---

    public function reprogramar($id_turno = null)
    {
        if ($id_turno === null) return redirect()->to(site_url('admin?section=turnos'));

        $turnosModel = new Turnos_db();
        $barberosModel = new Barberos_db();
        $serviciosModel = new Servicios();
        $turno = $turnosModel->getTurnoDetalles($id_turno);

        if (empty($turno)) return redirect()->to(site_url('admin?section=turnos'))->with('mensaje', 'Turno no encontrado.');

        if (!$this->_checkRestriccion12Horas($turno['fecha'], $turno['hora_turno'])) {
            return redirect()->to(site_url('admin?section=turnos'))
                             ->with('mensaje', 'No se puede reprogramar: Faltan menos de 12 horas para el turno.');
        }
        
        $data = [
            'turno' => $turno,
            'dataBarberos' => $barberosModel->traerBarberos(), 
            'dataServicios' => $serviciosModel->traerServicios(), 
            'horariosDisponibles' => [], 
            'fechaSeleccionada' => $turno['fecha'] 
        ];
        return view('reprogramar', $data);
    }

    public function horariosReprogramar($id_turno = null)
    {
        if ($id_turno === null) return redirect()->to(site_url('admin?section=turnos'));
        
        $turnosModel = new Turnos_db();
        $barberosModel = new Barberos_db();
        $serviciosModel = new Servicios();
        $horariosModel = new horariosModel();
        
        $fecha = $this->request->getPost('fecha');
        $turno = $turnosModel->getTurnoDetalles($id_turno);

        $data = [
            'turno' => $turno,
            'dataBarberos' => $barberosModel->traerBarberos(),
            'dataServicios' => $serviciosModel->traerServicios(),
            'horariosDisponibles' => $horariosModel->traerHorariosDisponibles($fecha),
            'fechaSeleccionada' => $fecha 
        ];
        return view('reprogramar', $data);
    }

    public function procesarReprogramacion($id_turno = null)
    {
        if ($id_turno === null) return redirect()->to(site_url('admin?section=turnos'));

        $turnosModel = new Turnos_db();
        // Obtenemos datos originales ANTES de actualizar para tener el email
        $turnoOriginal = $turnosModel->getTurnoDetalles($id_turno); 

        try {
            $nuevaFecha = $this->request->getPost('fecha');
            $idNuevoHorario = $this->request->getPost('horario');
            $hoy = date('Y-m-d');

            // Validaciones
            if (strtotime($nuevaFecha) < strtotime($hoy)) {
                session()->setFlashdata('error', 'Error: No se puede reprogramar para una fecha pasada.');
                return redirect()->to(site_url('admin/turnos/reprogramar/' . $id_turno));
            }
            if (!$this->_checkRestriccion12Horas($turnoOriginal['fecha'], $turnoOriginal['hora_turno'])) {
                session()->setFlashdata('error', 'Error: Ya no se puede reprogramar (menos de 12hs).');
                return redirect()->to(site_url('admin?section=turnos'));
            }
            if (!$this->_checkRestriccion30Dias($nuevaFecha)) {
                session()->setFlashdata('error', 'Error: No puedes agendar con más de 30 días de anticipación.');
                return redirect()->to(site_url('admin/turnos/reprogramar/' . $id_turno));
            }
            
            // Datos a actualizar
            $data = [
                'fecha' => $nuevaFecha, 
                'id_hora_fk' => $idNuevoHorario,
                'estado' => 'confirmado' 
            ];
            $turnosModel->reprogramarTurno($id_turno, $data);

            // --- CORRECCIÓN: ENVÍO DE EMAIL ---
            // Obtenemos el texto del horario nuevo para el mail
            $horariosModel = new horariosModel();
            $nuevoHorarioInfo = $horariosModel->find($idNuevoHorario);
            $horaTexto = substr($nuevoHorarioInfo['horario'] ?? '00:00', 0, 5);

            if (!empty($turnoOriginal['cliente_email'])) {
                $this->_enviarEmailReprogramacion(
                    $turnoOriginal['cliente_email'],
                    $turnoOriginal['cliente_nombre'],
                    date('d/m/Y', strtotime($nuevaFecha)),
                    $horaTexto,
                    $turnoOriginal['servicio_nombre'],
                    $turnoOriginal['token_reprogramar']
                );
            }
            // ----------------------------------
            
            return redirect()->to(site_url('admin?section=turnos'))->with('mensaje', '¡Turno reprogramado con éxito y email enviado!');

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error al reprogramar: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    // --- FUNCIONES USUARIO PÚBLICO ---

    public function reprogramarUsuario($token = null)
    {
        if ($token === null) return redirect()->to(site_url('/'));

        $turnosModel = new Turnos_db();
        $horariosModel = new horariosModel();
        $turno = $turnosModel->getTurnoByToken($token);

        if (empty($turno)) return redirect()->to(site_url('/'))->with('error', 'Link no válido.');
        
        if (!$this->_checkRestriccion12Horas($turno['fecha'], $turno['hora_turno'])) {
            return redirect()->to(site_url('/'))
                             ->with('error', 'Lo sentimos, ya no puedes reprogramar este turno porque faltan menos de 12 horas.');
        }
        
        $data = [
            'turno' => $turno,
            'horariosDisponibles' => [],
            'fechaSeleccionada' => $turno['fecha'],
            'token' => $token 
        ];
        return view('reprogramar', $data);
    }

    public function horariosUsuario($token = null)
    {
        if ($token === null) return redirect()->to(site_url('/'));
        $turnosModel = new Turnos_db();
        $horariosModel = new horariosModel();
        $fecha = $this->request->getPost('fecha');
        $turno = $turnosModel->getTurnoByToken($token);

        if (empty($turno)) return redirect()->to(site_url('/'));

        $data = [
            'turno' => $turno,
            'horariosDisponibles' => $horariosModel->traerHorariosDisponibles($fecha),
            'fechaSeleccionada' => $fecha,
            'token' => $token
        ];
        return view('reprogramar', $data);
    }

    public function procesarReprogramacionUsuario($token = null)
    {
        if ($token === null) return redirect()->to(site_url('/'));
        $turnosModel = new Turnos_db();
        // Traemos el turno original (que ya tiene los datos del cliente, incluido el email)
        $turnoOriginal = $turnosModel->getTurnoByToken($token);

        if (empty($turnoOriginal)) return redirect()->to(site_url('/'));

        try {
            $nuevaFecha = $this->request->getPost('fecha');
            $idNuevoHorario = $this->request->getPost('horario');

            // Validaciones
            if (strtotime($nuevaFecha) <= strtotime($turnoOriginal['fecha'])) {
                session()->setFlashdata('error', 'Error: Fecha debe ser posterior a la original.');
                return redirect()->to(site_url('turnos/cambiar/' . $token));
            }
            if (!$this->_checkRestriccion12Horas($turnoOriginal['fecha'], $turnoOriginal['hora_turno'])) {
                return redirect()->to(site_url('/'))->with('error', 'Error: Faltan menos de 12 horas.');
            }
            if (!$this->_checkRestriccion30Dias($nuevaFecha)) {
                session()->setFlashdata('error', 'Error: No puedes agendar con más de 30 días de anticipación.');
                return redirect()->to(site_url('turnos/cambiar/' . $token));
            }

            $data = [
                'fecha' => $nuevaFecha,
                'id_hora_fk' => $idNuevoHorario,
                'estado' => 'reprogramado_usr' 
            ];

            $turnosModel->reprogramarTurno($turnoOriginal['id_turno'], $data);
            
            // --- CORRECCIÓN: ENVÍO DE EMAIL ---
            // Obtenemos texto del horario
            $horariosModel = new horariosModel();
            $nuevoHorarioInfo = $horariosModel->find($idNuevoHorario);
            $horaTexto = substr($nuevoHorarioInfo['horario'] ?? '00:00', 0, 5);

            if (!empty($turnoOriginal['cliente_email'])) {
                $this->_enviarEmailReprogramacion(
                    $turnoOriginal['cliente_email'],
                    $turnoOriginal['cliente_nombre'],
                    date('d/m/Y', strtotime($nuevaFecha)),
                    $horaTexto,
                    $turnoOriginal['servicio_nombre'],
                    $token
                );
            }
            // ----------------------------------
            
            session()->setFlashdata('exito', '¡Turno reprogramado con éxito! Te enviamos un email.');
            session()->setFlashdata('servicio_nombre', $turnoOriginal['servicio_nombre']);
            session()->setFlashdata('fecha', $nuevaFecha);
            // Usamos el horario que acabamos de buscar
            session()->setFlashdata('horario', $horaTexto); 
            
            return redirect()->to(site_url('proceso-reserva'));

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // --- FUNCIÓN AUXILIAR PRIVADA PARA ENVIAR EL EMAIL ---
    private function _enviarEmailReprogramacion($email, $nombre, $fecha, $hora, $servicio, $token) {
        $emailService = \Config\Services::email();
        // Configura aquí tu remitente real
        $emailService->setFrom('leanstylenegocios@gmail.com', 'LeanBarber Reservas'); 
        $emailService->setTo($email);
        $emailService->setSubject('Cambio de Turno - LeanBarber 💈');

        $mensajeHTML = view('emails/turno_reprogramado', [
            'nombre' => $nombre,
            'fecha' => $fecha,
            'hora' => $hora,
            'servicio' => $servicio,
            'link_ver_turno' => site_url('turnos/cambiar/' . $token)
        ]);

        $emailService->setMessage($mensajeHTML);
        return $emailService->send();
    }
}