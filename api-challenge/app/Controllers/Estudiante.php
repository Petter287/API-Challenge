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

        $dataEstudiante = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'fechaNacimiento' => $fechaNacimiento
        ];

        $errorValidacion = $this->validarDatosEstudiante($dataEstudiante);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryEstudiante();

        $result = $library->create($dataEstudiante);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 201 : 500))
            ->setJSON($result);
    }

    public function update(int $id)
    {
        $json = $this->request->getJSON(true);

        $nombre = trim($json['nombre'] ?? '');
        $apellido = trim($json['apellido'] ?? '');
        $dni = trim($json['dni'] ?? '');
        $fechaNacimiento = trim($json['fechaNacimiento'] ?? '');

        $dataEstudiante = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'fechaNacimiento' => $fechaNacimiento
        ];

        $errorValidacion = $this->validarDatosEstudiante($dataEstudiante);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryEstudiante();
        $result = $library->update($id, $dataEstudiante);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function delete(int $id)
    {
        $library = new LibraryEstudiante();
        $result = $library->delete($id);
        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function list()
    {
        $library = new LibraryEstudiante();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Estudiantes obtenidos correctamente.',
                'data' => $library->getAll()['estudiantes']
            ]);
    }

    private function isValidDate(string $date): bool
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        $errors = \DateTime::getLastErrors();

        return $dateTime !== false
            && $dateTime->format('Y-m-d') === $date
            && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0));
    }

    private function validarDatosEstudiante(array $data): ?array
    {
        if (
            $data['nombre'] === '' ||
            $data['apellido'] === '' ||
            $data['dni'] === '' ||
            $data['fechaNacimiento'] === ''
        ) {
            return [
                'message' => 'El nombre, el apellido, el DNI y la fecha de nacimiento son obligatorios.'
            ];
        }

        if (!ctype_digit($data['dni'])) {
            return [
                'message' => 'El DNI debe contener solo números.'
            ];
        }

        if (strlen($data['dni']) < 7 || strlen($data['dni']) > 9) {
            return [
                'message' => 'El DNI debe tener entre 7 y 9 dígitos.'
            ];
        }

        if (!$this->isValidDate($data['fechaNacimiento'])) {
            return [
                'message' => 'La fecha de nacimiento debe tener el formato YYYY-MM-DD.'
            ];
        }

        if (strtotime($data['fechaNacimiento']) > time()) {
            return [
                'message' => 'La fecha de nacimiento no puede ser futura.'
            ];
        }

        return null;
    }
}
