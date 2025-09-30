<?php namespace App\Models;
use CodeIgniter\Model;
class Barberos_db extends Model

{ protected $table = 'barberos'; 
    protected $primaryKey = 'id_barbero';
protected $useAutoIncrement = true; protected $returnType = 'array';
protected $useSoftDeletes = false;
protected $allowedFields = [' nombre', 'apellido', 'activo'];
protected $useTimestamps = false; // Dates
protected $dateFormat = 'datetime';
protected $createdField = 'created_at';
protected $updatedField = 'updated_at';
protected $deletedField = 'deleted_at';
protected $validationRules = []; // Validation
protected $validationMessages = [];
protected $skipValidation = false;
protected $cleanValidationRules = true;


public function traerBarberos(){
     return $this->findAll();
}

 

}