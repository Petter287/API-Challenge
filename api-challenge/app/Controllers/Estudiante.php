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
        $dni = trim($json['dni'] ?? '');
        $fechaNacimiento = trim($json['fechaNacimiento'] ?? '');

        if ($nombre === '' || $apellido === '' || $dni === '' || $fechaNacimiento === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'El nombre, el apellido, el DNI y la fecha de nacimiento son obligatorios.',
                    'data' => null
                ]);
        }

        if (!$this->isValidDate($fechaNacimiento)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'La fecha de nacimiento debe tener el formato YYYY-MM-DD.',
                    'data' => null
                ]);
        }

        $library = new LibraryEstudiante();

        $result = $library->create([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'fechaNacimiento' => $fechaNacimiento
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
        $dni = trim($json['dni'] ?? '');
        $fechaNacimiento = trim($json['fechaNacimiento'] ?? '');

        if ($nombre === '' || $apellido === '' || $dni === '' || $fechaNacimiento === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'El nombre, el apellido, el DNI y la fecha de nacimiento son obligatorios.',
                    'data' => null
                ]);
        }

        if (!$this->isValidDate($fechaNacimiento)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'La fecha de nacimiento debe tener el formato YYYY-MM-DD.',
                    'data' => null
                ]);
        }

        $library = new LibraryEstudiante();
        $result = $library->update($id, [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'fechaNacimiento' => $fechaNacimiento
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

    private function isValidDate(string $date): bool
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        $errors = \DateTime::getLastErrors();

        return $dateTime !== false
            && $dateTime->format('Y-m-d') === $date
            && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0));
    }
}
