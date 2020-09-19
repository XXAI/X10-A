<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiasJustifica extends Model
{
    protected $table = "USER_SPEDAY";
    public $timestamps = false;

    public function Incidencias(){
        return $this->hasOne('App\Models\Incidencias',  'id',"incidencia_id");
    }


  


    public function getKeyName(){
        return "incidencia_id";
    }
}
