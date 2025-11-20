<?php

namespace App\Controllers;
use App\Models\Turnos_db;
use App\Models\Clientes_db;
use App\Models\Barberos_db;
use App\Models\Servicios;
use App\Models\horariosModel;

class Turnos extends BaseController
{
     public function procesar()
    {
        // Cargamos todos los modelos que vamos a necesitar
        $clientesModel = new Clientes_db();
        $turnosModel = new Turnos_db();
        $serviciosModel = new Servicios();
        $horariosModel = new horariosModel();

        try {
            // 1. Insertar cliente
            $clienteData = [
                'nombre'   => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'telefono' => $this->request->getPost('telefono')
            ];
            $idCliente = $clientesModel->insertarClientes($clienteData);

            // 2. Generar un token único para reprogramar
            $token = bin2hex(random_bytes(32)); // 64 caracteres

            // 3. Preparar datos del turno
            $turnoData = [
                'fecha'              => $this->request->getPost('fecha'),
                'id_hora_fk'         => $this->request->getPost('horario'),
                'estado'             => 'pendiente_pago',
                'fecha_notificacion' => date('Y-m-d H:i:s'),
                'estado_msj'         => 'enviado',
                'id_cliente_fk'      => $idCliente,
                'id_servicio_fk'     => $this->request->getPost('id_servicio'),
                'id_barbero_fk'      => $this->request->getPost('id_barbero'),
                'token_reprogramar'  => $token // Guardamos el token
            ];

            // 4. Insertar turno 
            $idTurno = $turnosModel->crearTurno($turnoData); 

            // --- ¡ÉXITO! Preparamos los datos para la vista de proceso ---
            
            // Obtenemos los detalles para mostrar en la página de éxito
            $servicio = $serviciosModel->find($this->request->getPost('id_servicio'));
            $horario = $horariosModel->find($this->request->getPost('horario'));
            
            session()->setFlashdata('exito', '¡Tu turno fue registrado correctamente!');
            
            // Pasamos los datos reales a la vista de proceso
            session()->setFlashdata('servicio_nombre', $servicio['nombre'] ?? 'Servicio no encontrado');
            session()->setFlashdata('precio_total', $servicio['precio_total'] ?? 0);
            session()->setFlashdata('monto_seña', $servicio['monto_seña'] ?? 0);
            session()->setFlashdata('fecha', $this->request->getPost('fecha'));
            session()->setFlashdata('horario', $horario['horario'] ?? 'Hora no encontrada');
            session()->setFlashdata('token', $token); // Pasamos el token para el link

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
            'dataBarberos' => $barberosModel->traerBarberos(), // Para el formulario
            'dataServicios' => $serviciosModel->traerServicios(), // Para el formulario
            'horariosDisponibles' => [], // Inicialmente vacío
            'fechaSeleccionada' => $turno['fecha'] // Pre-selecciona la fecha antigua
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
            'horariosDisponibles' => $horariosModel->HorariosDisponibles($fecha),
            'fechaSeleccionada' => $fecha // Carga la *nueva* fecha seleccionada
        ];

        // Recargamos la misma vista, pero ahora con los horarios
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
            
            // --- ¡NUEVA VALIDACIÓN DEL LADO DEL SERVIDOR! ---
            $nuevaFecha = $this->request->getPost('fecha');
            $hoy = date('Y-m-d'); // Obtenemos la fecha de hoy

            // Comparamos si la fecha seleccionada es anterior a la fecha de hoy
            if (strtotime($nuevaFecha) < strtotime($hoy)) {
                // Si es anterior, rebotamos con un mensaje de error
                session()->setFlashdata('error', 'Error: No se puede reprogramar un turno para una fecha pasada.');
                // Redirigimos de vuelta a la misma página de reprogramación
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
            // Error
            // Usamos setFlashdata para que el error se muestre en la vista 'reprogramar'
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
            'token' => $token // Pasamos el token a la vista
        ];

        // Re-usamos la misma vista 'reprogramar.php'
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
            'horariosDisponibles' => $horariosModel->HorariosDisponibles($fecha),
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

            // --- Validación de Fecha Posterior ---
            if (strtotime($nuevaFecha) <= strtotime($turno['fecha'])) {
                session()->setFlashdata('error', 'Error: Solo puedes reprogramar para una fecha posterior a tu turno original.');
                return redirect()->to(site_url('turnos/cambiar/' . $token));
            }

            $data = [
                'fecha' => $nuevaFecha,
                'id_hora_fk' => $this->request->getPost('horario'),
                'estado' => 'confirmado' // Nuevo estado
            ];

            // Usamos el ID del turno (obtenido vía token) para actualizar
            $turnosModel->reprogramarTurno($turno['id_turno'], $data);
            
            // Éxito: Redirigimos a la página de proceso con un mensaje de éxito
            session()->setFlashdata('exito', '¡Tu turno fue reprogramado con éxito!');
            session()->setFlashdata('servicio_nombre', $turno['servicio_nombre']);
            session()->setFlashdata('fecha', $nuevaFecha);
            session()->setFlashdata('horario', $this->request->getPost('horario_texto')); // Necesitaríamos pasar el texto
            
            return redirect()->to(site_url('proceso-reserva'));

        } catch (\Exception $e) {
            // Error
            return redirect()->back()->withInput()->with('error', 'Error al reprogramar: ' . $e->getMessage());
        }
    }
}
