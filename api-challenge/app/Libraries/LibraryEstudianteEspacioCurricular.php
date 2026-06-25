<?php

namespace App\Libraries;

use App\Models\Estudiante_model;
use App\Models\EspacioCurricular_model;
use App\Models\Estado_EspacioCurricular_model;
use App\Models\Estudiante_EspacioCurricular_model;

class LibraryEstudianteEspacioCurricular
{
    private const ESTADOS_VALIDOS = [
        'sin_calificar',
        'no_iniciado',
        'aprobado',
        'en_proceso',
    ];

    private Estudiante_EspacioCurricular_model $model;

    public function __construct()
    {
        $this->model = new Estudiante_EspacioCurricular_model();
    }

    public function getAll()
    {
        $data['estudiantesEspaciosCurriculares'] = $this->model->obtenerEstEspCurr();
        return $data;
    }

    public function setStatus(int $idEstudiante, int $idEspCurr, string $status): array
    {
        if (!in_array($status, self::ESTADOS_VALIDOS, true)) {
            return [
                'success' => false,
                'message' => 'El estado indicado no es válido.',
                'data' => null,
                'statusCode' => 400,
            ];
        }

        $estadoModel = new Estado_EspacioCurricular_model();
        $estado = $estadoModel->encontrarEstado([
            'estado' => $status,
            'limit' => 1,
        ]);

        if (!$estado || !empty($estado->deletedBy) || !empty($estado->deletedAt)) {
            return [
                'success' => false,
                'message' => 'El estado indicado no está disponible.',
                'data' => null,
                'statusCode' => 404,
            ];
        }

        $data = [
            'idEstudiante' => $idEstudiante,
            'idEspCurr' => $idEspCurr,
            'idEstadoEspCurr' => (int) $estado->id,
        ];

        $relacion = $this->model->encontrarEstEspCurr([
            'idEstudiante' => $idEstudiante,
            'idEspCurr' => $idEspCurr,
            'limit' => 1,
        ]);

        $relacionActiva = $relacion
            && empty($relacion->deletedBy)
            && empty($relacion->deletedAt);

        return $relacionActiva
            ? $this->update($idEstudiante, $idEspCurr, $data)
            : $this->create($data);
    }

