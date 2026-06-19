<?php

namespace App\Controllers;

use App\Libraries\LibraryEstadoEspacioCurricular;

class EstadoEspacioCurricular extends BaseController
{
    public function index()
    {
        $library = new LibraryEstadoEspacioCurricular();
        $data = $library->getAll();

        return view('estados_espacios_curriculares/listado', $data);
    }

    public function create()
    {
        $json = $this->request->getJSON(true);
        $estado = trim($json['estado'] ?? '');

        $dataEstado = [
            'estado' => $estado
        ];

        $errorValidacion = $this->validarDatosEstado($dataEstado);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryEstadoEspacioCurricular();
        $result = $library->create($dataEstado);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 201 : 500))
            ->setJSON($result);
    }

    public function update(int $id)
    {
        $json = $this->request->getJSON(true);
        $estado = trim($json['estado'] ?? '');

        $dataEstado = [
            'estado' => $estado
        ];

        $errorValidacion = $this->validarDatosEstado($dataEstado);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryEstadoEspacioCurricular();
        $result = $library->update($id, $dataEstado);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function delete(int $id)
    {
        $library = new LibraryEstadoEspacioCurricular();
        $result = $library->delete($id);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function list()
    {
        $library = new LibraryEstadoEspacioCurricular();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Estados obtenidos correctamente.',
                'data' => $library->getAll()['estados']
            ]);
    }

    private function validarDatosEstado(array $data): ?array
    {
        if ($data['estado'] === '') {
            return [
                'message' => 'El estado es obligatorio.'
            ];
        }

        return null;
    }
}
