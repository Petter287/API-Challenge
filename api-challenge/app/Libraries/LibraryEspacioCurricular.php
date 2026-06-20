<?php

namespace App\Libraries;

use App\Models\EspacioCurricular_model;
use App\Models\Periodo_model;

class LibraryEspacioCurricular
{
    private EspacioCurricular_model $model;

    public function __construct()
    {
        $this->model = new EspacioCurricular_model();
    }

    public function getAll()
    {
        $data['espaciosCurriculares'] = $this->model->obtenerEspaciosCurriculares();
        return $data;
    }

    public function create(array $data)
    {
        $periodoModel = new Periodo_model();
        $existePeriodo = $periodoModel->find($data['idPeriodo']);
        if (!$existePeriodo) {
            return [
                'success' => false,
                'message' => 'Periodo no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $params=[
            'nombre' => $data['nombre'] ?? null,
            'idPeriodo' => (int) ($data['idPeriodo'] ?? 0),
            'limit' => 1
        ];

        $existeEspacioCurricular = $this->model->encontrarEspacioCurricular($params);
        if ($existeEspacioCurricular) {
            return [
                'success' => false,
                'message' => 'Ya existe un espacio curricular con ese nombre y período.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idEspacioCurricular = $this->model->nuevoEspacioCurricular($params);

        $dataEspacioCurricular = $idEspacioCurricular
            ? $this->model->obtenerEspaciosCurriculares(['id' => $idEspacioCurricular]) [0]
            : null;

        return [
            'success' => $idEspacioCurricular ? true : false,
            'message' => $idEspacioCurricular ? 'Espacio curricular creado exitosamente.' : 'Error al crear el espacio curricular.',
            'data' => $dataEspacioCurricular ?: null
        ];
    }

    public function update(int $id, array $data)
    {
        $espacioCurricularExistente = $this->model->find($id);
        if (!$espacioCurricularExistente) {
            return [
                'success' => false,
                'message' => 'Espacio curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $periodoModel = new Periodo_model();
        $existePeriodo = $periodoModel->find($data['idPeriodo']);
        if (!$existePeriodo) {
            return [
                'success' => false,
                'message' => 'Periodo no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $params=[
            'nombre' => $data['nombre'] ?? null,
            'idPeriodo' => (int) ($data['idPeriodo'] ?? 0),
            'limit' => 1
        ];

        $existeEspacioCurricular = $this->model->encontrarEspacioCurricular($params);
        if ($existeEspacioCurricular) {
            return [
                'success' => false,
                'message' => 'Ya existe un espacio curricular con ese nombre y período.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $this->model->actualizarEspacioCurricular($id, $params);

        $dataEspacioCurricular = $updated
            ? $this->model->obtenerEspaciosCurriculares(['id' => $id]) [0]
            : null;

        return [
            'success' => $updated,
            'message' => $updated ? 'Espacio curricular actualizado exitosamente.' : 'Error al actualizar el espacio curricular.',
            'data' => $updated ? $dataEspacioCurricular : null
        ];
    }

    public function delete(int $id)
    {
        $espacioCurricularExistente = $this->model->find($id);
        if (!$espacioCurricularExistente) {
            return [
                'success' => false,
                'message' => 'Espacio curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $this->model->delete($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Espacio curricular eliminado exitosamente.' : 'Error al eliminar el espacio curricular.',
            'data' => null
        ];
    }
}
