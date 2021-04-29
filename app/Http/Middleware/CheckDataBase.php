<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckDataBase as Middleware;

use Closure;
use Request;
use Illuminate\Support\Facades\DB;
use App\Models\BaseUser;

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
      if(isset(auth()->user()['id'])){
        
        $iduser = auth()->user()['id'];
        $buscaBase = BaseUser::where("user_id","=",$iduser)->first();
        $namedb = $buscaBase->base;
      
    }
    
    

   /*    if(isset(auth()->user()['nombre'])){
        
        $namedb = auth()->user()['nombre'];
    } */
      
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
