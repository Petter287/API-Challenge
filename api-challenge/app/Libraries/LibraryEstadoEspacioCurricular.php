<?php

namespace App\Libraries;

use App\Entities\EstadoEspacioCurricularEntity;
use App\Models\Estado_EspacioCurricular_model;

class LibraryEstadoEspacioCurricular
{
    public function getAll()
    {
        $model = new Estado_EspacioCurricular_model();
        $data['estados'] = $model->findAll();
        return $data;
    }

    public function create(array $data)
    {
        $estadoEspacioCurricular = new EstadoEspacioCurricularEntity();
        $estadoEspacioCurricular->estado = $data['estado'] ?? null;

        $model = new Estado_EspacioCurricular_model();

        $existeEstado = $model
            ->where('estado', $data['estado'])
            ->first();

        if ($existeEstado) {
            return [
                'success' => false,
                'message' => 'Ya existe un estado con ese nombre.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idEstado = $model->insert($estadoEspacioCurricular, true);

        $dataEstado = $idEstado
            ? $model->find($idEstado)
            : null;

        return [
            'success' => $idEstado ? true : false,
            'message' => $idEstado ? 'Estado creado exitosamente.' : 'Error al crear el estado.',
            'data' => $dataEstado ?: null
        ];
    }

    public function update(int $id, array $data)
    {
        $model = new Estado_EspacioCurricular_model();
        $estadoExistente = $model->find($id);

        if (!$estadoExistente) {
            return [
                'success' => false,
                'message' => 'Estado no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $existeEstado = $model
            ->where('estado', $data['estado'])
            ->where('id !=', $id)
            ->first();

        if ($existeEstado) {
            return [
                'success' => false,
                'message' => 'Ya existe otro estado con ese nombre.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $model->update($id, [
            'estado' => $data['estado']
        ]);

        return [
            'success' => $updated,
            'message' => $updated ? 'Estado actualizado exitosamente.' : 'Error al actualizar el estado.',
            'data' => $updated ? $model->find($id) : null
        ];
    }

    public function delete(int $id)
    {
        $model = new Estado_EspacioCurricular_model();
        $estadoExistente = $model->find($id);

        if (!$estadoExistente) {
            return [
                'success' => false,
                'message' => 'Estado no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $model->delete($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Estado eliminado exitosamente.' : 'Error al eliminar el estado.',
            'data' => null
        ];
    }
}
