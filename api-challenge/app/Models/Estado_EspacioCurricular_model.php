<?php

namespace App\Models;

class Estado_EspacioCurricular_model extends Base_model
{
    protected $table = 'Estado_EspacioCurricular';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'estado'
    ];
}
