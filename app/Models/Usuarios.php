<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    
    protected $connection = 'dinamica';
   
    
    protected $table = "USERINFO";

    
    
    protected $fillable = [ 'Badgenumber','Name','Gender','TITLE','PAGER','BIRTHDAY','HIREDDAY','street','CITY','STATE','ZIP','FPHONE','DEFAULTDEPTID','MINZU'];//,'ATT','INLATE','OUTEARLY','OVERTIME','SEP','HOLIDAY','MINZU','status'];
    public $timestamps = false;


    public function horarios(){
        return $this->hasMany('App\Models\UsuarioHorario', 'USERID', "USERID")->orderBy('ENDDATE','DESC')->with("nombre_horario");//  ->where("STARTDATE", "<=", date("Y-m-d").'T00:00:00')->where("ENDDATE", ">=", date("Y-m-d").'T00:00:00');
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

    public function dias_justificados(){
        return $this->hasMany('App\Models\DiasJustifica', 'USERID', "USERID");
    }

  

    public function getKeyName(){
        return "USERID";
    }
}
