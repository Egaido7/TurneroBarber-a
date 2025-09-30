<?php

namespace App\Controllers;
use App\Models\HorariosModel;
use App\Models\Servicios;
use App\Models\Barberos_db;

class Home extends BaseController
{
    public function index(): string
    {   $servicios = new Servicios();
        $barberos = new Barberos_db();
        $dataBarberos = $barberos->traerBarberos();
        $dataServicios = $servicios->traerServicios();
        return view('inicio', ['dataServicios' => $dataServicios, 'dataBarberos' => $dataBarberos]);
    }
  public function horarios()
{
    $servicios = new Servicios();
        $dataServicios = $servicios->traerServicios();
        $barberos = new Barberos_db();
        $dataBarberos = $barberos->traerBarberos();
    $fecha = $this->request->getPost('fecha');

    // Validación 1: fecha no pasada
    $hoy = date('Y-m-d');
    if ($fecha < $hoy) {
        return redirect()->back()->with('error', 'No podés reservar turnos en fechas pasadas.');
    }

    // Validación 2: evitar domingos
    $diaSemana = date('w', strtotime($fecha)); // 0 = domingo, 6 = sábado
    if ($diaSemana == 0) {
        return redirect()->back()->with('error', 'No se atiende los domingos.');
    }

    // Si pasa validaciones, buscar horarios
    $modeloHorario = new HorariosModel(); 
    $horariosDisponibles = $modeloHorario->Horariosdisponibles($fecha);

    return view('inicio', [
        'horariosDisponibles' => $horariosDisponibles,
        'fechaSeleccionada'   => $fecha,
        'dataServicios' => $dataServicios,
        'dataBarberos' => $dataBarberos
    ]);
}
}
