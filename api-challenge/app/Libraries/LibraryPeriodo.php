<?php

namespace App\Libraries;

use App\Models\Periodo_model;

class LibraryPeriodo
{
    private Periodo_model $model;

    public function __construct()
    {
        $this->model = new Periodo_model();
    }

    public function getAll()
    {
        $data['periodos'] = $this->model->findAll();
        return $data;
    }

    public function create(array $data)
    {
        $params = [
            'nombre' => $data['nombre'] ?? null,
            'limit' => 1
        ];

        $existePeriodo = $this->model->encontrarPeriodo($params);
        if ($existePeriodo) {
            return [
                'success' => false,
                'message' => 'Ya existe un período con ese nombre.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idPeriodo = $this->model->nuevoPeriodo($params);

        $dataPeriodo = $idPeriodo
            ? $this->model->find($idPeriodo)
            : null;

        return [
            'success' => $idPeriodo ? true : false,
            'message' => $idPeriodo ? 'Periodo creado exitosamente.' : 'Error al crear el periodo.',
            'data' => $dataPeriodo ?: null
        ];
    }

    public function update(int $id, array $data)
    {
        $periodoExistente = $this->model->find($id);
        if (!$periodoExistente) {
            return [
                'success' => false,
                'message' => 'Periodo no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $params = [
            'nombre' => $data['nombre'] ?? null,
            'limit' => 1
        ];

        $existePeriodo = $this->model->encontrarPeriodo($params);
        if ($existePeriodo && (int) $existePeriodo->id !== $id) {
            return [
                'success' => false,
                'message' => 'Ya existe otro periodo con ese nombre.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $this->model->actualizarPeriodo($id, $params);

        return [
            'success' => $updated,
            'message' => $updated ? 'Periodo actualizado exitosamente.' : 'Error al actualizar el periodo.',
            'data' => $updated ? $this->model->find($id) : null
        ];
    }

    public function delete(int $id)
    {
        $periodoExistente = $this->model->find($id);
        if (!$periodoExistente) {
            return [
                'success' => false,
                'message' => 'Periodo no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $this->model->deleteWithUser($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Periodo eliminado exitosamente.' : 'Error al eliminar el periodo.',
            'data' => null
        ];
    }
}
