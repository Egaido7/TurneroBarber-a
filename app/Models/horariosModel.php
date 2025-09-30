<?php namespace App\Models;
use CodeIgniter\Model;
class HorariosModel extends Model

{ protected $table = 'horario'; 
    protected $primaryKey = 'id_horario';
protected $useAutoIncrement = true; protected $returnType = 'array';
protected $useSoftDeletes = false;
protected $allowedFields = ['horario'];
protected $useTimestamps = false; // Dates
protected $dateFormat = 'datetime';
protected $createdField = 'created_at';
protected $updatedField = 'updated_at';
protected $deletedField = 'deleted_at';
protected $validationRules = []; // Validation
protected $validationMessages = [];
protected $skipValidation = false;
protected $cleanValidationRules = true;

function traerHorarios(){
     return $this->findAll();
}


public function horariosDisponibles($fecha)
{
    return $this->db->table('horario h')
        ->select('h.id_horario, h.horario')
        ->join('turnos t', 'h.id_horario = t.id_hora_fk AND t.fecha = "'.$fecha.'" AND t.estado != "cancelado"', 'left')
        ->where('t.id_turno IS NULL')
        ->get()
        ->getResultArray(); // 👈 importante
}


}

