<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon, DB, PDF, View, Dompdf\Dompdf;;

use App\Models\DiasOtorgados;
use App\Models\Omisiones;

use Illuminate\Support\Facades\Input;
//use \Hash, \Response;

use Illuminate\Support\Facades\Auth;

class LogsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

   public function obtenerLogs()
    {
        
        $parametros = Input::all();
        $logs = DiasOtorgados::with("siglas","capturista")->whereNotNull("captura_id")->orderBy("DATE","DESC")->paginate();
        $omisiones = Omisiones::with("capturista")->whereNotNull("MODIFYBY")->orderBy("DATE","DESC")->paginate();
      
        return response()->json(["logs" => $logs,"omision" => $omisiones]); 
    }
    
    
}