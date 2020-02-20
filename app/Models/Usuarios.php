<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = "USERINFO";

    public function horarios(){
        return $this->hasMany('App\Models\UsuarioHorario', 'USERID', "USERID");//->where("STARTDATE", "<=", date("Y-m-d").'T00:00:00')->where("ENDDATE", ">=", date("Y-m-d").'T00:00:00');
    }

    public function horario(){
        return $this->hasMany('App\Models\UsuarioHorario', 'USERID', "USERID");
    }

    public function checadas(){
        return $this->hasMany('App\Models\ChecadasTrabajador', 'USERID', "USERID");
    }

    public function omisiones(){
        return $this->hasMany('App\Models\Omisiones', 'USERID', "USERID");
    }

    public function dias_otorgados(){
        return $this->hasMany('App\Models\DiasOtorgados', 'USERID', "USERID");
    }
}