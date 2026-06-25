<?php

namespace App\Libraries;

use App\Models\Estudiante_model;
use App\Models\Estudiante_EspacioCurricular_model;

class LibraryEstudiante
{
    private Estudiante_model $model;
    
    public function __construct()
    {
        $this->model = new Estudiante_model();
    }

    public function getAll()
    {
        $data['estudiantes'] = $this->model->findAll();
        return $data;
    }

    public function getSubjectStatuses(int $idEstudiante): array
    {
        $estudiante = $this->model->find($idEstudiante);

        if (!$estudiante) {
            return [
                'success' => false,
                'message' => 'Estudiante no encontrado.',
                'data' => null,
                'statusCode' => 404,
            ];
        }

        $relacionModel = new Estudiante_EspacioCurricular_model();
        $calculator = new EstadoFinalMateriaCalculator();
        $filas = $relacionModel->obtenerEstadosMateriaPorEstudiante($idEstudiante);

        return [
            'success' => true,
            'message' => 'Estados finales obtenidos correctamente.',
            'data' => $calculator->agruparPorMateria($filas),
        ];
    }

    public function create(array $data)
    {
        $dataEstudiante = [
            'nombre' => $data['nombre'] ?? null,
            'apellido' => $data['apellido'] ?? null,
            'dni' => $data['dni'] ?? null,
            'fechaNacimiento' => $data['fechaNacimiento'] ?? null,
        ];

        $params = [
            'dni' => $dataEstudiante['dni'],
            'limit' => 1
        ];

        $existeDni = $this->model->encontrarEstudiante($params);
        if ($existeDni) {
            return [
                'success' => false,
                'message' => 'Ya existe un estudiante con ese DNI.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idEstudiante = $this->model->nuevoEstudiante($dataEstudiante);

        $dataEstudiante = $idEstudiante
            ? $this->model->find($idEstudiante)
            : null;

        return [
            'success' => $idEstudiante ? true : false,
            'message' => $idEstudiante ? 'Estudiante creado exitosamente.' : 'Error al crear el estudiante.',
            'data' => $dataEstudiante ?: null
        ];
    }

    public function update(int $id, array $data)
    {
        $estudianteExistente = $this->model->find($id);
        if (!$estudianteExistente) {
            return [
                'success' => false,
                'message' => 'Estudiante no encontrado.',
                'data' => null
            ];
        }

        $dataEstudiante = [
            'nombre' => $data['nombre'] ?? null,
            'apellido' => $data['apellido'] ?? null,
            'dni' => $data['dni'] ?? null,
            'fechaNacimiento' => $data['fechaNacimiento'] ?? null,
        ];

        $params = [
            'dni' => $dataEstudiante['dni'],
            'limit' => 1
        ];

        $existeDni = $this->model->encontrarEstudiante($params);
        if ($existeDni && $existeDni->id != $id) {
            return [
                'success' => false,
                'message' => 'Ya existe un estudiante con ese DNI.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $this->model->actualizarEstudiante($id, $dataEstudiante);

        return [
            'success' => $updated,
            'message' => $updated ? 'Estudiante actualizado exitosamente.' : 'Error al actualizar el estudiante.',
            'data' => $updated ? $this->model->find($id) : null
        ];
    }

    public function delete(int $id)
    {
        $estudianteExistente = $this->model->find($id);
        if (!$estudianteExistente) {
            return [
                'success' => false,
                'message' => 'Estudiante no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $this->model->deleteWithUser($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Estudiante eliminado exitosamente.' : 'Error al eliminar el estudiante.',
            'data' => null
        ];
    }
}
