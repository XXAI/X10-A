<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    //protected $connection = 'GM';
    
    protected $connection = 'dinamica';   
    protected $table = "NUM_RUN";

    public function deta_Horario(){
        return $this->hasOne('App\Models\DetalleHorario','NUM_RUNID', "NUM_OF_RUN_ID");

    }

/*     public function xxxx(){
        return $this->hasMany('App\Models\UsuarioHorario', "NUM_OF_RUN_ID", 'NUM_RUNID');
    }

    public function usuario(){

        return $this->belongsToMany('App\Models\Usuarios', 'USER_OF_RUN', 'NUM_OF_RUN_ID', 'USERID');
        
    } */
   
}
