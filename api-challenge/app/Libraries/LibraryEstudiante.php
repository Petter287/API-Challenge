<?php

namespace App\Libraries;
use App\Models\Estudiante_model;
use App\Entities\EstudianteEntity;
use DateTime;

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

        $model = new Estudiante_model();
        $idEstudiante = $model->insert($estudiante, true);

        $dataEstudiante = $idEstudiante
                          ? $model ->find($idEstudiante)
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

        $updated = $model->update($id, [
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido']
        ]);

        return [
            'success' => $updated,
            'message' => $updated ? 'Estudiante actualizado exitosamente.' : 'Error al actualizar el estudiante.',
            'data' => $updated ? $model->find($id) : null
        ];
    }
}