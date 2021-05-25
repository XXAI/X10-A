<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";
    protected $fillable = ['username', 'password', 'email', 'nombre', 'apellido_paterno', 'apellido_materno', 'alias', 'is_superuser'];
    protected $dateFormat = 'Y-d-m H:i:s.v';
    public $timestamps = false;
    public function BaseUsers(){
        return $this->hasMany('App\Models\BaseUser','user_id', "id");

    }
}
