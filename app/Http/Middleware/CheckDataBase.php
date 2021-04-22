<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckDataBase as Middleware;

use Closure;
use Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;


class CheckDataBase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $desc = $request->desc;
       // console.log('hola pablo');
        $rfc = Request::header('buscar'); // Este es el parámetro a validar
      //$namedb = $request->namedb;
      echo $rfc;
     /*  $buscaBase=DB::table("tablaBases")->where("rfc","=",$desc)->first();
     dd($buscaBase); */
      if(isset(auth()->user()['nombre'])){
        
        $namedb = auth()->user()['nombre'];
    }
      
      //$namedb=auth()->user()['nombre'];
      //echo auth()->user()['nombre'];
      /* if(auth()->user()['nombre']=='Administrator'){
        $namedb ='ZKAccess';
      }else{$namedb ='gomezmaza';}
 */
      //echo $namedb;
        if(!empty($namedb)){
            \Config::set('database.connections.dinamica.database',$namedb); // Asigno la DB que voy a usar
            DB::connection('dinamica'); //Asigno la nueva conexión al sistema. 
        }
        return $next($request);
    }
}
