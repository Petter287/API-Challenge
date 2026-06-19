<?php

namespace App\Libraries;

use App\Entities\MateriaEntity;
use App\Models\Materia_model;

class LibraryMateria
{
    public function getAll()
    {
        $model = new Materia_model();
        $data['materias'] = $model->findAll();
        return $data;
    }

    public function create(array $data)
    {
        $materia = new MateriaEntity();
        $materia->nombre = $data['nombre'] ?? null;
        $materia->anio = (int) ($data['anio'] ?? 0);

        $model = new Materia_model();

        $existeMateria = $model
            ->where('nombre', $data['nombre'])
            ->where('anio', $data['anio'])
            ->first();

        if ($existeMateria) {
            return [
                'success' => false,
                'message' => 'Ya existe una materia con ese nombre y año.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idMateria = $model->insert($materia, true);

        $dataMateria = $idMateria
            ? $model->find($idMateria)
            : null;

        return [
            'success' => $idMateria ? true : false,
            'message' => $idMateria ? 'Materia creada exitosamente.' : 'Error al crear la materia.',
            'data' => $dataMateria ?: null
        ];
    }

    public function update(int $id, array $data)
    {
        $model = new Materia_model();
        $materiaExistente = $model->find($id);

        if (!$materiaExistente) {
            return [
                'success' => false,
                'message' => 'Materia no encontrada.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $existeMateria = $model
            ->where('nombre', $data['nombre'])
            ->where('anio', $data['anio'])
            ->where('id !=', $id)
            ->first();

        if ($existeMateria) {
            return [
                'success' => false,
                'message' => 'Ya existe otra materia con ese nombre y año.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $model->update($id, [
            'nombre' => $data['nombre'],
            'anio' => $data['anio']
        ]);

        return [
            'success' => $updated,
            'message' => $updated ? 'Materia actualizada exitosamente.' : 'Error al actualizar la materia.',
            'data' => $updated ? $model->find($id) : null
        ];
    }

    public function delete(int $id)
    {
        $model = new Materia_model();
        $materiaExistente = $model->find($id);

        if (!$materiaExistente) {
            return [
                'success' => false,
                'message' => 'Materia no encontrada.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $model->delete($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Materia eliminada exitosamente.' : 'Error al eliminar la materia.',
            'data' => null
        ];
    }
}
