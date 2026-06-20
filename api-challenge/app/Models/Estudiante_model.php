<?php

namespace App\Models;

use App\Entities\EstudianteEntity;

class Estudiante_model extends Base_model
{
    protected $table = 'Estudiante';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'apellido',
        'dni',
        'fechaNacimiento'
    ];

    public function encontrarEstudiante(array $params)
    {
        $query = $this->db->table($this->table);

        if (isset($params['nombre'])) {
            $query->where('nombre', $params['nombre']);
        }

        if (isset($params['apellido'])) {
            $query->where('apellido', $params['apellido']);
        }

        if (isset($params['dni'])) {
            $query->where('dni', $params['dni']);
        }
        
        if (isset($params['fechaNacimiento'])) {
            $query->where('fechaNacimiento', $params['fechaNacimiento']);
        }

        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        return $query->get()->getRow();
    }

    public function nuevoEstudiante(array $data)
    {
        $estudiante = new EstudianteEntity();
        $estudiante->nombre = $data['nombre'] ?? null;
        $estudiante->apellido = $data['apellido'] ?? null;
        $estudiante->dni = $data['dni'] ?? null;
        $estudiante->fechaNacimiento = $data['fechaNacimiento'] ?? null;

        return $this->insert($estudiante);
    }

    public function actualizarEstudiante(int $id, array $data)
    {
        $estudianteExistente = (object) $this->find($id);
        if (!$estudianteExistente) {
            return false;
        }

        $estudianteExistente->nombre = $data['nombre'] ?? $estudianteExistente->nombre;
        $estudianteExistente->apellido = $data['apellido'] ?? $estudianteExistente->apellido;
        $estudianteExistente->dni = $data['dni'] ?? $estudianteExistente->dni;
        $estudianteExistente->fechaNacimiento = $data['fechaNacimiento'] ?? $estudianteExistente->fechaNacimiento;

        return $this->update($id, $estudianteExistente);
    }
}
