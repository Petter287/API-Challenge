<?php

namespace App\Libraries;

use App\Models\Estudiante_model;
use App\Entities\EstudianteEntity;

class LibraryEstudiante
{
    public function getAll()
    {
        $model = new Estudiante_model();
        $data['estudiantes'] = $model->findAll();
        return $data;
    }

    public function create(array $data)
    {
        $estudiante = new EstudianteEntity();
        $estudiante->nombre = $data['nombre'] ?? null;
        $estudiante->apellido = $data['apellido'] ?? null;
        $estudiante->dni = $data['dni'] ?? null;
        $estudiante->fechaNacimiento = $data['fechaNacimiento'] ?? null;

        $model = new Estudiante_model();

        $existeDni = $model
            ->where('dni', $data['dni'])
            ->first();

        if ($existeDni) {
            return [
                'success' => false,
                'message' => 'Ya existe un estudiante con ese DNI.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idEstudiante = $model->insert($estudiante, true);

        $dataEstudiante = $idEstudiante
            ? $model->find($idEstudiante)
            : null;

        $structReturn = [
            'success' => $idEstudiante ? true : false,
            'message' => $idEstudiante ? 'Estudiante creado exitosamente.' : 'Error al crear el estudiante.',
            'data' => $dataEstudiante ?: null
        ];

        return $structReturn;
    }

    public function update(int $id, array $data)
    {
        $model = new Estudiante_model();
        $estudianteExistente = $model->find($id);

        if (!$estudianteExistente) {
            return [
                'success' => false,
                'message' => 'Estudiante no encontrado.',
                'data' => null
            ];
        }

        $existeDni = $model
            ->where('dni', $data['dni'])
            ->where('id !=', $id)
            ->first();

        if ($existeDni) {
            return [
                'success' => false,
                'message' => 'Ya existe otro estudiante con ese DNI.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $model->update($id, [
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'dni' => $data['dni'],
            'fechaNacimiento' => $data['fechaNacimiento']
        ]);

        return [
            'success' => $updated,
            'message' => $updated ? 'Estudiante actualizado exitosamente.' : 'Error al actualizar el estudiante.',
            'data' => $updated ? $model->find($id) : null
        ];
    }

    public function delete(int $id)
    {
        $model = new Estudiante_model();
        $estudianteExistente = $model->find($id);

        if (!$estudianteExistente) {
            return [
                'success' => false,
                'message' => 'Estudiante no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $model->delete($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Estudiante eliminado exitosamente.' : 'Error al eliminar el estudiante.',
            'data' => null
        ];
    }
}
