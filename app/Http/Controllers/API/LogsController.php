<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon, DB, PDF, View, Dompdf\Dompdf;;

use App\Models\DiasOtorgados;
use App\Models\Omisiones;
use App\Models\User;
use App\Models\BaseUser;

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
        //dd($user);
        $logs = DiasOtorgados::with("siglas","capturista","empleado")->whereNotNull("captura_id")->whereBetween("DATE",[$inicio,$fin]);
        $omisiones = Omisiones::with("capturista")->whereNotNull("MODIFYBY")->orderBy("DATE","DESC")->paginate();
        

        if($user != 'NaN') {          
          $logs=$logs->Where(function($query)use($user){
          $query->where("captura_id",'=',$user);
          }); 
         }
     
      $logs= $logs->orderBy("DATE","DESC")->paginate(2000);
       // return response()->json(["logs" => $logs,"omision" => $omisiones]); 
        return array("logs" => $logs, "omision" => $omisiones);
    }

    public function buscacapturista(Request $request)
    {
        
       /*  if($request->get('bh'))
        {  */ 
        $iduser = auth()->user()['id'];
        $buscaBase = BaseUser::where("user_id","=",$iduser)->first();
        $namedb = $buscaBase->base;
        
        $bi = $request->get('bi');
       // $data_in = User::with("BaseUsers")->orderBy('nombre','ASC')->where("nombre",'LIKE','%'.$bi.'%')->get(); 
        
        $data_in = DB::table('users')
        ->join("users_bases", "users_bases.user_id", "=","users.id")
        ->where("nombre",'LIKE','%'.$bi.'%')->where("base","=",$namedb)->where("users.id",'!=',1)->get(); 
        
        //dd($data_in); ->where("base","=",$namedb)
      
      return response()->json($data_in);  
        
        //}
    }
    
    
}