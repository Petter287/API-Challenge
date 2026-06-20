<?php

namespace App\Models;

use App\Entities\EspacioCurricularEntity;

class EspacioCurricular_model extends Base_model
{
    protected $table = 'Espacio_Curricular';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'idPeriodo'
    ];

    public function obtenerEspaciosCurriculares($params = [])
    {
        $query = $this->select('Espacio_Curricular.*, Periodo.nombre AS periodoNombre')
                       ->join('Periodo', 'Periodo.id = Espacio_Curricular.idPeriodo');

        if (isset($params['id'])) {
            $query->where('Espacio_Curricular.id', $params['id']);
        }

        // $dump = $query->findAll();
        // log_message('error', 'Espacios curriculares obtenidos: ' . json_encode($dump));
        // return $dump;
        return $query->findAll();
    }

    public function encontrarEspacioCurricular(array $params)
    {
        $query = $this->db->table($this->table);

        if (isset($params['nombre'])) {
            $query->where('nombre', $params['nombre']);
        }

        if (isset($params['idPeriodo'])) {
            $query->where('idPeriodo', $params['idPeriodo']);
        }

        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        return $query->get()->getRow();
    }

    public function nuevoEspacioCurricular(array $data)
    {
        $espacioCurricular = new EspacioCurricularEntity();
        $espacioCurricular->nombre = $data['nombre'] ?? null;
        $espacioCurricular->idPeriodo = (int) ($data['idPeriodo'] ?? 0);

        return $this->insert($espacioCurricular);
    }

    public function actualizarEspacioCurricular(int $id, array $data)
    {
        $espacioCurricularExistente = (object) $this->find($id);
        if (!$espacioCurricularExistente) {
            return false;
        }

        $espacioCurricularExistente->nombre = $data['nombre'] ?? $espacioCurricularExistente->nombre;
        $espacioCurricularExistente->idPeriodo = isset($data['idPeriodo']) ? (int) $data['idPeriodo'] : $espacioCurricularExistente->idPeriodo;

        return $this->update($id, $espacioCurricularExistente);
    }
}
