<?php

namespace App\Controllers;

use App\Libraries\LibraryEspacioCurricular;
use App\Libraries\LibraryPeriodo;
use App\Models\Periodo_model;

class EspacioCurricular extends BaseController
{
    public function index()
    {
        $library = new LibraryEspacioCurricular();
        $periodoLibrary = new LibraryPeriodo();

        $data = $library->getAll();
        $data['periodos'] = $periodoLibrary->getAll()['periodos'];

        return view('espacios_curriculares/listado', $data);
    }

    public function create()
    {
        $json = $this->request->getJSON(true);
        $nombre = trim($json['nombre'] ?? '');
        $idPeriodo = trim((string) ($json['idPeriodo'] ?? ''));

        $dataEspacioCurricular = [
            'nombre' => $nombre,
            'idPeriodo' => $idPeriodo
        ];

        $errorValidacion = $this->validarDatosEspacioCurricular($dataEspacioCurricular);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryEspacioCurricular();
        $result = $library->create($dataEspacioCurricular);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 201 : 500))
            ->setJSON($result);
    }

    public function createFromChallenge()
    {
        $json = $this->request->getJSON(true);
        $nombre = trim((string) ($json['nombre'] ?? ''));
        $periodo = trim((string) ($json['periodo'] ?? ''));

        if ($nombre === '' || $periodo === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'El nombre y el período son obligatorios.',
                    'data' => null,
                ]);
        }

        $periodoModel = new Periodo_model();
        $periodoExistente = $periodoModel->encontrarPeriodo([
            'nombre' => $periodo,
            'limit' => 1,
        ]);

        if (
            !$periodoExistente
            || !empty($periodoExistente->deletedBy)
            || !empty($periodoExistente->deletedAt)
        ) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'success' => false,
                    'message' => 'Período no encontrado.',
                    'data' => null,
                ]);
        }

        $library = new LibraryEspacioCurricular();
        $result = $library->create([
            'nombre' => $nombre,
            'idPeriodo' => (int) $periodoExistente->id,
        ]);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 201 : 500))
            ->setJSON($result);
    }

    public function update(int $id)
    {
        $json = $this->request->getJSON(true);
        $nombre = trim($json['nombre'] ?? '');
        $idPeriodo = trim((string) ($json['idPeriodo'] ?? ''));

        $dataEspacioCurricular = [
            'nombre' => $nombre,
            'idPeriodo' => $idPeriodo
        ];

        $errorValidacion = $this->validarDatosEspacioCurricular($dataEspacioCurricular);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryEspacioCurricular();
        $result = $library->update($id, $dataEspacioCurricular);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function delete(int $id)
    {
        $library = new LibraryEspacioCurricular();
        $result = $library->delete($id);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function list()
    {
        $library = new LibraryEspacioCurricular();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Espacios curriculares obtenidos correctamente.',
                'data' => $library->getAll()['espaciosCurriculares']
            ]);
    }

    public function periodos()
    {
        $periodoLibrary = new LibraryPeriodo();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Períodos obtenidos correctamente.',
                'data' => $periodoLibrary->getAll()['periodos']
            ]);
    }

    private function validarDatosEspacioCurricular(array $data): ?array
    {
        if ($data['nombre'] === '' || $data['idPeriodo'] === '') {
            return [
                'message' => 'El nombre y el período son obligatorios.'
            ];
        }

        if (!ctype_digit($data['idPeriodo'])) {
            return [
                'message' => 'El período seleccionado no es válido.'
            ];
        }

        if ((int) $data['idPeriodo'] <= 0) {
            return [
                'message' => 'El período seleccionado no es válido.'
            ];
        }

        return null;
    }
}
