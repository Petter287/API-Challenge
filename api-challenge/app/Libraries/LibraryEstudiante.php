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
        $estudiante->createdBy = $data['createdBy'] ?? null;
        $estudiante->createdAt = new DateTime();

        $model = new Estudiante_model();
        $idEstudiante = $model->insert($estudiante, true);

        $structReturn = [
            'success' => $idEstudiante ? true : false,
            'message' => $idEstudiante ? 'Estudiante creado exitosamente.' : 'Error al crear el estudiante.',
            'data' => $estudiante ?: null
        ];

        return $structReturn;
    }
}