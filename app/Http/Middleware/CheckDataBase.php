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
       // console.log('hola pablo');
        $namedb = Request::header('namedb'); // Este es el parámetro a validar
        if(!empty($namedb)){
            \Config::set('database.connections.dinamica.database',$namedb); // Asigno la DB que voy a usar
            DB::connection('dinamica'); //Asigno la nueva conexión al sistema. 
        }
        return $next($request);
    }
}
