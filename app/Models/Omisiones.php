<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Omisiones extends Model
{
    protected $connection = 'dinamica';
    protected $table = "CHECKEXACT";
    protected $fillable = ['USERID','CHECKTIME','CHECKTYPE','MODIFYBY','DATE','YUYIN','ISADD','ISMODIFY','ISDELETE','INCOUNT','ISCOUNT'];
     
    public $timestamps = false;
}
