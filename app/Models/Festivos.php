<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Festivos extends Model
{
    protected $connection = 'dinamica';
    protected $table = "HOLIDAYS";
}
