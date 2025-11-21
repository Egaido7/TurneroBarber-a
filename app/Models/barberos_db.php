<?php namespace App\Models;
use CodeIgniter\Model;
class Barberos_db extends Model

{ protected $table = 'barberos'; 
    protected $primaryKey = 'id_barbero';
protected $useAutoIncrement = true; protected $returnType = 'array';
protected $useSoftDeletes = false;
protected $allowedFields = ['nombre', 'apellido', 'activo', 'password'];
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

public function traerBarbero($id_barbero){
    return $this->asArray()
                ->where(['id_barbero' => $id_barbero])
                ->first();
}

public function actualizarPassword($id_barbero, $newPassword) {
    return $this->update($id_barbero, ['password' => $newPassword]);

}

public function guardarBarbero($data) {
    return $this->insert($data);
}

    public function eliminarBarbero($id_barbero) {
        return $this->delete($id_barbero);
    }
}