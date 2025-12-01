<?php

namespace App\Controllers;

use App\Models\Turnos_db;
use App\Models\Clientes_db;
use App\Models\Barberos_db;
use App\Models\Servicios;
use App\Models\horariosModel;
use App\Models\DiasBloqueados;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class Turnos extends BaseController
{
    /**
     * Procesa la reserva y REDIRIGE a Mercado Pago
     */
   public function procesar()
    {
        $clientesModel = new Clientes_db();
        $turnosModel = new Turnos_db();
        $serviciosModel = new Servicios();
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
            // 1. Obtener y guardar Cliente
            $clienteData = [
                'nombre'   => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'telefono' => $this->request->getPost('telefono'),
                'email'    => $this->request->getPost('email')
            ];
            $idCliente = $clientesModel->insertarClientes($clienteData);
            
            // 2. Generar token
            $token = bin2hex(random_bytes(32)); 

            // 3. Guardar Turno
            $turnoData = [
                'fecha'              => $this->request->getPost('fecha'),
                'id_hora_fk'         => $this->request->getPost('horario'),
                'estado'             => 'pendiente_pago',
                'fecha_notificacion' => date('Y-m-d H:i:s'),
                'estado_msj'         => 'pendiente',
                'id_cliente_fk'      => $idCliente,
                'id_servicio_fk'     => $this->request->getPost('id_servicio'),
                'id_barbero_fk'      => $this->request->getPost('id_barbero'),
                'token_reprogramar'  => $token
            ];
            
            $idTurno = $turnosModel->crearTurno($turnoData); 

            // 4. Datos del servicio
            $servicio = $serviciosModel->find($this->request->getPost('id_servicio'));
            $precioTotal = (float) ($servicio['precio_total'] ?? 0);
            $nombreServicio = $servicio['nombre'] ?? 'Servicio de Barbería';

            // --- INTEGRACIÓN MERCADO PAGO V3 ---
            
            // 1. Configurar Token
            MercadoPagoConfig::setAccessToken(getenv('MP_ACCESS_TOKEN'));

            // 2. Crear el Cliente de Preferencias
            $client = new PreferenceClient();

            // 3. Crear la preferencia usando un ARRAY (ya no new Item)
            $preference = $client->create([
                "items" => [
                    [
                        "id" => "123", // opcional
                        "title" => "Reserva: " . $nombreServicio,
                        "quantity" => 1,
                        "unit_price" => $precioTotal,
                        "currency_id" => "ARS" // Asegúrate de poner tu moneda
                    ]
                ],
                "back_urls" => [
                    "success" => site_url('turnos/feedback'),
                    "failure" => site_url('turnos/feedback'),
                    "pending" => site_url('turnos/feedback')
                ],
                "auto_return" => "approved",
                "external_reference" => (string) $idTurno // Convierte a string por seguridad
            ]);

            // 4. Redirigir (init_point ahora es una propiedad del objeto respuesta)
            return redirect()->to($preference->init_point);

        } catch (MPApiException $e) {
            // Manejo específico de errores de Mercado Pago
            $response = $e->getApiResponse();
            $content = $response ? $response->getContent() : $e->getMessage();
            session()->setFlashdata('error', 'Error MP: ' . json_encode($content));
            return redirect()->to(site_url('/'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Ocurrió un error: ' . $e->getMessage());
            return redirect()->to(site_url('/'));
        }
    }

    /**
     * Recibe al usuario desde Mercado Pago
     */
    public function feedbackPago()
    {
        $turnosModel = new Turnos_db();
        $horariosModel = new horariosModel();
        
        // Mercado Pago envía datos por GET
        $status = $this->request->getGet('status');
        $idTurno = $this->request->getGet('external_reference'); // El ID que guardamos antes

        if ($status === 'approved' && $idTurno) {
            
            // 1. Actualizar estado del turno a CONFIRMADO
            $turnosModel->update($idTurno, ['estado' => 'confirmado']);

            // 2. Obtener todos los detalles para el mail y la vista
            $turnoDetalles = $turnosModel->getTurnoDetalles($idTurno);
            
            if ($turnoDetalles) {
                // Preparar datos para Flashdata (Vista de éxito)
                session()->setFlashdata('exito', '¡Pago recibido! Tu turno ha sido confirmado.');
                session()->setFlashdata('servicio_nombre', $turnoDetalles['servicio_nombre']);
                session()->setFlashdata('precio_total', $turnoDetalles['precio_total']);
                session()->setFlashdata('monto_seña', $turnoDetalles['precio_total']); // Asumiendo pago total o seña
                session()->setFlashdata('fecha', $turnoDetalles['fecha']);
                session()->setFlashdata('horario', substr($turnoDetalles['hora_turno'], 0, 5));
                session()->setFlashdata('token', $turnoDetalles['token_reprogramar']);

                // 3. Enviar el Email (Lo movimos aquí)
                $emailService = \Config\Services::email();
                $emailService->setFrom('leanstylenegocios@gmail.com', 'LeanBarber Reservas'); 
                $emailService->setTo($turnoDetalles['cliente_email']);
                $emailService->setSubject('Pago Confirmado - Turno LeanBarber 💈');

                $mensajeHTML = view('emails/turno_confirmado', [
                    'nombre' => $turnoDetalles['cliente_nombre'],
                    'fecha' => date('d/m/Y', strtotime($turnoDetalles['fecha'])),
                    'hora' => substr($turnoDetalles['hora_turno'], 0, 5),
                    'servicio' => $turnoDetalles['servicio_nombre'],
                    'barbero' => $turnoDetalles['barbero_nombre'], // Asegúrate de que getTurnoDetalles traiga esto
                    'precio' => $turnoDetalles['precio_total'],
                    'sena' => $turnoDetalles['precio_total'], // Ojo con esto si es solo seña
                    'link_reprogramar' => site_url('turnos/cambiar/' . $turnoDetalles['token_reprogramar'])
                ]);

                $emailService->setMessage($mensajeHTML);
                $emailService->send();
            }

            return redirect()->to(site_url('proceso-reserva'));

        } else {
            // Pago fallido o pendiente
            session()->setFlashdata('error', 'El pago no se completó o está pendiente. El turno no fue confirmado.');
            return redirect()->to(site_url('proceso-reserva'));
        }
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