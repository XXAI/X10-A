<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Omisiones extends Model
{
    protected $connection = 'dinamica';
    protected $table = "CHECKEXACT";
    protected $fillable = ['USERID','CHECKTIME','CHECKTYPE','MODIFYBY','DATE','YUYIN','ISADD','ISMODIFY','ISDELETE','INCOUNT','ISCOUNT'];
    

    public function capturista(){
        return $this->hasOne('App\Models\User', 'id', "MODIFYBY");
    }
    public $timestamps = false;

    public function getKeyName(){
        return "EXACTID";
    }

}
