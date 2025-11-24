<?php namespace App\Models;
use CodeIgniter\Model;
class Clientes_db extends Model

{ protected $table = 'clientes'; 
    protected $primaryKey = 'id_cliente';
protected $useAutoIncrement = true; protected $returnType = 'array';
protected $useSoftDeletes = false;
protected $allowedFields = ['nombre', 'apellido', 'email'];
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
        // Verificamos si el cliente ya existe por teléfono O email para no duplicar
        $cliente = $this->where('email', $data['email'])
                        ->first();

        if ($cliente) {
            // Si existe, actualizamos sus datos (por si cambió el nombre o algo) y devolvemos su ID
            $this->update($cliente['id_cliente'], $data);
            return $cliente['id_cliente'];
        } else {
            // Si no existe, lo creamos
            return $this->insert($data);
        }
    }

}