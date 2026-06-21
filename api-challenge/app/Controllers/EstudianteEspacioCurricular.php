<?php

namespace App\Controllers;

use App\Libraries\LibraryEstudiante;
use App\Libraries\LibraryEspacioCurricular;
use App\Libraries\LibraryEstadoEspacioCurricular;
use App\Libraries\LibraryEstudianteEspacioCurricular;

class EstudianteEspacioCurricular extends BaseController
{
    public function index()
    {
        $library = new LibraryEstudianteEspacioCurricular();
        $estudianteLibrary = new LibraryEstudiante();
        $espacioCurricularLibrary = new LibraryEspacioCurricular();
        $estadoEspacioCurricular = new LibraryEstadoEspacioCurricular();

        $data = $library->getAll();
        $data['estudiantes'] = $estudianteLibrary->getAll()['estudiantes'];
        $data['espaciosCurriculares'] = $espacioCurricularLibrary->getAll()['espaciosCurriculares'];
        $data['estadosEspaciosCurriculares'] = $estadoEspacioCurricular->getAll()['estados'];

        return view('estudiantes_espacios_curriculares/listado', $data);
    }

    public function create()
    {
        $json = $this->request->getJSON(true);
        $idEstudiante = trim((string) ($json['idEstudiante'] ?? ''));
        $idEspCurr = trim((string) ($json['idEspCurr'] ?? ''));
        $idEstadoEspCurr = trim((string) ($json['idEstadoEspCurr'] ?? ''));

        $dataRelacion = [
            'idEstudiante' => $idEstudiante,
            'idEspCurr' => $idEspCurr,
            'idEstadoEspCurr' => $idEstadoEspCurr
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

        $library = new LibraryEstudianteEspacioCurricular();
        $result = $library->create($dataRelacion);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 201 : 500))
            ->setJSON($result);
    }

    public function update(int $idEstudianteActual, int $idEspCurrActual)
    {
        $json = $this->request->getJSON(true);
        $idEstudiante = trim((string) ($json['idEstudiante'] ?? ''));
        $idEspCurr = trim((string) ($json['idEspCurr'] ?? ''));
        $idEstadoEspCurr = trim((string) ($json['idEstadoEspCurr'] ?? ''));

        $dataRelacion = [
            'idEstudiante' => $idEstudiante,
            'idEspCurr' => $idEspCurr,
            'idEstadoEspCurr' => $idEstadoEspCurr
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

        $library = new LibraryEstudianteEspacioCurricular();
        $result = $library->update($idEstudianteActual, $idEspCurrActual, $dataRelacion);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function delete(int $idEstudiante, int $idEspCurr)
    {
        $library = new LibraryEstudianteEspacioCurricular();
        $result = $library->delete($idEstudiante, $idEspCurr);

        return $this->response
            ->setStatusCode($result['statusCode'] ?? ($result['success'] ? 200 : 500))
            ->setJSON($result);
    }

    public function list()
    {
        $library = new LibraryEstudianteEspacioCurricular();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Relaciones obtenidas correctamente.',
                'data' => $library->getAll()['estudiantesEspaciosCurriculares']
            ]);
    }

    public function options()
    {
        $estudianteLibrary = new LibraryEstudiante();
        $espacioCurricularLibrary = new LibraryEspacioCurricular();
        $estadoEspacioCurricularLibrary = new LibraryEstadoEspacioCurricular();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'success' => true,
                'message' => 'Opciones obtenidas correctamente.',
                'data' => [
                    'estudiantes' => $estudianteLibrary->getAll()['estudiantes'],
                    'espaciosCurriculares' => $espacioCurricularLibrary->getAll()['espaciosCurriculares'],
                    'estadosEspaciosCurriculares' => $estadoEspacioCurricularLibrary->getAll()['estados']
                ]
            ]);
    }

    private function validarDatosRelacion(array $data): ?array
    {
        if ($data['idEstudiante'] === '' || $data['idEspCurr'] === '' || $data['idEstadoEspCurr'] === '') {
            return [
                'message' => 'El estudiante, el espacio curricular y el estado del espacio curricular son obligatorios.'
            ];
        }

        if (!ctype_digit($data['idEstudiante']) || !ctype_digit($data['idEspCurr']) || !ctype_digit($data['idEstadoEspCurr'])) {
            return [
                'message' => 'El estudiante, el espacio curricular o el estado del espacio curricular seleccionado no es válido.'
            ];
        }

        if ((int) $data['idEstudiante'] <= 0 || (int) $data['idEspCurr'] <= 0 || (int) $data['idEstadoEspCurr'] <= 0) {
            return [
                'message' => 'El estudiante, el espacio curricular o el estado del espacio curricular seleccionado no es válido.'
            ];
        }

        return null;
    }
}
