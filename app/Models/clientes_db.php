<?php namespace App\Models;
use CodeIgniter\Model;
class Clientes_db extends Model

{ protected $table = 'clientes'; 
    protected $primaryKey = 'id_cliente';
protected $useAutoIncrement = true; protected $returnType = 'array';
protected $useSoftDeletes = false;
protected $allowedFields = ['nombre', 'apellido', 'telefono'];
protected $useTimestamps = false; // Dates
protected $dateFormat = 'datetime';
protected $createdField = 'created_at';
protected $updatedField = 'updated_at';
protected $deletedField = 'deleted_at';
protected $validationRules = []; // Validation
protected $validationMessages = [];
protected $skipValidation = false;
protected $cleanValidationRules = true;


 
public function traerClientes(){
     return $this->findAll();
}

public function insertarClientes($data){
    return $this->insert($data);
}

}