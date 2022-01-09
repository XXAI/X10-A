<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class incidenciasEliminadas extends Model
{
    protected $connection = 'dinamica';
    protected $table = 'incidencias_eliminadas';
    public $timestamps = false;
}
