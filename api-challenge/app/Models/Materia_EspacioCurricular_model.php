<?php

namespace App\Models;

class Materia_EspacioCurricular_model extends Base_model
{
    protected $table = 'Materia_EspacioCurricular';
    protected $primaryKey = null;

    protected $allowedFields = [
        'idMateria',
        'idEspCurr'
    ];
}