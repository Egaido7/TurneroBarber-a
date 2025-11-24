<?php

namespace App\Controllers;
use App\Models\Turnos_db;
use App\Models\Clientes_db;
use App\Models\Barberos_db;
use App\Models\Servicios;
use App\Models\horariosModel;
use Twilio\Rest\Client;
class Turnos extends BaseController
{
     public function procesar()
    {
        $clientesModel = new Clientes_db();
        $turnosModel = new Turnos_db();
        $serviciosModel = new Servicios();
        $horariosModel = new horariosModel();
        $barberosModel = new Barberos_db(); // Necesitamos el nombre del barbero

        try {
            // 1. Obtener datos del POST (incluido email)
            $clienteData = [
                'nombre'   => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'email'    => $this->request->getPost('email')
            ];
            
            // Insertamos y guardamos
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

            // --- PREPARAR DATOS ---
            $servicio = $serviciosModel->find($this->request->getPost('id_servicio'));
            $horario = $horariosModel->find($this->request->getPost('horario'));
            $barbero = $barberosModel->traerBarbero($this->request->getPost('id_barbero')); // Traer barbero
            
            $servicioNombre = $servicio['nombre'] ?? 'Servicio';
            $fechaTurno = date('d/m/Y', strtotime($this->request->getPost('fecha')));
            $horaTurno = substr($horario['horario'] ?? '00:00', 0, 5);
            $barberoNombre = ($barbero['nombre'] ?? '') . ' ' . ($barbero['apellido'] ?? '');
            
            // Datos Flashdata para la vista (igual que antes)
            session()->setFlashdata('exito', '¡Tu turno fue registrado correctamente! Te enviamos un email con los detalles.');
            session()->setFlashdata('servicio_nombre', $servicioNombre);
            session()->setFlashdata('precio_total', $servicio['precio_total']);
            session()->setFlashdata('monto_seña', $servicio['monto_seña']);
            session()->setFlashdata('fecha', $this->request->getPost('fecha'));
            session()->setFlashdata('horario', $horaTurno);
            session()->setFlashdata('token', $token); 

            // --- ENVÍO DE EMAIL GRATUITO ---
            $emailService = \Config\Services::email();

            $emailService->setFrom('leanstyle@gmail.com', 'LeanBarber Reservas'); // <--- IMPORTANTE: Tu correo
            $emailService->setTo($this->request->getPost('email'));
            $emailService->setSubject('Confirmación de Turno - LeanBarber 💈');

            // Cargar la vista del email y pasarle los datos
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
                // Si falla, logueamos el error pero no detenemos el flujo
                log_message('error', 'Error enviando email: ' . $emailService->printDebugger(['headers']));
                // Opcional: avisar al usuario
                session()->setFlashdata('warning', 'Turno guardado, pero no se pudo enviar el email.');
            }
            // --------------------------------

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Ocurrió un error: ' . $e->getMessage());
        }
        
        return redirect()->to(site_url('proceso-reserva'));
    }


    public function resultado()
    {
        return view('proceso');
    }

     public function reprogramar($id_turno = null) { return $this->_mostrarReprogramar($id_turno, 'admin'); }
     public function reprogramarUsuario($token = null) { return $this->_mostrarReprogramar($token, 'usuario'); }
