<?php

namespace App\Libraries;

use App\Models\Materia_model;

class LibraryMateria
{
    private Materia_model $model;

    public function __construct()
    {
        $this->model = new Materia_model();
    }

    public function getAll()
    {
        $data['materias'] = $this->model->findAll();
        return $data;
    }

    public function create(array $data)
    {
        $params = [
            'nombre' => $data['nombre'] ?? null,
            'anio' => (int) ($data['anio'] ?? 0),
            'limit' => 1
        ];

        $existeMateria = $this->model->encontrarMateria($params);
        if ($existeMateria) {
            return [
                'success' => false,
                'message' => 'Ya existe una materia con ese nombre y año.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idMateria = $this->model->nuevaMateria($params);

        $dataMateria = $idMateria
            ? $this->model->find($idMateria)
            : null;

        return [
            'success' => $idMateria ? true : false,
            'message' => $idMateria ? 'Materia creada exitosamente.' : 'Error al crear la materia.',
            'data' => $dataMateria ?: null
        ];
    }

    public function update(int $id, array $data)
    {
        $materiaExistente = $this->model->find($id);
        if (!$materiaExistente) {
            return [
                'success' => false,
                'message' => 'Materia no encontrada.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $params = [
            'nombre' => $data['nombre'] ?? null,
            'anio' => (int) ($data['anio'] ?? 0),
            'limit' => 1
        ];

        $existeMateria = $this->model->encontrarMateria($params);
        if ($existeMateria && (int) $existeMateria->id !== $id) {
            return [
                'success' => false,
                'message' => 'Ya existe una materia con ese nombre y año.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $this->model->actualizarMateria($id, $params);

        return [
            'success' => $updated,
            'message' => $updated ? 'Materia actualizada exitosamente.' : 'Error al actualizar la materia.',
            'data' => $updated ? $this->model->find($id) : null
        ];
    }

    public function delete(int $id)
    {
        $materiaExistente = $this->model->find($id);
        if (!$materiaExistente) {
            return [
                'success' => false,
                'message' => 'Materia no encontrada.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $this->model->deleteWithUser($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Materia eliminada exitosamente.' : 'Error al eliminar la materia.',
            'data' => null
        ];
    }
}
