<?php

namespace App\Entities;
use App\Entities\BaseEntity;

class EstudianteEntity extends BaseEntity
{
    public string $nombre;
    public string $apellido;
    public string $dni;
    public string $fechaNacimiento;
}
