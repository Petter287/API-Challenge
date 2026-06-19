<?php

namespace App\Controllers;

use App\Libraries\LibraryMateria;

class Materia extends BaseController
{
    public function index()
    {
        $library = new LibraryMateria();
        $data = $library->getAll();

        return view('materias/listado', $data);
    }

    public function create()
    {
        $json = $this->request->getJSON(true);
        $nombre = trim($json['nombre'] ?? '');
        $anio = trim((string) ($json['anio'] ?? ''));

        $dataMateria = [
            'nombre' => $nombre,
            'anio' => $anio
        ];

        $errorValidacion = $this->validarDatosMateria($dataMateria);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryMateria();
        $result = $library->create($dataMateria);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 201 : 500))
            ->setJSON($result);
    }

    public function update(int $id)
    {
        $json = $this->request->getJSON(true);
        $nombre = trim($json['nombre'] ?? '');
        $anio = trim((string) ($json['anio'] ?? ''));

        $dataMateria = [
            'nombre' => $nombre,
            'anio' => $anio
        ];

        $errorValidacion = $this->validarDatosMateria($dataMateria);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryMateria();
        $result = $library->update($id, $dataMateria);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function delete(int $id)
    {
        $library = new LibraryMateria();
        $result = $library->delete($id);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function list()
    {
        $library = new LibraryMateria();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Materias obtenidas correctamente.',
                'data' => $library->getAll()['materias']
            ]);
    }

    private function validarDatosMateria(array $data): ?array
    {
        if ($data['nombre'] === '' || $data['anio'] === '') {
            return [
                'message' => 'El nombre y el año son obligatorios.'
            ];
        }

        if (!ctype_digit($data['anio'])) {
            return [
                'message' => 'El año debe contener solo números.'
            ];
        }

        if ((int) $data['anio'] <= 0) {
            return [
                'message' => 'El año debe ser mayor a cero.'
            ];
        }

        return null;
    }
}
