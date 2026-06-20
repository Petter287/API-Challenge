<?php

namespace App\Models;

use App\Entities\PeriodoEntity;

class Periodo_model extends Base_model
{
    protected $table = 'Periodo';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre'
    ];

    public function encontrarPeriodo(array $params)
    {
        $query = $this->db->table($this->table);

        if (isset($params['nombre'])) {
            $query->where('nombre', $params['nombre']);
        }

        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        return $query->get()->getRow();
    }

    public function nuevoPeriodo(array $data)
    {
        $periodo = new PeriodoEntity();
        $periodo->nombre = $data['nombre'] ?? null;

        return $this->insert($periodo);
    }

    public function actualizarPeriodo(int $id, array $data)
    {
        $periodoExistente = (object) $this->find($id);
        if (!$periodoExistente) {
            return false;
        }

        $periodoExistente->nombre = $data['nombre'] ?? $periodoExistente->nombre;

        return $this->update($id, $periodoExistente);
    }
}
