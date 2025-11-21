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

        try {
            // 1. Obtener datos del formulario
            $telefonoCliente = $this->request->getPost('telefono');
            
            // 2. Insertar cliente
            $clienteData = [
                'nombre'   => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'telefono' => $telefonoCliente
            ];
            $idCliente = $clientesModel->insertarClientes($clienteData);

            // 3. Generar token único
            $token = bin2hex(random_bytes(32)); 

            // 4. Preparar datos del turno
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

            // 5. Insertar turno 
            $idTurno = $turnosModel->crearTurno($turnoData); 

            // --- PREPARAR DATOS PARA LA VISTA Y SMS ---
            $servicio = $serviciosModel->find($this->request->getPost('id_servicio'));
            $horario = $horariosModel->find($this->request->getPost('horario'));
            
            $servicioNombre = $servicio['nombre'] ?? 'Servicio';
            $fechaTurno = date('d/m/Y', strtotime($this->request->getPost('fecha')));
            $horaTurno = substr($horario['horario'] ?? '00:00', 0, 5);
            $precioTotal = $servicio['precio_total'] ?? 0;
            $montoSena = $servicio['monto_seña'] ?? 0;

            // Datos para la vista
            session()->setFlashdata('exito', '¡Tu turno fue registrado correctamente!');
            session()->setFlashdata('servicio_nombre', $servicioNombre);
            session()->setFlashdata('precio_total', $precioTotal);
            session()->setFlashdata('monto_seña', $montoSena);
            session()->setFlashdata('fecha', $this->request->getPost('fecha'));
            session()->setFlashdata('horario', $horaTurno);
            session()->setFlashdata('token', $token); 

            // --- INTEGRACIÓN CON TWILIO (SMS) ---
            try {
                // Cargar credenciales desde .env
                $sid    = getenv('TWILIO_SID');
                $tokenTwilio  = getenv('TWILIO_TOKEN');
                $twilioNumber = getenv('TWILIO_NUMBER');
                
                if ($sid && $tokenTwilio && $twilioNumber) {
                    $twilio = new Client($sid, $tokenTwilio);

                    // Construir el mensaje
                    $cuerpoMensaje = "Hola! Reservaste turno en BarberShop Elite 💈\n" .
                                     "Servicio: $servicioNombre\n" .
                                     "Fecha: $fechaTurno a las $horaTurno\n" .
                                     "Total: $$precioTotal (Seña: $$montoSena)\n\n" .
                                     "Para cancelar o reprogramar: " . site_url('turnos/cambiar/' . $token);

                    // Formatear número (Twilio necesita formato E.164, ej: +549266...)
                    // Asumo que el usuario ingresa número local, agregamos prefijo de Argentina si falta
                    if (strpos($telefonoCliente, '+') === false) {
                        // Ajusta este prefijo según tu país. Ej Argentina celular: +549
                        $telefonoCliente = '+549' . $telefonoCliente; 
                    }

                    $twilio->messages->create(
                        $telefonoCliente, // Destinatario
                        [
                            'from' => $twilioNumber,
                            'body' => $cuerpoMensaje
                        ]
                    );
                }
            } catch (\Exception $eTwilio) {
                // Si falla el SMS, NO detenemos el proceso, solo lo logueamos o avisamos
                // En modo TRIAL, esto fallará si el número destino no es el tuyo verificado.
                log_message('error', 'Error enviando SMS Twilio: ' . $eTwilio->getMessage());
                session()->setFlashdata('warning', 'Turno reservado, pero no se pudo enviar el SMS de confirmación.');
            }
            // -------------------------------------

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Ocurrió un error al registrar tu turno: ' . $e->getMessage());
        }
        
        return redirect()->to(site_url('proceso-reserva'));
    }


    public function resultado()
    {
        return view('proceso');
    }

     public function reprogramar($id_turno = null)
    {
        if ($id_turno === null) {
            return redirect()->to(site_url('admin?section=turnos'))->with('mensaje', 'Error: ID de turno no válido.');
        }

        $turnosModel = new Turnos_db();
        $barberosModel = new Barberos_db();
        $serviciosModel = new Servicios();

        $turno = $turnosModel->getTurnoDetalles($id_turno);

        if (empty($turno)) {
            return redirect()->to(site_url('admin?section=turnos'))->with('mensaje', 'Error: Turno no encontrado.');
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

    /**
     * Carga los horarios disponibles.
     * Es llamada por el botón "Ver Horarios" (POST).
     */
    public function horariosReprogramar($id_turno = null)
    {
        if ($id_turno === null) {
            return redirect()->to(site_url('admin?section=turnos'))->with('mensaje', 'Error: ID de turno no válido.');
        }
        
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
            'horariosDisponibles' => $horariosModel->horariosDisponibles($fecha),
            'fechaSeleccionada' => $fecha 
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

     public function reprogramarUsuario($token = null)
    {
        if ($token === null) {
            return redirect()->to(site_url('/'))->with('error', 'Error: Link de reprogramación no válido.');
        }

        $turnosModel = new Turnos_db();
        $horariosModel = new horariosModel();
        
        $turno = $turnosModel->getTurnoByToken($token);

        if (empty($turno)) {
            return redirect()->to(site_url('/'))->with('error', 'Error: Link de reprogramación no válido o el turno ya fue modificado.');
        }
        
        $data = [
            'turno' => $turno,
            'horariosDisponibles' => [],
            'fechaSeleccionada' => $turno['fecha'],
            'token' => $token 
        ];

        return view('reprogramar', $data);
    }

    /**
     * Carga los horarios disponibles para el USUARIO.
     * Es llamada por el botón "Ver Horarios" (POST) desde la ruta pública.
     */
   public function horariosUsuario($token = null)
    {
        if ($token === null) {
            return redirect()->to(site_url('/'))->with('error', 'Error: Link no válido.');
        }
        
        $turnosModel = new Turnos_db();
        $horariosModel = new horariosModel();
        
        $fecha = $this->request->getPost('fecha');
        $turno = $turnosModel->getTurnoByToken($token);

        if (empty($turno)) {
            return redirect()->to(site_url('/'))->with('error', 'Error: Turno no encontrado.');
        }

        $data = [
            'turno' => $turno,
            'horariosDisponibles' => $horariosModel->horariosDisponibles($fecha),
            'fechaSeleccionada' => $fecha,
            'token' => $token
        ];

        return view('reprogramar', $data);
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
}
