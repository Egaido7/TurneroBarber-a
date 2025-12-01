<?php namespace App\Models;
use CodeIgniter\Model;

class Diasbloqueados extends Model
{
    protected $table = 'dias_bloqueados';
    protected $primaryKey = 'id_dia';
    protected $allowedFields = ['fecha', 'motivo'];
    protected $returnType = 'array';

    /**
     * Verifica si una fecha está bloqueada.
     * Retorna el motivo si está bloqueada, o NULL si está libre.
     */
    public function esDiaBloqueado($fecha) {
        $dia = $this->where('fecha', $fecha)->first();
        return $dia ? $dia['motivo'] : null;
    }

    public function traerDiasBloqueados() {
        return $this->where('fecha >=', date('Y-m-d'))
                    ->orderBy('fecha', 'ASC')
                    ->findAll();
    }
}