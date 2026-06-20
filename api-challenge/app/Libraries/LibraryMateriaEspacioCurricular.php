<?php

namespace App\Libraries;

use App\Models\Materia_EspacioCurricular_model;
use App\Models\Materia_model;
use App\Models\EspacioCurricular_model;

class LibraryMateriaEspacioCurricular
{
    private Materia_EspacioCurricular_model $model;

    public function __construct()
    {
        $this->model = new Materia_EspacioCurricular_model();
    }

    public function getAll()
    {
        $data['materiasEspaciosCurriculares'] = $this->model->obtenerMatEspCurr();
        return $data;
    }

    public function create(array $data)
    {
        $materiaModel = new Materia_model();
        $existeMateria = $materiaModel->find($data['idMateria']);
        if (!$existeMateria) {
            return [
                'success' => false,
                'message' => 'Materia no encontrada.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $espacioCurricularModel = new EspacioCurricular_model();
        $existeEspacioCurricular = $espacioCurricularModel->find($data['idEspCurr']);
        if (!$existeEspacioCurricular) {
            return [
                'success' => false,
                'message' => 'Espacio Curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $params = [
            'idMateria' => (int) ($data['idMateria'] ?? 0),
            'idEspCurr' => (int) ($data['idEspCurr'] ?? 0),
            'limit' => 1
        ];

        $existeMatEspCurr = $this->model->encontrarMatEspCurr($params);
        if ($existeMatEspCurr) {
            $estaDadaDeBaja = !empty($existeMatEspCurr->deletedBy) || !empty($existeMatEspCurr->deletedAt);

            if ($estaDadaDeBaja) {
                $reactivated = $this->model->reactivarMatEspCurr($params['idMateria'], $params['idEspCurr']);

                return [
                    'success' => $reactivated,
                    'message' => $reactivated ? 'Relación de Materia y Espacio Curricular creada correctamente.' : 'Error al reactivar la relación Materia - Espacio curricular.',
                    'data' => $reactivated ? $this->model->obtenerMatEspCurr($params)[0] : null
                ];
            }

            return [
                'success' => false,
                'message' => 'Ya existe una relación entre la materia y el espacio curricular seleccionados.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $inserted = $this->model->nuevoMatEspCurr($data) !== false;

        $dataMateriaEspacioCurricular = $inserted
            ? $this->model->obtenerMatEspCurr($params)[0]
            : null;

        return [
            'success' => $inserted,
            'message' => $inserted ? 'Relación de Materia y Espacio Curricular creada correctamente.' : 'Error al crear la relación Materia - Espacio curricular.',
            'data' => $dataMateriaEspacioCurricular ?: null
        ];
    }

    public function update(
        int $idMateriaActual,
        int $idEspCurrActual,
        array $data
    ) {
        $paramsActuales = [
            'idMateria' => $idMateriaActual,
            'idEspCurr' => $idEspCurrActual,
            'limit' => 1
        ];

        $materiaEspacioCurricularExistente =
            $this->model->encontrarMatEspCurr($paramsActuales);

        if (!$materiaEspacioCurricularExistente) {
            return [
                'success' => false,
                'message' => 'Materia - Espacio curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $nuevosDatos = [
            'idMateria' => (int) ($data['idMateria'] ?? $idMateriaActual),
            'idEspCurr' => (int) ($data['idEspCurr'] ?? $idEspCurrActual),
        ];

        $materiaModel = new Materia_model();
        $existeMateria = $materiaModel->find($nuevosDatos['idMateria']);

        if (!$existeMateria) {
            return [
                'success' => false,
                'message' => 'Materia no encontrada.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $espacioCurricularModel = new EspacioCurricular_model();
        $existeEspacioCurricular =
            $espacioCurricularModel->find($nuevosDatos['idEspCurr']);

        if (!$existeEspacioCurricular) {
            return [
                'success' => false,
                'message' => 'Espacio Curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $cambioRelacion =
            $nuevosDatos['idMateria'] !== $idMateriaActual
            || $nuevosDatos['idEspCurr'] !== $idEspCurrActual;

        if ($cambioRelacion) {
            $paramsBusqueda = [
                ...$nuevosDatos,
                'limit' => 1
            ];

            $relacionDestino =
                $this->model->encontrarMatEspCurr($paramsBusqueda);

            if ($relacionDestino) {
                $estaDadaDeBaja =
                    !empty($relacionDestino->deletedBy)
                    || !empty($relacionDestino->deletedAt);

                if ($estaDadaDeBaja) {
                    $reactivated = $this->model->reactivarMatEspCurr(
                        $nuevosDatos['idMateria'],
                        $nuevosDatos['idEspCurr']
                    );

                    return [
                        'success' => $reactivated,
                        'message' => $reactivated
                            ? 'Relación de Materia y Espacio Curricular actualizada correctamente.'
                            : 'Error al actualizar la relación Materia - Espacio curricular.',
                        'data' => $reactivated
                            ? ($this->model->obtenerMatEspCurr($paramsBusqueda)[0] ?? null)
                            : null
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

        $updated = $this->model->actualizarMatEspCurr(
            $idMateriaActual,
            $idEspCurrActual,
            $nuevosDatos
        );

        $paramsBusqueda = [
            ...$nuevosDatos,
            'limit' => 1
        ];

        $resultado = $updated
            ? ($this->model->obtenerMatEspCurr($paramsBusqueda)[0] ?? null)
            : null;

        return [
            'success' => $updated,
            'message' => $updated
                ? 'Relación de Materia y Espacio Curricular actualizada correctamente.'
                : 'Error al actualizar la relación Materia - Espacio curricular.',
            'data' => $resultado
        ];
    }

    public function delete(int $idMateria, int $idEspCurr)
    {
        $matEspCurrExistente = $this->model->encontrarMatEspCurr([
            'idMateria' => $idMateria,
            'idEspCurr' => $idEspCurr,
        ]);

        if (!$matEspCurrExistente) {
            return [
                'success' => false,
                'message' => 'Relación Materia - Espacio curricular no encontrada.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $this->model->eliminarMatEspCurr($idMateria, $idEspCurr);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Relación eliminada exitosamente.' : 'Error al eliminar la relación.',
            'data' => null
        ];
    }
}
