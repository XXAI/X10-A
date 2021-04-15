<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioHorario extends Model
{
    //protected $connection = 'ZK';
    protected $table = "USER_OF_RUN";
    public $timestamps = false;
   
   
    public function detalleHorario(){
        return $this->hasMany('App\Models\DetalleHorario','NUM_RUNID', "NUM_OF_RUN_ID");

    }

    public function nombre_horario(){
        return $this->hasMany('App\Models\Horario', "NUM_RUNID",'NUM_OF_RUN_ID')->orderBy('STARTDATE','DESC');

    }


   /*   public function getKeyName(){
        return "USERID";
    } */
        
}
