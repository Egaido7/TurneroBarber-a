<?php

namespace App\Controllers;
use App\Models\Servicios;
use App\Models\Barberos_db;
use App\Models\horariosModel;
use App\Models\DiasBloqueados; // <-- Importante

class Home extends BaseController
{
    // ... (función index sin cambios) ...
    public function index()
    {
        $serviciosModel = new Servicios();
        $barberosModel = new Barberos_db();
        $data = [
            'dataServicios' => $serviciosModel->traerServicios(),
            'dataBarberos' => $barberosModel->traerBarberos()
        ];
        return view('inicio', $data);
    }

    public function horarios()
    {
        $fecha = $this->request->getPost('fecha');
        
        // 1. Validar si el día está bloqueado
        $diasModel = new DiasBloqueados();
        $motivoBloqueo = $diasModel->esDiaBloqueado($fecha);

        // Recargamos los datos necesarios para la vista (servicios, barberos)
        $serviciosModel = new Servicios();
        $barberosModel = new Barberos_db();
        $data = [
            'dataServicios' => $serviciosModel->traerServicios(),
            'dataBarberos' => $barberosModel->traerBarberos(),
            'fechaSeleccionada' => $fecha
        ];

        if ($motivoBloqueo) {
            // Si está bloqueado, NO buscamos horarios. Enviamos el mensaje de error.
            $data['errorHorario'] = "Lo sentimos, no atendemos el " . date('d/m/Y', strtotime($fecha)) . " por: " . $motivoBloqueo;
            $data['horariosDisponibles'] = []; // Array vacío
        } else {
            // Si está libre, buscamos los horarios normalmente
            $horariosModel = new horariosModel();
            $data['horariosDisponibles'] = $horariosModel->traerHorariosDisponibles($fecha);
        }

        return view('inicio', $data);
    }
}