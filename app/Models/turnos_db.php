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

function crearTurno($data){
    return $this->insert($data, true); // devuelve el id del turno insertado
}
public function eliminarTurno($id_turno){
   return $this->delete($id_turno);
}
}


