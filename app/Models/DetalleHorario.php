<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleHorario extends Model
{
    //protected $connection = 'dinamica';
    
    protected $table = "NUM_RUN_DEIL";
    
    public function reglaAsistencia(){
        return $this->hasOne('App\Models\ReglaAsistencia', "schClassid", 'SCHCLASSID');
    }



   
   
}
