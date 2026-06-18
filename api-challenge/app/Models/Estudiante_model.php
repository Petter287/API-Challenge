<?php

namespace App\Models;

class Estudiante_model extends Base_model
{
    protected $table = 'Estudiante';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'apellido'
    ];
}
