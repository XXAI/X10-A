<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecadasTrabajador extends Model
{
    protected $connection = 'dinamica';   
    protected $table = "CHECKINOUT";
   
  
    protected $fillable = ['USERID', 'CHECKTIME', 'CHECKTYPE','VERIFYCODE','SENSORID','MachineId','Memoinfo','UserExtFmt','WorkCode','sn'];
    public $timestamps = false;


    public function empleado(){
        return $this->BelongsTo('App\Models\Usuarios', 'USERID', "USERID");
    }

    public function getKeyName(){
        return "LOGID";
    }

}
