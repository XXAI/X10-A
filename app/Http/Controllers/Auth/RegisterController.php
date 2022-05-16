<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon, DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Response;


use App\Models\User;
use App\Models\BaseUser;
use App\Models\CluesUser;

use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    
    public function store(Request $request)
    {
       
        
        try {
            $registro = new User;
            $registro->username = $request->email;
            
            $registro->nombre = $request->name;
            $registro->apellido_paterno = $request->apellido_paterno;
            $registro->apellido_materno = $request->apellido_materno; 
           $registro->password = Hash::make($request->password);
            $registro->base_id=$request->base;
            $registro->email = $request->email;
            $registro->alias = "bsx";     
            $registro->is_superuser = 0;  
            $registro->save();
            $id_user=User::max('id'); 
            $user_base = new BaseUser;
            $user_base->user_id=$id_user;
            $user_base->base_id=$request->base;
            $user_base->save(); 
            $user_clues = new CluesUser;
            $user_clues->user_id=$id_user;
            $user_clues->clues=$request->clues; 
            $user_clues->save(); 
         

            return Response::json(['mensaje'=>'Registrado Correctamente ']); 
        } 
        catch (\Exception $e) {             
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
        }
    }
}
