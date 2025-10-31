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
                'id_hora_fk'         => $this->request->getPost('horario'), // Este es el ID del horario
                'estado'             => 'pendiente_pago',
                'fecha_notificacion' => date('Y-m-d H:i:s'),
                'estado_msj'         => 'enviado',
                'id_cliente_fk'      => $idCliente,
                'id_servicio_fk'     => $this->request->getPost('id_servicio'),
                'id_barbero_fk'      => $this->request->getPost('id_barbero'),
            ];

            // 3. Insertar turno 
            $turnosModel = new Turnos_db();
            $turnosModel->crearTurno($turnoData); 

            // --- ¡ÉXITO! Preparamos los datos para la vista de proceso ---

            // Uso 'exito' como clave, que es lo que la vista espera
            session()->setFlashdata('exito', '¡Tu turno fue registrado correctamente!');
            
            session()->setFlashdata('servicio', 'Servicio ID: ' . $this->request->getPost('id_servicio')); // Temporal

            session()->setFlashdata('fecha', $this->request->getPost('fecha'));

            session()->setFlashdata('horario', 'Horario ID: ' . $this->request->getPost('horario')); // Temporal
            

        } catch (\Exception $e) {
            // --- ¡ERROR! el mensaje de error ---
            // Mensaje de error (uso 'error' como clave, que es lo que la vista espera)
            session()->setFlashdata('error', 'Ocurrió un error al registrar tu turno. Intenta de nuevo.');

        }
        return redirect()->to(site_url('proceso-reserva'));
    }


    public function resultado()
    {
        return view('proceso');
    }
}
