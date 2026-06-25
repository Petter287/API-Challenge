<?php

namespace App\Models;

use App\Entities\EstudianteEspacioCurricularEntity;

class Estudiante_EspacioCurricular_model extends Base_model
{
    protected $table = 'Estudiante_EspacioCurricular';
    protected $primaryKey = null;

    protected $allowedFields = [
        'idEstudiante',
        'idEspCurr',
        'idEstadoEspCurr'
    ];

    public function obtenerEstEspCurr($params = [])
    {
        $query = $this->db
            ->table($this->table)
            ->select('Estudiante_EspacioCurricular.*, Estudiante.nombre AS nombreEstudiante, Estudiante.apellido AS apellidoEstudiante, Espacio_Curricular.nombre AS nombreEspacioCurricular, Estado_EspacioCurricular.estado AS estadoEspacioCurricular')
            ->join('Estudiante', 'Estudiante_EspacioCurricular.idEstudiante = Estudiante.id')
            ->join('Espacio_Curricular', 'Estudiante_EspacioCurricular.idEspCurr = Espacio_Curricular.id')
            ->join('Estado_EspacioCurricular', 'Estudiante_EspacioCurricular.idEstadoEspCurr = Estado_EspacioCurricular.id')
            ->where('Estudiante_EspacioCurricular.deletedBy', null)
            ->where('Estudiante_EspacioCurricular.deletedAt', null);

        if (isset($params['idEstudiante']) && isset($params['idEspCurr'])) {
            $query->where('Estudiante_EspacioCurricular.idEstudiante', $params['idEstudiante'])
                ->where('Estudiante_EspacioCurricular.idEspCurr', $params['idEspCurr']);
        }

        return $query->get()->getResultArray();
    }

    public function encontrarEstEspCurr(array $params)
    {
        $query = $this->db->table($this->table);

        if (isset($params['idEstudiante'])) {
            $query->where('idEstudiante', $params['idEstudiante']);
        }

        if (isset($params['idEspCurr'])) {
            $query->where('idEspCurr', $params['idEspCurr']);
        }

        if (isset($params['idEstadoEspCurr'])) {
            $query->where('idEstadoEspCurr', $params['idEstadoEspCurr']);
        }

        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        return $query->get()->getRow();
    }

    public function obtenerEstadosMateriaPorEstudiante(int $idEstudiante): array
    {
        $materiasDelEstudiante = $this->db
            ->table('Materia_EspacioCurricular MEC_Estudiante')
            ->select('MEC_Estudiante.idMateria')
            ->join(
                'Estudiante_EspacioCurricular EEC_Estudiante',
                'EEC_Estudiante.idEspCurr = MEC_Estudiante.idEspCurr'
            )
            ->where('EEC_Estudiante.idEstudiante', $idEstudiante)
            ->where('EEC_Estudiante.deletedBy', null)
            ->where('EEC_Estudiante.deletedAt', null)
            ->where('MEC_Estudiante.deletedBy', null)
            ->where('MEC_Estudiante.deletedAt', null);

        $condicionEstudiante = sprintf(
            'EEC.idEspCurr = MEC.idEspCurr AND EEC.idEstudiante = %d'
            . ' AND EEC.deletedBy IS NULL AND EEC.deletedAt IS NULL',
            $idEstudiante
        );

        return $this->db->table('Materia_EspacioCurricular MEC')
            ->select(
                'M.id AS idMateria, M.nombre AS nombreMateria, M.anio AS anioMateria,'
                . ' EC.id AS idEspCurr, EC.nombre AS nombreEspCurr,'
                . ' Estado.estado AS estadoEspCurr'
            )
            ->join('Materia M', 'M.id = MEC.idMateria')
            ->join('Espacio_Curricular EC', 'EC.id = MEC.idEspCurr')
            ->join('Estudiante_EspacioCurricular EEC', $condicionEstudiante, 'left')
            ->join(
                'Estado_EspacioCurricular Estado',
                'Estado.id = EEC.idEstadoEspCurr'
                . ' AND Estado.deletedBy IS NULL AND Estado.deletedAt IS NULL',
                'left'
            )
            ->whereIn('MEC.idMateria', $materiasDelEstudiante)
            ->where('MEC.deletedBy', null)
            ->where('MEC.deletedAt', null)
            ->where('M.deletedBy', null)
            ->where('M.deletedAt', null)
            ->where('EC.deletedBy', null)
            ->where('EC.deletedAt', null)
            ->orderBy('M.id', 'ASC')
            ->orderBy('EC.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function nuevoEstEspCurr(array $data)
    {
        $estEspCurr = new EstudianteEspacioCurricularEntity();
        $estEspCurr->idEstudiante = (int) ($data['idEstudiante'] ?? 0);
        $estEspCurr->idEspCurr = (int) ($data['idEspCurr'] ?? 0);
        $estEspCurr->idEstadoEspCurr = (int) ($data['idEstadoEspCurr'] ?? 0);

        return $this->insert($estEspCurr);
    }

    public function reactivarEstEspCurr(int $idEstudiante, int $idEspCurr, int $idEstadoEspCurr)
    {
        $estEspCurrExistente = $this->encontrarEstEspCurr([
            'idEstudiante' => $idEstudiante,
            'idEspCurr' => $idEspCurr,
            'limit' => 1
        ]);

        if (!$estEspCurrExistente) {
            return false;
        }

        $estEspCurrExistente->idEstadoEspCurr = $idEstadoEspCurr;
        $estEspCurrExistente->deletedBy = null;
        $estEspCurrExistente->deletedAt = null;
        $estEspCurrExistente->updatedBy = 'system';
        $estEspCurrExistente->updatedAt = date('Y-m-d H:i:s');

        return $this->actualizarEstEspCurr($idEstudiante, $idEspCurr, (array) $estEspCurrExistente);
    }
    public function actualizarEstEspCurr(int $idEstudianteActual, int $idEspCurrActual, array $data): bool
    {
        $data['updatedBy'] = $data['updatedBy'] ?? 'system';
        $data['updatedAt'] = $data['updatedAt'] ?? date('Y-m-d H:i:s');

        return $this->db->table($this->table)
            ->where('idEstudiante', $idEstudianteActual)
            ->where('idEspCurr', $idEspCurrActual)
            ->update($data);
    }

    public function eliminarEstEspCurr(int $idEstudiante, int $idEspCurr): bool
    {
        $estEspCurrExistente = $this->encontrarEstEspCurr([
            'idEstudiante' => $idEstudiante,
            'idEspCurr' => $idEspCurr,
        ]);

        if (!$estEspCurrExistente) {
            return false;
        }

        return $this->db->table($this->table)
            ->where('idEstudiante', $idEstudiante)
            ->where('idEspCurr', $idEspCurr)
            ->where('deletedBy', null)
            ->where('deletedAt', null)
            ->update([
                'deletedBy' => 'system',
                'deletedAt' => date('Y-m-d H:i:s')
            ]);
    }
}
