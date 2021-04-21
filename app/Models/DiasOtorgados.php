<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiasOtorgados extends Model
{
    protected $connection = 'dinamica';
    protected $table = "USER_SPEDAY";

    public function siglas(){
        return $this->hasOne('App\Models\Siglas', 'LeaveId', "DATEID");
    }
}
