<?php

namespace App\Models;

use App\Entities\MateriaEntity;

class Materia_model extends Base_model
{
    protected $table = 'Materia';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'anio',
    ];

    public function encontrarMateria(array $params)
    {
        $query = $this->db->table($this->table);

        if (isset($params['nombre'])) {
            $query->where('nombre', $params['nombre']);
        }

        if (isset($params['anio'])) {
            $query->where('anio', $params['anio']);
        }

        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        return $query->get()->getRow();
    }

    public function nuevaMateria(array $data)
    {
        $materia = new MateriaEntity();
        $materia->nombre = $data['nombre'] ?? null;
        $materia->anio = (int) ($data['anio'] ?? 0);

        return $this->insert($materia);
    }

    public function actualizarMateria(int $id, array $data)
    {
        $materiaExistente = (object) $this->find($id);
        if (!$materiaExistente) {
            return false;
        }

        $materiaExistente->nombre = $data['nombre'] ?? $materiaExistente->nombre;
        $materiaExistente->anio = isset($data['anio']) ? (int) $data['anio'] : $materiaExistente->anio;

        return $this->update($id, $materiaExistente);
    }
}
