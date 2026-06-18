<?php

namespace App\Models;

class EspacioCurricular_model extends Base_model
{
    protected $table = 'Espacio_Curricular';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'idPeriodo'
    ];
}
