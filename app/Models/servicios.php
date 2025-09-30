<?php namespace App\Models;
use CodeIgniter\Model;
class Servicios extends Model

{ protected $table = 'servicios'; 
    protected $primaryKey = 'id_servicio';
protected $useAutoIncrement = true; protected $returnType = 'array';
protected $useSoftDeletes = false;
protected $allowedFields = ['nombre', 'descripcion', 'precio_total', 'monto_seña'];
protected $useTimestamps = false; // Dates
protected $dateFormat = 'datetime';
protected $createdField = 'created_at';
protected $updatedField = 'updated_at';
protected $deletedField = 'deleted_at';
protected $validationRules = []; // Validation
protected $validationMessages = [];
protected $skipValidation = false;
protected $cleanValidationRules = true;


function traerServicios(){
     return $this->findAll();
}

function editarServicio ($id_servicio, $servicio){
    $this->update($id_servicio, $servicio);
}

function nuevoServicio($servicio){
 $this->insert($servicio);
}

}