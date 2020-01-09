<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = "USERINFO";

    public function horarios(){
        return $this->hasMany('App\Models\UsuarioHorario', 'USERID', "USERID")->where("STARTDATE", "<=", date("Y-m-d").'T00:00:00')->where("ENDDATE", ">=", date("Y-m-d").'T00:00:00');
    }
}
