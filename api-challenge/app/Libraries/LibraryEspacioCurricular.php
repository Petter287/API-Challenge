<?php

namespace App\Libraries;

use App\Entities\EspacioCurricularEntity;
use App\Models\EspacioCurricular_model;
use App\Models\Periodo_model;

class LibraryEspacioCurricular
{
    public function getAll()
    {
        $model = new EspacioCurricular_model();

        $data['espaciosCurriculares'] = $model
            ->select('Espacio_Curricular.*, Periodo.nombre AS periodoNombre')
            ->join('Periodo', 'Periodo.id = Espacio_Curricular.idPeriodo')
            ->findAll();

        return $data;
    }

    public function create(array $data)
    {
        $validacionPeriodo = $this->validarPeriodo($data['idPeriodo']);

        if ($validacionPeriodo) {
            return $validacionPeriodo;
        }

        $espacioCurricular = new EspacioCurricularEntity();
        $espacioCurricular->nombre = $data['nombre'] ?? null;
        $espacioCurricular->idPeriodo = (int) ($data['idPeriodo'] ?? 0);

        $model = new EspacioCurricular_model();

        $existeEspacioCurricular = $model
            ->where('nombre', $data['nombre'])
            ->where('idPeriodo', $data['idPeriodo'])
            ->first();

        if ($existeEspacioCurricular) {
            return [
                'success' => false,
                'message' => 'Ya existe un espacio curricular con ese nombre y período.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $idEspacioCurricular = $model->insert($espacioCurricular, true);

        $dataEspacioCurricular = $idEspacioCurricular
            ? $this->findWithPeriodo($idEspacioCurricular)
            : null;

        return [
            'success' => $idEspacioCurricular ? true : false,
            'message' => $idEspacioCurricular ? 'Espacio curricular creado exitosamente.' : 'Error al crear el espacio curricular.',
            'data' => $dataEspacioCurricular ?: null
        ];
    }

    public function update(int $id, array $data)
    {
        $model = new EspacioCurricular_model();
        $espacioCurricularExistente = $model->find($id);

        if (!$espacioCurricularExistente) {
            return [
                'success' => false,
                'message' => 'Espacio curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $validacionPeriodo = $this->validarPeriodo($data['idPeriodo']);

        if ($validacionPeriodo) {
            return $validacionPeriodo;
        }

        $existeEspacioCurricular = $model
            ->where('nombre', $data['nombre'])
            ->where('idPeriodo', $data['idPeriodo'])
            ->where('id !=', $id)
            ->first();

        if ($existeEspacioCurricular) {
            return [
                'success' => false,
                'message' => 'Ya existe otro espacio curricular con ese nombre y período.',
                'data' => null,
                'statusCode' => 409
            ];
        }

        $updated = $model->update($id, [
            'nombre' => $data['nombre'],
            'idPeriodo' => $data['idPeriodo']
        ]);

        return [
            'success' => $updated,
            'message' => $updated ? 'Espacio curricular actualizado exitosamente.' : 'Error al actualizar el espacio curricular.',
            'data' => $updated ? $this->findWithPeriodo($id) : null
        ];
    }

    public function delete(int $id)
    {
        $model = new EspacioCurricular_model();
        $espacioCurricularExistente = $model->find($id);

        if (!$espacioCurricularExistente) {
            return [
                'success' => false,
                'message' => 'Espacio curricular no encontrado.',
                'data' => null,
                'statusCode' => 404
            ];
        }

        $deleted = $model->delete($id);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Espacio curricular eliminado exitosamente.' : 'Error al eliminar el espacio curricular.',
            'data' => null
        ];
    }

    private function findWithPeriodo(int $id)
    {
        $model = new EspacioCurricular_model();

        return $model
            ->select('Espacio_Curricular.*, Periodo.nombre AS periodoNombre')
            ->join('Periodo', 'Periodo.id = Espacio_Curricular.idPeriodo')
            ->where('Espacio_Curricular.id', $id)
            ->first();
    }

    private function validarPeriodo(int $idPeriodo): ?array
    {
        $periodoModel = new Periodo_model();
        $periodo = $periodoModel->find($idPeriodo);

        if (!$periodo) {
            return [
                'success' => false,
                'message' => 'El período seleccionado no existe.',
                'data' => null,
                'statusCode' => 400
            ];
        }

        return null;
    }
}
