<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalidaAutorizada extends Model
{
    protected $connection = 'dinamica';
    protected $table = "SAL_AUTO";
}
