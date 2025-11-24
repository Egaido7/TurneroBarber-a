<?php namespace App\Models;
use CodeIgniter\Model;
class Turnos_db extends Model

{ protected $table = 'turnos'; 
    protected $primaryKey = 'id_turno';
protected $useAutoIncrement = true; protected $returnType = 'array';
protected $useSoftDeletes = false;
protected $allowedFields = ['fecha', 'id_hora_fk', 'estado', 'fecha_notificacion','estado_msj' , 'id_cliente_fk', 'id_barbero_fk', 'id_servicio_fk', 'token_reprogramar'];
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


 // --- ¡NUEVAS FUNCIONES PARA ESTADÍSTICAS! ---

    /**
     * Obtiene los KPIs principales (Total de Turnos e Ingresos) para un mes y año.
     * Solo cuenta turnos que no estén 'cancelado'.
     */
    public function getEstadisticasMes($mes, $anio)
    {
        $builder = $this->db->table('turnos t');
        $builder->select('COUNT(t.id_turno) as total_turnos, SUM(s.precio_total) as total_ingresos');
        $builder->join('servicios s', 't.id_servicio_fk = s.id_servicio');
        $builder->where('MONTH(t.fecha)', $mes);
        $builder->where('YEAR(t.fecha)', $anio);
        $builder->where('t.estado !=', 'cancelado'); // No contamos turnos cancelados
        $result = $builder->get()->getRowArray();

        return [
            'total_turnos' => $result['total_turnos'] ?? 0,
            'total_ingresos' => $result['total_ingresos'] ?? 0
        ];
    }

    /**
     * Obtiene el conteo de clientes cuyo *primer* turno fue en un mes y año específicos.
     */
    public function getClientesNuevosMes($mes, $anio)
    {
        // Subquery para encontrar la primera fecha de turno de cada cliente
        $subQuery = $this->db->table('turnos')
                            ->select('id_cliente_fk, MIN(fecha) as primera_fecha')
                            ->groupBy('id_cliente_fk')
                            ->getCompiledSelect();
        
        // Query principal: cuenta cuántos de esos "primeros turnos" ocurrieron en el mes/año
        $builder = $this->db->table("($subQuery) as clientes_nuevos");
        $builder->select('COUNT(id_cliente_fk) as total_nuevos');
        $builder->where('MONTH(primera_fecha)', $mes);
        $builder->where('YEAR(primera_fecha)', $anio);
        $result = $builder->get()->getRowArray();
        
        return $result['total_nuevos'] ?? 0;
    }

    /**
     * Obtiene los 5 servicios más populares (por conteo de turnos)
     */
    public function getServiciosPopularesMes($mes, $anio)
    {
        $builder = $this->db->table('turnos t');
        $builder->select('s.nombre, COUNT(t.id_turno) as total');
        $builder->join('servicios s', 't.id_servicio_fk = s.id_servicio');
        $builder->where('MONTH(t.fecha)', $mes);
        $builder->where('YEAR(t.fecha)', $anio);
        $builder->where('t.estado !=', 'cancelado');
        $builder->groupBy('s.nombre');
        $builder->orderBy('total', 'DESC');
        $builder->limit(5); // Top 5
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene los 3 barberos más populares (por conteo de turnos)
     */
    public function getBarberosPopularesMes($mes, $anio)
    {
        $builder = $this->db->table('turnos t');
        $builder->select('b.nombre, b.apellido, COUNT(t.id_turno) as total');
        $builder->join('barberos b', 't.id_barbero_fk = b.id_barbero');
        $builder->where('MONTH(t.fecha)', $mes);
        $builder->where('YEAR(t.fecha)', $anio);
        $builder->where('t.estado !=', 'cancelado');
        $builder->groupBy('b.nombre, b.apellido'); // Agrupar por nombre y apellido
        $builder->orderBy('total', 'DESC');
        $builder->limit(3); // Top 3
        
        return $builder->get()->getResultArray();
    }

       public function getTurnoDetalles($id_turno) {
        $builder = $this->db->table('turnos t');
        $builder->select('t.*, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido, c.email AS cliente_email, s.nombre AS servicio_nombre, h.horario AS hora_turno');
        $builder->join('clientes c', 't.id_cliente_fk = c.id_cliente');
        $builder->join('servicios s', 't.id_servicio_fk = s.id_servicio');
        $builder->join('horario h', 't.id_hora_fk = h.id_horario');
        $builder->where('t.id_turno', $id_turno);
        
        $query = $builder->get();
        return $query->getRowArray();
    }

    /**
     * Actualiza (sobrescribe) un turno existente con nueva fecha y hora.
     */
    public function reprogramarTurno($id_turno, $data) {
        // $data contendrá ['fecha', 'id_hora_fk', 'estado']
        return $this->update($id_turno, $data);
    }

    public function getTurnoByToken($token) {
        $builder = $this->db->table('turnos t');
        $builder->select('t.*, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido, c.email AS cliente_email, s.nombre AS servicio_nombre, h.horario AS hora_turno');
        $builder->join('clientes c', 't.id_cliente_fk = c.id_cliente');
        $builder->join('servicios s', 't.id_servicio_fk = s.id_servicio');
        $builder->join('horario h', 't.id_hora_fk = h.id_horario');
        $builder->where('t.token_reprogramar', $token);
        
        $query = $builder->get();
        return $query->getRowArray();
    }
}