private function _mostrarReprogramar($id_or_token, $tipo) {
        $turnosModel = new Turnos_db();
        $barberosModel = new Barberos_db();
        $serviciosModel = new Servicios();
        
        if ($tipo == 'admin') {
             if (!session()->get('isLoggedIn')) return redirect()->to(site_url('login'));
             $turno = $turnosModel->getTurnoDetalles($id_or_token);
        } else {
             $turno = $turnosModel->getTurnoByToken($id_or_token);
        }

        if (empty($turno)) {
            return redirect()->to(site_url($tipo == 'admin' ? 'admin?section=turnos' : '/'))->with('mensaje', 'Turno no encontrado.');
        }
        
        $data = [
            'turno' => $turno,
            'dataBarberos' => $barberosModel->traerBarberos(), 
            'dataServicios' => $serviciosModel->traerServicios(), 
            'horariosDisponibles' => [], 
            'fechaSeleccionada' => $turno['fecha'],
            'token' => ($tipo == 'usuario') ? $id_or_token : null
        ];
        return view('reprogramar', $data);
    }

    /**
     * Carga los horarios disponibles.
     * Es llamada por el botón "Ver Horarios" (POST).
     */
    public function horariosReprogramar($id_turno = null) { return $this->_mostrarHorarios($id_turno, 'admin'); }
        public function horariosUsuario($token = null) { return $this->_mostrarHorarios($token, 'usuario'); }
        private function _mostrarHorarios($id_or_token, $tipo) {
        $turnosModel = new Turnos_db();
        $barberosModel = new Barberos_db();
        $serviciosModel = new Servicios();
        $horariosModel = new horariosModel();
        
        $fecha = $this->request->getPost('fecha');
        
        if ($tipo == 'admin') {
             $turno = $turnosModel->getTurnoDetalles($id_or_token);
        } else {
             $turno = $turnosModel->getTurnoByToken($id_or_token);
        }

        $data = [
            'turno' => $turno,
            'dataBarberos' => $barberosModel->traerBarberos(),
            'dataServicios' => $serviciosModel->traerServicios(),
            'horariosDisponibles' => $horariosModel->traerHorariosDisponibles($fecha),
            'fechaSeleccionada' => $fecha,
            'token' => ($tipo == 'usuario') ? $id_or_token : null
        ];
        return view('reprogramar', $data);
    }
    /**
     * PROCESA el formulario y guarda la reprogramación.
     * Es llamada por el botón "Aceptar" (POST).
     */
     public function procesarReprogramacion($id_turno = null)
    {
        if ($id_turno === null) {
            return redirect()->to(site_url('admin?section=turnos'))->with('mensaje', 'Error: ID de turno no válido.');
        }

        try {
            $turnosModel = new Turnos_db();
            
            
            $nuevaFecha = $this->request->getPost('fecha');
            $hoy = date('Y-m-d'); // Obtenemos la fecha de hoy

            
            if (strtotime($nuevaFecha) < strtotime($hoy)) {
                
                session()->setFlashdata('error', 'Error: No se puede reprogramar un turno para una fecha pasada.');
                
                return redirect()->to(site_url('admin/turnos/reprogramar/' . $id_turno));
            }
            // --- FIN DE LA VALIDACIÓN ---
            
            $data = [
                'fecha' => $nuevaFecha, // Usamos la variable que ya teníamos
                'id_hora_fk' => $this->request->getPost('horario'),
                'estado' => 'confirmado' // Cambiamos el estado
            ];

            $turnosModel->reprogramarTurno($id_turno, $data);
            
            // Éxito: Redirigimos al panel de admin
            return redirect()->to(site_url('admin?section=turnos'))->with('mensaje', '¡Turno reprogramado con éxito!');

        } catch (\Exception $e) {
           
            session()->setFlashdata('error', 'Error al reprogramar: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    /**
     * PROCESA el formulario y guarda la reprogramación del USUARIO.
     * Es llamada por el botón "Aceptar" (POST) desde la ruta pública.
     */
    public function procesarReprogramacionUsuario($token = null)
    {
        if ($token === null) {
            return redirect()->to(site_url('/'))->with('error', 'Error: Link no válido.');
        }

        $turnosModel = new Turnos_db();
        $turno = $turnosModel->getTurnoByToken($token);

        if (empty($turno)) {
            return redirect()->to(site_url('/'))->with('error', 'Error: Turno no encontrado.');
        }

        try {
            $nuevaFecha = $this->request->getPost('fecha');

            if (strtotime($nuevaFecha) <= strtotime($turno['fecha'])) {
                session()->setFlashdata('error', 'Error: Solo puedes reprogramar para una fecha posterior a tu turno original.');
                return redirect()->to(site_url('turnos/cambiar/' . $token));
            }

            $data = [
                'fecha' => $nuevaFecha,
                'id_hora_fk' => $this->request->getPost('horario'),
                'estado' => 'reprogramado_usr' 
            ];

            $turnosModel->reprogramarTurno($turno['id_turno'], $data);
            
            session()->setFlashdata('exito', '¡Tu turno fue reprogramado con éxito!');
            session()->setFlashdata('servicio_nombre', $turno['servicio_nombre']);
            session()->setFlashdata('fecha', $nuevaFecha);
           
            session()->setFlashdata('horario', 'Horario Actualizado'); 
            
            return redirect()->to(site_url('proceso-reserva'));

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al reprogramar: ' . $e->getMessage());
        }
    }

    // --- FUNCIÓN AUXILIAR PRIVADA PARA ENVIAR EL EMAIL ---
    private function _enviarEmailReprogramacion($email, $nombre, $fecha, $hora, $servicio, $token) {
        $emailService = \Config\Services::email();
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
