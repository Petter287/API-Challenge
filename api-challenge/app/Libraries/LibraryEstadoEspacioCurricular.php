<?php

namespace App\Libraries;

use App\Models\Estado_EspacioCurricular_model;

class LibraryEstadoEspacioCurricular
{
    private Estado_EspacioCurricular_model $model;

    public function __construct()
    {
        $this->model = new Estado_EspacioCurricular_model();
    }

    public function getAll()
    {
        $data['estados'] = $this->model->findAll();
        return $data;
    }

    public function create(array $data)
    {
        $params = [
            'estado' => $data['estado'] ?? null,
            'limit' => 1
        ];

        $existeEstado = $this->model->encontrarEstado($params);
        if ($existeEstado) {
            return [
                'success' => false,
                'message' => 'Ya existe un estado con ese nombre.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idEstado = $this->model->nuevoEstado($params);

        $dataEstado = $idEstado
            ? $this->model->find($idEstado)
            : null;

        return [
            'success' => $idEstado ? true : false,
            'message' => $idEstado ? 'Estado creado exitosamente.' : 'Error al crear el estado.',
            'data' => $dataEstado ?: null
        ];
    }

    public function update(int $id, array $data)
    {
        $estadoExistente = $this->model->find($id);
        if (!$estadoExistente) {
            return [
                'success' => false,
                'message' => 'Estado no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $params = [
            'estado' => $data['estado'] ?? null,
            'limit' => 1
        ];

        $existeEstado = $this->model->encontrarEstado($params);
        if ($existeEstado && (int) $existeEstado->id !== $id) {
            return [
                'success' => false,
                'message' => 'Ya existe otro estado con ese nombre.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $this->model->actualizarEstado($id, $data);

        return [
            'success' => $updated,
            'message' => $updated ? 'Estado actualizado exitosamente.' : 'Error al actualizar el estado.',
            'data' => $updated ? $this->model->find($id) : null
        ];
    }

    public function delete(int $id)
    {
        $estadoExistente = $this->model->find($id);
        if (!$estadoExistente) {
            return [
                'success' => false,
                'message' => 'Estado no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $this->model->deleteWithUser($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Estado eliminado exitosamente.' : 'Error al eliminar el estado.',
            'data' => null
        ];
    }
}
