<?php

namespace App\Models;

class Periodo_model extends Base_model
{
    protected $table = 'Periodo';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre'
    ];
}
