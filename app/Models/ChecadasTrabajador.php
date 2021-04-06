<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecadasTrabajador extends Model
{
    protected $table = "CHECKINOUT";
   
  
    protected $fillable = ['USERID', 'CHECKTIME', 'CHECKTYPE','VERIFYCODE','SENSORID','MachineId','Memoinfo','UserExtFmt','WorkCode','sn'];
    public $timestamps = false;

}
