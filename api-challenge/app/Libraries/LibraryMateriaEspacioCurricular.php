<?php

namespace App\Libraries;

use App\Entities\MateriaEspacioCurricularEntity;
use App\Models\Materia_EspacioCurricular_model;
use App\Models\Materia_model;
use App\Models\EspacioCurricular_model;

class LibraryMateriaEspacioCurricular
{
    public function getAll()
    {
        $data['materiasEspaciosCurriculares'] = db_connect()
            ->table('Materia_EspacioCurricular')
            ->select('Materia_EspacioCurricular.*, Materia.nombre as nombreMateria, Espacio_Curricular.nombre as nombreEspacioCurricular')
            ->join('Materia', 'Materia.id = Materia_EspacioCurricular.idMateria')
            ->join('Espacio_Curricular', 'Espacio_Curricular.id = Materia_EspacioCurricular.idEspCurr')
            ->where('Materia_EspacioCurricular.deletedBy', null)
            ->where('Materia_EspacioCurricular.deletedAt', null)
            ->get()
            ->getResultArray();

        return $data;
    }

    public function create(array $data)
    {
        $idMateria = (int) ($data['idMateria'] ?? 0);
        $idEspCurr = (int) ($data['idEspCurr'] ?? 0);

        $validacionMateria = $this->validarMateria($idMateria);

        if ($validacionMateria) {
            return $validacionMateria;
        }

        $validacionEspacioCurricular = $this->validarEspacioCurricular($idEspCurr);

        if ($validacionEspacioCurricular) {
            return $validacionEspacioCurricular;
        }

        $materiaEspacioCurricular = new MateriaEspacioCurricularEntity();
        $materiaEspacioCurricular->idMateria = $idMateria;
        $materiaEspacioCurricular->idEspCurr = $idEspCurr;

        $model = new Materia_EspacioCurricular_model();

        $existeMateriaEspacioCurricular = db_connect()
            ->table('Materia_EspacioCurricular')
            ->where('idMateria', $idMateria)
            ->where('idEspCurr', $idEspCurr)
            ->get()
            ->getRowArray();

        if ($existeMateriaEspacioCurricular) {
            $estaDadaDeBaja = !empty($existeMateriaEspacioCurricular['deletedBy'])
                || !empty($existeMateriaEspacioCurricular['deletedAt']);

            if ($estaDadaDeBaja) {
                $reactivated = db_connect()
                    ->table('Materia_EspacioCurricular')
                    ->where('idMateria', $idMateria)
                    ->where('idEspCurr', $idEspCurr)
                    ->update([
                        'deletedBy' => null,
                        'deletedAt' => null,
                        'updatedBy' => 'system',
                        'updatedAt' => date('Y-m-d H:i:s')
                    ]);

                return [
                    'success' => $reactivated,
                    'message' => $reactivated ? 'Relación de Materia y Espacio Curricular creada correctamente.' : 'Error al reactivar la relación Materia - Espacio curricular.',
                    'data' => $reactivated ? $this->findWithMateriaEspacioCurricular($idMateria, $idEspCurr) : null
                ];
            }

            return [
                'success' => false,
                'message' => 'Ya existe una relación entre la materia y el espacio curricular seleccionados.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $inserted = $model->insert($materiaEspacioCurricular) !== false;

        $dataMateriaEspacioCurricular = $inserted
            ? $this->findWithMateriaEspacioCurricular($idMateria, $idEspCurr)
            : null;

        return [
            'success' => $inserted,
            'message' => $inserted ? 'Relación de Materia y Espacio Curricular creada correctamente.' : 'Error al crear la relación Materia - Espacio curricular.',
            'data' => $dataMateriaEspacioCurricular ?: null
        ];
    }

    public function update(int $idMateriaActual, int $idEspCurrActual, array $data)
    {
        $idMateria = (int) ($data['idMateria'] ?? 0);
        $idEspCurr = (int) ($data['idEspCurr'] ?? 0);

        $materiaEspacioCurricularExistente = db_connect()
            ->table('Materia_EspacioCurricular')
            ->where('idMateria', $idMateriaActual)
            ->where('idEspCurr', $idEspCurrActual)
            ->where('deletedBy', null)
            ->where('deletedAt', null)
            ->get()
            ->getRowArray();

        if (!$materiaEspacioCurricularExistente) {
            return [
                'success' => false,
                'message' => 'Materia - Espacio curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $validacionMateria = $this->validarMateria($idMateria);

        if ($validacionMateria) {
            return $validacionMateria;
        }

        $validacionEspacioCurricular = $this->validarEspacioCurricular($idEspCurr);

        if ($validacionEspacioCurricular) {
            return $validacionEspacioCurricular;
        }

        $cambioRelacion = $idMateria !== $idMateriaActual
            || $idEspCurr !== $idEspCurrActual;

        if ($cambioRelacion) {
            $existeMateriaEspacioCurricular = db_connect()
                ->table('Materia_EspacioCurricular')
                ->where('idMateria', $idMateria)
                ->where('idEspCurr', $idEspCurr)
                ->get()
                ->getRowArray();

            if ($existeMateriaEspacioCurricular) {
                $estaDadaDeBaja = !empty($existeMateriaEspacioCurricular['deletedBy'])
                    || !empty($existeMateriaEspacioCurricular['deletedAt']);

                if ($estaDadaDeBaja) {
                    $db = db_connect();
                    $db->transStart();

                    $db->table('Materia_EspacioCurricular')
                        ->where('idMateria', $idMateriaActual)
                        ->where('idEspCurr', $idEspCurrActual)
                        ->update([
                            'deletedBy' => 'system',
                            'deletedAt' => date('Y-m-d H:i:s')
                        ]);

                    $db->table('Materia_EspacioCurricular')
                        ->where('idMateria', $idMateria)
                        ->where('idEspCurr', $idEspCurr)
                        ->update([
                            'deletedBy' => null,
                            'deletedAt' => null,
                            'updatedBy' => 'system',
                            'updatedAt' => date('Y-m-d H:i:s')
                        ]);

                    $db->transComplete();

                    $updated = $db->transStatus();

                    return [
                        'success' => $updated,
                        'message' => $updated ? 'Relación Materia - Espacio curricular actualizada exitosamente.' : 'Error al actualizar la relación Materia - Espacio curricular.',
                        'data' => $updated ? $this->findWithMateriaEspacioCurricular($idMateria, $idEspCurr) : null
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Ya existe una relación entre la materia y el espacio curricular seleccionados.',
                    'data' => null,
                    'statusCode' => 409
                ];
            }
        }

        $updated = db_connect()
            ->table('Materia_EspacioCurricular')
            ->where('idMateria', $idMateriaActual)
            ->where('idEspCurr', $idEspCurrActual)
            ->update([
                'idMateria' => $idMateria,
                'idEspCurr' => $idEspCurr,
                'updatedBy' => 'system',
                'updatedAt' => date('Y-m-d H:i:s')
            ]);

        return [
            'success' => $updated,
            'message' => $updated ? 'Relación Materia - Espacio curricular actualizada exitosamente.' : 'Error al actualizar la relación Materia - Espacio curricular.',
            'data' => $updated ? $this->findWithMateriaEspacioCurricular($idMateria, $idEspCurr) : null
        ];
    }

    public function delete(int $idMateria, int $idEspCurr)
    {
        $existente = db_connect()
            ->table('Materia_EspacioCurricular')
            ->where('idMateria', $idMateria)
            ->where('idEspCurr', $idEspCurr)
            ->where('deletedBy', null)
            ->where('deletedAt', null)
            ->get()
            ->getRowArray();

        if (!$existente) {
            return [
                'success' => false,
                'message' => 'Relación Materia - Espacio curricular no encontrada.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = db_connect()
            ->table('Materia_EspacioCurricular')
            ->where('idMateria', $idMateria)
            ->where('idEspCurr', $idEspCurr)
            ->update([
                'deletedBy' => 'system',
                'deletedAt' => date('Y-m-d H:i:s')
            ]);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Relación eliminada exitosamente.' : 'Error al eliminar la relación.',
            'data' => null
        ];
    }

    private function findWithMateriaEspacioCurricular(int $idMateria, int $idEspCurr)
    {
        return db_connect()
            ->table('Materia_EspacioCurricular')
            ->select('Materia_EspacioCurricular.*, Materia.nombre AS nombreMateria, Espacio_Curricular.nombre AS nombreEspacioCurricular')
            ->join('Materia', 'Materia.id = Materia_EspacioCurricular.idMateria')
            ->join('Espacio_Curricular', 'Espacio_Curricular.id = Materia_EspacioCurricular.idEspCurr')
            ->where('Materia_EspacioCurricular.idMateria', $idMateria)
            ->where('Materia_EspacioCurricular.idEspCurr', $idEspCurr)
            ->where('Materia_EspacioCurricular.deletedBy', null)
            ->where('Materia_EspacioCurricular.deletedAt', null)
            ->get()
            ->getRowArray();
    }

    private function validarMateria(int $idMateria): ?array
    {
        $materiaModel = new Materia_model();
        $materia = $materiaModel->find($idMateria);

        if (!$materia) {
            return [
                'success' => false,
                'message' => 'La materia seleccionada no existe.',
                'data' => null,
                'statusCode' => 400
            ];
        }

        return null;
    }

    private function validarEspacioCurricular(int $idEspacioCurricular): ?array
    {
        $espCurrModel = new EspacioCurricular_model();
        $espCurr = $espCurrModel->find($idEspacioCurricular);

        if (!$espCurr) {
            return [
                'success' => false,
                'message' => 'El Espacio Curricular seleccionado no existe.',
                'data' => null,
                'statusCode' => 400
            ];
        }

        return null;
    }
}
