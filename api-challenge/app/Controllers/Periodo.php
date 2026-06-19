<?php

namespace App\Controllers;

use App\Libraries\LibraryPeriodo;

class Periodo extends BaseController
{
    public function index()
    {
        $library = new LibraryPeriodo();
        $data = $library->getAll();

        return view('periodos/listado', $data);
    }

    public function create()
    {
        $json = $this->request->getJSON(true);

        $nombre = trim($json['nombre'] ?? '');

        $dataPeriodo = [
            'nombre' => $nombre
        ];

        $errorValidacion = $this->validarDatosPeriodo($dataPeriodo);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryPeriodo();

        $result = $library->create($dataPeriodo);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 201 : 500))
            ->setJSON($result);
    }

    public function update(int $id)
    {
        $json = $this->request->getJSON(true);

        $nombre = trim($json['nombre'] ?? '');

        $dataPeriodo = [
            'nombre' => $nombre
        ];

        $errorValidacion = $this->validarDatosPeriodo($dataPeriodo);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryPeriodo();
        $result = $library->update($id, $dataPeriodo);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function delete(int $id)
    {
        $library = new LibraryPeriodo();
        $result = $library->delete($id);
        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function list()
    {
        $library = new LibraryPeriodo();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Periodos obtenidos correctamente.',
                'data' => $library->getAll()['periodos']
            ]);
    }

    private function validarDatosPeriodo(array $data): ?array
    {
        if ($data['nombre'] === '') {
            return [
                'message' => 'El nombre es obligatorio.'
            ];
        }

        return null;
    }
}
