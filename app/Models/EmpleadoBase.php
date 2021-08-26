<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoBase extends Model
{
    protected $connection = 'dinamica';
    protected $table = "tablaBases";
}
