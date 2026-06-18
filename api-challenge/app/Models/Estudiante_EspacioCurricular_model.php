<?php

namespace App\Models;

class Estudiante_EspacioCurricular_model extends Base_model
{
    protected $table = 'Estudiante_EspacioCurricular';
    protected $primaryKey = null;

    protected $allowedFields = [
        'idEstudiante',
        'idEspCurr',
        'idEstadoEspCurr'
    ];
}
