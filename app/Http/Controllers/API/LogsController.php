<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon, DB, PDF, View, Dompdf\Dompdf;;

use App\Models\DiasOtorgados;
use App\Models\Omisiones;
use App\Models\User;

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
   

   public function obtenerLogs(Request $request)
    {
        
        $parametros = Input::all();
        $inicio = $request->inicio;
        $fin = $request->fin;
        $user = $request->get('user');
       // dd($user);
        $logs = DiasOtorgados::with("siglas","capturista")->whereNotNull("captura_id")->whereBetween("DATE",[$inicio,$fin]);
        $omisiones = Omisiones::with("capturista")->whereNotNull("MODIFYBY")->orderBy("DATE","DESC")->paginate();
        

        if($user!=''){
            $logs=$logs->where("captura_id",'=',$user);
        }
        
      $logs= $logs->orderBy("DATE","DESC")->paginate();
        return response()->json(["logs" => $logs,"omision" => $omisiones]); 
    }

    public function buscacapturista(Request $request)
    {
        
       /*  if($request->get('bh'))
        {  */ 
        $bi = $request->get('bi');
        $data_in = User::orderBy('nombre','ASC')->where("nombre",'LIKE','%'.$bi.'%')->get();          
        
      
      return response()->json($data_in);  
        
        //}
    }
    
    
}