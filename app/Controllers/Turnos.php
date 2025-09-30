<?php

namespace App\Controllers;
use App\Models\Turnos_db;
use App\Models\Clientes_db;

class Turnos extends BaseController
{
    public function procesar()
{
 try {
        // 1. Insertar cliente usando el modelo
    $clientesModel = new Clientes_db();

    $clienteData = [
        'nombre'   => $this->request->getPost('nombre'),
        'apellido' => $this->request->getPost('apellido'),
        'telefono' => $this->request->getPost('telefono')
    ];

    $idCliente = $clientesModel->insertarClientes($clienteData); // devuelve el id insertado

    // 2. Preparar datos del turno
    $turnoData = [
        'fecha'              => $this->request->getPost('fecha'),
        'id_hora_fk'         => $this->request->getPost('horario'),
        'estado'             => 'pendiente',
        'fecha_notificacion' => date('Y-m-d H:i:s'),
        'estado_msj'         => 'pendiente',
        'id_cliente_fk'      => $idCliente,
        'id_servicio_fk'     => $this->request->getPost('id_servicio'),
        'id_barbero_fk'      => $this->request->getPost('id_barbero'),
    ];


    // 3. Insertar turno 
    $turnosModel = new Turnos_db();
    $turnosModel->crearTurno($turnoData); 
        session()->setFlashdata('success', '¡Tu turno fue registrado correctamente!');
    } catch (\Exception $e) {
        // Mensaje de error
        session()->setFlashdata('error', 'Ocurrió un error al registrar tu turno. Intenta de nuevo.');
    }
   
    return redirect()->to(base_url('/')); // vuelve al inicio



}
}
