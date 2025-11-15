<?php

namespace App\Controllers;
use App\Models\Barberos_db;
use App\Models\Turnos_db;
use PhpParser\Node\Expr\AssignOp\Mod;

class Admin extends BaseController
{  
      public function dashboard()
    {
        // 1. Verificar que el usuario está autenticado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }
        
        // 2. Definir la sección actual
        $section = $this->request->getGet('section') ?? 'turnos';
        
        // 3. Preparar el array de datos que se enviará a la vista
        $data = [
            'section' => $section
        ];
        
        // 4. Cargar datos específicos para CADA sección
        if ($section === 'turnos') {
            $fechaSeleccionada = $this->request->getGet('fecha') ?? date('Y-m-d');
            $turnosModel = new Turnos_db();
            $data['turnos'] = $turnosModel->obtenerTurnosConDetalles($fechaSeleccionada);
            $data['fechaSeleccionada'] = $fechaSeleccionada;
        
        } elseif ($section === 'peluqueros') {
            // --- ¡NUEVA LÓGICA PARA PELUQUEROS! ---
            $barberosModel = new Barberos_db();
            $data['barberos'] = $barberosModel->traerBarberos();
        }
        // ... (aquí irían las otras secciones como 'servicios', etc.) ...

        // 5. Cargar la vista principal
        return view('admin/dashboard', $data);
    }
    
    /**
     * Procesa el formulario del modal "Agregar Peluquero"
     */
    public function agregarPeluquero()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        // Preparamos los datos
        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            // ¡IMPORTANTE! Hashear la contraseña
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'activo'   => 1 // Lo ponemos como activo por defecto
        ];

        $barberosModel = new Barberos_db();
        $barberosModel->guardarBarbero($data);

        return redirect()->to(site_url('admin?section=peluqueros'))->with('mensaje', 'Peluquero agregado con éxito.');
    }

    /**
     * Procesa el formulario del modal "Editar Peluquero"
     */
    public function editarPeluquero($id_barbero = null)
    {
        if (!session()->get('isLoggedIn') || $id_barbero === null) {
            return redirect()->to(site_url('login'));
        }

        $barberosModel = new Barberos_db();
        
        // Preparamos los datos básicos
        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'activo'   => $this->request->getPost('activo') ?? 0 // Si no se marca, será 0 (inactivo)
        ];

        // Opcional: Solo actualizar la contraseña si se escribió una nueva
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $barberosModel->update($id_barbero, $data);

        return redirect()->to(site_url('admin?section=peluqueros'))->with('mensaje', 'Peluquero actualizado con éxito.');
    }

    /**
     * Procesa el clic en el botón "Eliminar"
     */
    public function eliminarPeluquero($id_barbero = null)
    {
        if (!session()->get('isLoggedIn') || $id_barbero === null) {
            return redirect()->to(site_url('login'));
        }

        // Lógica de borrado
        $barberosModel = new Barberos_db();
        $barberosModel->eliminarBarbero($id_barbero);
        
        return redirect()->to(site_url('admin?section=peluqueros'))->with('mensaje', 'Peluquero eliminado con éxito.');
    }

    /**
     * Placeholder para la lógica de cancelación de turnos
     */
    public function cancelarTurno($id_turno = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        if ($id_turno) {
            $turnosModel = new Turnos_db();
            $turnosModel->update($id_turno, ['estado' => 'cancelado']);
            session()->setFlashdata('mensaje', 'Turno ID ' . $id_turno . ' cancelado correctamente.');
        } else {
            session()->setFlashdata('mensaje', 'Error: No se proporcionó ID de turno.');
        }

        // Redirigir de vuelta a la sección de turnos (y opcionalmente mantener la fecha)
        $fecha = $this->request->getGet('fecha') ?? date('Y-m-d');
        return redirect()->to(site_url('admin?section=turnos&fecha=' . $fecha));
    }
    

     public function traerEstadisticas(){}
    public function traerServicios(){}
    public function editarServicio(){}
    public function eliminarServicio(){}
    public function editarPrecio(){}

    public function agregarServicioForm()
    {}


}