    public function create(array $data)
    {
        $estudianteModel = new Estudiante_model();
        $existeEstudiante = $estudianteModel->find($data['idEstudiante']);
        if (!$existeEstudiante) {
            return [
                'success' => false,
                'message' => 'Estudiante no encontrado.',
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

        $estadoEspCurrModel = new Estado_EspacioCurricular_model();
        $existeEstadoEspCurr = $estadoEspCurrModel->find($data['idEstadoEspCurr']);
        if (!$existeEstadoEspCurr) {
            return [
                'success' => false,
                'message' => 'Estado del Espacio Curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $params = [
            'idEstudiante' => (int) ($data['idEstudiante'] ?? 0),
            'idEspCurr' => (int) ($data['idEspCurr'] ?? 0),
            'limit' => 1
        ];

        $existeEstEspCurr = $this->model->encontrarEstEspCurr($params);
        if ($existeEstEspCurr) {
            $estaDadaDeBaja = !empty($existeEstEspCurr->deletedBy) || !empty($existeEstEspCurr->deletedAt);

            if ($estaDadaDeBaja) {
                $reactivated = $this->model->reactivarEstEspCurr(
                    $params['idEstudiante'],
                    $params['idEspCurr'],
                    (int) ($data['idEstadoEspCurr'] ?? 0)
                );

                return [
                    'success' => $reactivated,
                    'message' => $reactivated ? 'Relación de Estudiante y Espacio Curricular reactivada correctamente.' : 'Error al reactivar la relación Estudiante - Espacio curricular.',
                    'data' => $reactivated ? $this->model->obtenerEstEspCurr($params)[0] : null
                ];
            }

            return [
                'success' => false,
                'message' => 'Ya existe una relación entre el Estudiante y el espacio curricular seleccionados.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $inserted = $this->model->nuevoEstEspCurr($data) !== false;

        $dataEstEspCurr = $inserted
            ? $this->model->obtenerEstEspCurr($params)[0]
            : null;

        return [
            'success' => $inserted,
            'message' => $inserted ? 'Relación de Estudiante y Espacio Curricular creada correctamente.' : 'Error al crear la relación Estudiante - Espacio curricular.',
            'data' => $dataEstEspCurr ?: null
        ];
    }

    public function update(int $idEstudianteActual, int $idEspCurrActual, array $data)
    {
        $paramsActuales = [
            'idEstudiante' => $idEstudianteActual,
            'idEspCurr' => $idEspCurrActual,
            'limit' => 1
        ];

        $estudianteEspacioCurricularExistente = $this->model->encontrarEstEspCurr($paramsActuales);

        if (!$estudianteEspacioCurricularExistente) {
            return [
                'success' => false,
                'message' => 'Estudiante - Espacio curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $nuevosDatos = [
            'idEstudiante' => (int) ($data['idEstudiante'] ?? $idEstudianteActual),
            'idEspCurr' => (int) ($data['idEspCurr'] ?? $idEspCurrActual),
            'idEstadoEspCurr' => (int) ($data['idEstadoEspCurr'] ?? 0)
        ];

        $estudianteModel = new Estudiante_model();
        $existeEstudiante = $estudianteModel->find($data['idEstudiante']);
        if (!$existeEstudiante) {
            return [
                'success' => false,
                'message' => 'Estudiante no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $espacioCurricularModel = new EspacioCurricular_model();
        $existeEspacioCurricular = $espacioCurricularModel->find($nuevosDatos['idEspCurr']);
        if (!$existeEspacioCurricular) {
            return [
                'success' => false,
                'message' => 'Espacio Curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $estadoEspCurrModel = new Estado_EspacioCurricular_model();
        $existeEstadoEspCurr = $estadoEspCurrModel->find($nuevosDatos['idEstadoEspCurr']);
        if (!$existeEstadoEspCurr) {
            return [
                'success' => false,
                'message' => 'Estado del Espacio Curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $cambioRelacion = $nuevosDatos['idEstudiante'] !== $idEstudianteActual || $nuevosDatos['idEspCurr'] !== $idEspCurrActual;

        if ($cambioRelacion) {
            $paramsBusqueda = [
                'idEstudiante' => $nuevosDatos['idEstudiante'],
                'idEspCurr' => $nuevosDatos['idEspCurr'],
                'limit' => 1
            ];

            $relacionDestino = $this->model->encontrarEstEspCurr($paramsBusqueda);

            if ($relacionDestino) {
                $estaDadaDeBaja = !empty($relacionDestino->deletedBy) || !empty($relacionDestino->deletedAt);

                if ($estaDadaDeBaja) {
                    $this->model->eliminarEstEspCurr($idEstudianteActual, $idEspCurrActual);

                    $reactivated = $this->model->reactivarEstEspCurr($nuevosDatos['idEstudiante'], $nuevosDatos['idEspCurr'], (int) $data['idEstadoEspCurr']);

                    return [
                        'success' => $reactivated,
                        'message' => $reactivated
                            ? 'Relación de Estudiante y Espacio Curricular actualizada correctamente.'
                            : 'Error al actualizar la relación Estudiante - Espacio curricular.',
                        'data' => $reactivated
                            ? ($this->model->obtenerEstEspCurr($paramsBusqueda)[0] ?? null)
                            : null
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Ya existe una relación entre el estudiante y el espacio curricular seleccionados.',
                    'data' => null,
                    'statusCode' => 409
                ];
            }
        }

        $updated = $this->model->actualizarEstEspCurr($idEstudianteActual, $idEspCurrActual, $nuevosDatos);

        $paramsBusqueda = [
            ...$nuevosDatos,
            'limit' => 1
        ];

        $resultado = $updated
            ? ($this->model->obtenerEstEspCurr($paramsBusqueda)[0] ?? null)
            : null;

        return [
            'success' => $updated,
            'message' => $updated
                ? 'Relación de Estudiante y Espacio Curricular actualizada correctamente.'
                : 'Error al actualizar la relación Estudiante - Espacio curricular.',
            'data' => $resultado
        ];
    }

    public function delete(int $idEstudiante, int $idEspCurr)
    {
        $estEspCurrExistente = $this->model->encontrarEstEspCurr([
            'idEstudiante' => $idEstudiante,
            'idEspCurr' => $idEspCurr,
        ]);

        if (!$estEspCurrExistente) {
            return [
                'success' => false,
                'message' => 'Relación Estudiante - Espacio curricular no encontrada.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $this->model->eliminarEstEspCurr($idEstudiante, $idEspCurr);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Relación eliminada exitosamente.' : 'Error al eliminar la relación.',
            'data' => null
        ];
    }
}
