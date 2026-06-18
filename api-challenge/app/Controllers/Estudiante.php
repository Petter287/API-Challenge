<?php

namespace App\Controllers;

use App\Libraries\LibraryEstudiante;

class Estudiante extends BaseController
{
    public function index()
    {
        $library = new LibraryEstudiante();
        $data = $library->getAll();

        return view('estudiantes/listado', $data);
    }

    public function create()
    {
        $json = $this->request->getJSON(true);

        $nombre = trim($json['nombre'] ?? '');
        $apellido = trim($json['apellido'] ?? '');

        if ($nombre === '' || $apellido === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'El nombre y el apellido son obligatorios.',
                    'data' => null
                ]);
        }

        $library = new LibraryEstudiante();

        $result = $library->create([
            'nombre' => $nombre,
            'apellido' => $apellido
        ]);

        return $this->response
            ->setStatusCode($result['success'] ? 201 : 500)
            ->setJSON($result);
    }

    public function update(int $id)
    {
        $json = $this->request->getJSON(true);

        $nombre = trim($json['nombre'] ?? '');
        $apellido = trim($json['apellido'] ?? '');

        if ($nombre === '' || $apellido === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'El nombre y el apellido son obligatorios.',
                    'data' => null
                ]);
        }

        $library = new LibraryEstudiante();
        $result = $library->update($id, [
            'nombre' => $nombre,
            'apellido' => $apellido
        ]);

        return $this->response
            ->setStatusCode($result['success'] ? 200 : 500)
            ->setJSON($result);
    }

    public function delete(int $id)
    {
        $library = new LibraryEstudiante();
        $result = $library->delete($id);
        return $this->response
            ->setStatusCode($result['success'] ? 200 : 500)
            ->setJSON($result);
    }
}
