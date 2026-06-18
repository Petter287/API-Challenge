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
}