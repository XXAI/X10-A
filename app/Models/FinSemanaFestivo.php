<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinSemanaFestivo extends Model
{
    protected $table = 'USER_TEMP_SCH';


    public function festivo_finsemana(){
        return $this->hasOne('App\Models\ReglasHorario', 'schClassid', 'SCHCLASSID');
    }
}
