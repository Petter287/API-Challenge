<?php

namespace App\Models;

use App\Entities\EstadoEspacioCurricularEntity;

class Estado_EspacioCurricular_model extends Base_model
{
    protected $table = 'Estado_EspacioCurricular';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'estado'
    ];

    public function encontrarEstado(array $params)
    {
        $query = $this->db->table($this->table);

        if (isset($params['estado'])) {
            $query->where('estado', $params['estado']);
        }

        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        return $query->get()->getRow();
    }

    public function nuevoEstado(array $data)
    {
        $estado = new EstadoEspacioCurricularEntity();
        $estado->estado = $data['estado'] ?? null;

        return $this->insert($estado);
    }

    public function actualizarEstado(int $id, array $data)
    {
        $estadoExistente = (object) $this->find($id);
        if (!$estadoExistente) {
            return false;
        }

        $estadoExistente->estado = $data['estado'] ?? $estadoExistente->estado;

        return $this->update($id, $estadoExistente);
    }
}
