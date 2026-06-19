<?php

namespace App\Libraries;

use App\Models\Periodo_model;
use App\Entities\PeriodoEntity;

class LibraryPeriodo
{
    public function getAll()
    {
        $model = new Periodo_model();
        $data['periodos'] = $model->findAll();
        return $data;
    }

    public function create(array $data)
    {
        $periodo = new PeriodoEntity();
        $periodo->nombre = $data['nombre'] ?? null;

        $model = new Periodo_model();

        $existePeriodo = $model
            ->where('nombre', $data['nombre'])
            ->first();

        if ($existePeriodo) {
            return [
                'success' => false,
                'message' => 'Ya existe un período con ese nombre.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idPeriodo = $model->insert($periodo, true);

        $dataPeriodo = $idPeriodo
            ? $model->find($idPeriodo)
            : null;

        $structReturn = [
            'success' => $idPeriodo ? true : false,
            'message' => $idPeriodo ? 'Periodo creado exitosamente.' : 'Error al crear el periodo.',
            'data' => $dataPeriodo ?: null
        ];

        return $structReturn;
    }

    public function update(int $id, array $data)
    {
        $model = new Periodo_model();
        $periodoExistente = $model->find($id);

        if (!$periodoExistente) {
            return [
                'success' => false,
                'message' => 'Periodo no encontrado.',
                'data' => null
            ];
        }

        $existePeriodo = $model
            ->where('nombre', $data['nombre'])
            ->where('id !=', $id)
            ->first();

        if ($existePeriodo) {
            return [
                'success' => false,
                'message' => 'Ya existe otro periodo con ese nombre.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $model->update($id, [
            'nombre' => $data['nombre']
        ]);

        return [
            'success' => $updated,
            'message' => $updated ? 'Periodo actualizado exitosamente.' : 'Error al actualizar el periodo.',
            'data' => $updated ? $model->find($id) : null
        ];
    }

    public function delete(int $id)
    {
        $model = new Periodo_model();
        $periodoExistente = $model->find($id);

        if (!$periodoExistente) {
            return [
                'success' => false,
                'message' => 'Periodo no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $model->delete($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Periodo eliminado exitosamente.' : 'Error al eliminar el periodo.',
            'data' => null
        ];
    }
}
