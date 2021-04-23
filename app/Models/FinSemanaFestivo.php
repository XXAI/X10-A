<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinSemanaFestivo extends Model
{
    protected $connection = 'dinamica';
    protected $table = 'USER_TEMP_SCH';


    public function festivo_finsemana(){
        return $this->hasOne('App\Models\ReglasHorarios', 'schClassid', 'SCHCLASSID');
    }
}
