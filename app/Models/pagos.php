<?php namespace App\Models;
use CodeIgniter\Model;
class Pagos_db extends Model

{ protected $table = 'pagos'; 
    protected $primaryKey = 'id_pago';
protected $useAutoIncrement = true; protected $returnType = 'array';
protected $useSoftDeletes = false;
protected $allowedFields = ['monto', 'fecha_pago', 'id_transaccion_externa', 'estado_pago','id_turno_fk'];
protected $useTimestamps = true; // Dates
protected $dateFormat = 'date';
protected $createdField = 'fecha_pago';
protected $updatedField = '';
protected $deletedField = '';
protected $validationRules = []; // Validation
protected $validationMessages = [];
protected $skipValidation = false;
protected $cleanValidationRules = true;


 

}