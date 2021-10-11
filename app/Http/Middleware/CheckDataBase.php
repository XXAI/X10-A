<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckDataBase as Middleware;

use Closure;
use Request;
use Illuminate\Support\Facades\DB;
use App\Models\BaseUser;
use App\Models\CatalogoBases;
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
        $idbase = auth()->user()['base_id'];
       // dd($idbase);
       // $buscaBase = BaseUser::where("user_id","=",$iduser)->first();
        $buscaBase = CatalogoBases::where("id","=",$idbase)->first();
        $namedb = $buscaBase->descripcion;
      
    }
    
    
        if(!empty($namedb)){
            
            \Config::set('database.connections.dinamica.database',$namedb); // Asigno la DB que voy a usar
            if($namedb='gomezmaza'){
                DB::connection('GM'); //Asigno la nueva conexión al sistema. 
            }else{
                DB::connection('dinamica');
            }
        }
        return $next($request);
    }
}
