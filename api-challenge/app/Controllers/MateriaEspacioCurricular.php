<?php

namespace App\Controllers;

use App\Libraries\LibraryEspacioCurricular;
use App\Libraries\LibraryMateria;
use App\Libraries\LibraryMateriaEspacioCurricular;

class MateriaEspacioCurricular extends BaseController
{
    public function index()
    {
        $library = new LibraryMateriaEspacioCurricular();
        $materiaLibrary = new LibraryMateria();
        $espacioCurricularLibrary = new LibraryEspacioCurricular();

        $data = $library->getAll();
        $data['materias'] = $materiaLibrary->getAll()['materias'];
        $data['espaciosCurriculares'] = $espacioCurricularLibrary->getAll()['espaciosCurriculares'];

        return view('materias_espacios_curriculares/listado', $data);
    }

    public function create()
    {
        $json = $this->request->getJSON(true);
        $idMateria = trim((string) ($json['idMateria'] ?? ''));
        $idEspCurr = trim((string) ($json['idEspCurr'] ?? ''));

        $dataRelacion = [
            'idMateria' => $idMateria,
            'idEspCurr' => $idEspCurr
        ];

        $errorValidacion = $this->validarDatosRelacion($dataRelacion);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryMateriaEspacioCurricular();
        $result = $library->create($dataRelacion);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 201 : 500))
            ->setJSON($result);
    }

    public function createFromChallenge(int $idMateria, int $idEspCurr)
    {
        $library = new LibraryMateriaEspacioCurricular();
        $result = $library->create([
            'idMateria' => $idMateria,
            'idEspCurr' => $idEspCurr,
        ]);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 201 : 500))
            ->setJSON($result);
    }

    public function update(int $idMateriaActual, int $idEspCurrActual)
    {
        $json = $this->request->getJSON(true);
        $idMateria = trim((string) ($json['idMateria'] ?? ''));
        $idEspCurr = trim((string) ($json['idEspCurr'] ?? ''));

        $dataRelacion = [
            'idMateria' => $idMateria,
            'idEspCurr' => $idEspCurr
        ];

        $errorValidacion = $this->validarDatosRelacion($dataRelacion);

        if ($errorValidacion) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => $errorValidacion['message'],
                    'data' => null
                ]);
        }

        $library = new LibraryMateriaEspacioCurricular();
        $result = $library->update($idMateriaActual, $idEspCurrActual, $dataRelacion);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function delete(int $idMateria, int $idEspCurr)
    {
        $library = new LibraryMateriaEspacioCurricular();
        $result = $library->delete($idMateria, $idEspCurr);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function list()
    {
        $library = new LibraryMateriaEspacioCurricular();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Relaciones obtenidas correctamente.',
                'data' => $library->getAll()['materiasEspaciosCurriculares']
            ]);
    }

    public function options()
    {
        $materiaLibrary = new LibraryMateria();
        $espacioCurricularLibrary = new LibraryEspacioCurricular();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Opciones obtenidas correctamente.',
                'data' => [
                    'materias' => $materiaLibrary->getAll()['materias'],
                    'espaciosCurriculares' => $espacioCurricularLibrary->getAll()['espaciosCurriculares']
                ]
            ]);
    }

    private function validarDatosRelacion(array $data): ?array
    {
        if ($data['idMateria'] === '' || $data['idEspCurr'] === '') {
            return [
                'message' => 'La materia y el espacio curricular son obligatorios.'
            ];
        }

        if (!ctype_digit($data['idMateria']) || !ctype_digit($data['idEspCurr'])) {
            return [
                'message' => 'La materia o el espacio curricular seleccionado no es válido.'
            ];
        }

        if ((int) $data['idMateria'] <= 0 || (int) $data['idEspCurr'] <= 0) {
            return [
                'message' => 'La materia o el espacio curricular seleccionado no es válido.'
            ];
        }

        return null;
    }
}
