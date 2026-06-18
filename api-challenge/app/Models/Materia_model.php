<?php

namespace App\Models;

class Materia_model extends Base_model
{
    protected $table = 'Materia';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'anio',
    ];
}
