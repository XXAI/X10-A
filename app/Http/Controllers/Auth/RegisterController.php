<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon, DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Response;


use App\Models\User;

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
           $registro->password = Hash::make([$request->password]);
          //  $registro->password = $request->password;
            $registro->email = $request->email;
            $registro->alias = "bsx";        
            $registro->save();
            return Response::json(['mensaje'=>'Registrado Correctamente ']); 
        } 
        catch (\Exception $e) {             
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
        }
    }
}
