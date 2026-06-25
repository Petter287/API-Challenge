<?php

namespace App\Models;

use App\Entities\MateriaEspacioCurricularEntity;

class Materia_EspacioCurricular_model extends Base_model
{
    protected $table = 'Materia_EspacioCurricular';
    protected $primaryKey = null;

    protected $allowedFields = [
        'idMateria',
        'idEspCurr'
    ];

    public function obtenerMatEspCurr($params = [])
    {
        $query = $this->select('Materia_EspacioCurricular.*, Materia.nombre as nombreMateria, Espacio_Curricular.nombre as nombreEspacioCurricular')
            ->join('Materia', 'Materia.id = Materia_EspacioCurricular.idMateria')
            ->join('Espacio_Curricular', 'Espacio_Curricular.id = Materia_EspacioCurricular.idEspCurr')
            ->where('Materia_EspacioCurricular.deletedBy', null)
            ->where('Materia_EspacioCurricular.deletedAt', null);

        if (isset($params['idMateria']) && isset($params['idEspCurr'])) {
            $query->where('Materia_EspacioCurricular.idMateria', $params['idMateria'])
                ->where('Materia_EspacioCurricular.idEspCurr', $params['idEspCurr']);
        }

        return $query->get()->getResultArray();
    }

    public function encontrarMatEspCurr(array $params)
    {
        $query = $this->db->table($this->table);

        if (isset($params['idMateria'])) {
            $query->where('idMateria', $params['idMateria']);
        }

        if (isset($params['idEspCurr'])) {
            $query->where('idEspCurr', $params['idEspCurr']);
        }

        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        return $query->get()->getRow();
    }

    public function nuevoMatEspCurr(array $data)
    {
        $matEspCurr = new MateriaEspacioCurricularEntity();
        $matEspCurr->idMateria = (int) ($data['idMateria'] ?? 0);
        $matEspCurr->idEspCurr = (int) ($data['idEspCurr'] ?? 0);

        return $this->insert($matEspCurr);
    }

    public function reactivarMatEspCurr(int $idMateria, int $idEspCurr)
    {
        $matEspCurrExistente = $this->encontrarMatEspCurr([
            'idMateria' => $idMateria,
            'idEspCurr' => $idEspCurr,
            'limit' => 1
        ]);

        if (!$matEspCurrExistente) {
            return false;
        }

        $matEspCurrExistente->deletedBy = null;
        $matEspCurrExistente->deletedAt = null;
        $matEspCurrExistente->updatedBy = 'system';
        $matEspCurrExistente->updatedAt = date('Y-m-d H:i:s');

        return $this->actualizarMatEspCurr($idMateria, $idEspCurr, (array) $matEspCurrExistente);
    }

    public function actualizarMatEspCurr(int $idMateriaActual, int $idEspCurrActual, array $data)
    {
        $data['updatedBy'] = $data['updatedBy'] ?? 'system';
        $data['updatedAt'] = $data['updatedAt'] ?? date('Y-m-d H:i:s');

        return $this->db->table($this->table)
            ->where('idMateria', $idMateriaActual)
            ->where('idEspCurr', $idEspCurrActual)
            ->update($data);
    }

    public function eliminarMatEspCurr(int $idMateria, int $idEspCurr)
    {
        $matEspCurrExistente = $this->encontrarMatEspCurr([
            'idMateria' => $idMateria,
            'idEspCurr' => $idEspCurr,
        ]);

        if (!$matEspCurrExistente) {
            return false;
        }

        return $this->db->table($this->table)
            ->where('idMateria', $idMateria)
            ->where('idEspCurr', $idEspCurr)
            ->where('deletedBy', null)
            ->where('deletedAt', null)
            ->update([
                'deletedBy' => 'system',
                'deletedAt' => date('Y-m-d H:i:s')
            ]);
    }
}
