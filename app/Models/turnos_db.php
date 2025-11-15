<?php namespace App\Models;
use CodeIgniter\Model;
class Turnos_db extends Model

{ protected $table = 'turnos'; 
    protected $primaryKey = 'id_turno';
protected $useAutoIncrement = true; protected $returnType = 'array';
protected $useSoftDeletes = false;
protected $allowedFields = ['fecha', 'id_hora_fk', 'estado', 'fecha_notificacion','estado_msj' , 'id_cliente_fk', 'id_barbero_fk', 'id_servicio_fk'];
protected $useTimestamps = true; // Dates
protected $dateFormat = 'date';
protected $createdField = 'fecha';
protected $updatedField = '';
protected $deletedField = '';
protected $validationRules = []; // Validation
protected $validationMessages = [];
protected $skipValidation = false;
protected $cleanValidationRules = true;



function traerTurnos(){
     return $this->findAll();
}

public function obtenerTurnosConDetalles($fecha) {
        $builder = $this->db->table('turnos t');
        $builder->select('t.id_turno, t.fecha, t.estado, c.nombre AS cliente_nombre, b.nombre AS barbero_nombre, s.nombre AS servicio_nombre, s.precio_total AS servicio_precio, h.horario AS hora_turno');
        $builder->join('clientes c', 't.id_cliente_fk = c.id_cliente');
        $builder->join('barberos b', 't.id_barbero_fk = b.id_barbero');
        $builder->join('servicios s', 't.id_servicio_fk = s.id_servicio');
        $builder->join('horario h', 't.id_hora_fk = h.id_horario'); // Corregido a id_horario
        
        // --- LA LÍNEA CLAVE ---
        $builder->where('t.fecha', $fecha); // Filtramos por la fecha seleccionada
        
        $builder->orderBy('h.horario', 'ASC'); // Ordenamos por hora
        
        $query = $builder->get();
        return $query->getResultArray();
    }


function crearTurno($data){
    return $this->insert($data, true); // devuelve el id del turno insertado
}
public function eliminarTurno($id_turno){
   return $this->delete($id_turno);
}
}


