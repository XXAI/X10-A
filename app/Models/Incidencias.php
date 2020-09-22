<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencias extends Model
{
    protected $table = 'incidencias';

    public function TiposIncidencia(){
        return $this->hasOne('App\Models\TiposIncidencia','LeaveId',"incidencias_tipo_id");
    }


    public function Usuarios(){
        return $this->hasOne('App\Models\Usuarios',  'USERID',"USERID");
    }

    
}
