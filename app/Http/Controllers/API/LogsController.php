<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon, DB, PDF, View, Dompdf\Dompdf;;

use App\Models\DiasOtorgados;
use App\Models\Omisiones;
use App\Models\User;
use App\Models\BaseUser;
use App\Models\ChecadasTrabajador;
use App\Models\Usuarios;
use App\Models\CluesUser;
use App\Exports\CapturistasExport;
use Maatwebsite\Excel\Facades\Excel; 


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
    public function reporteCapturista(Request $request)
    {

        
        $logs = $this->obtenerLogs($request);
        //dd($logs);
        $pdf = PDF::loadView('reportes//reporte-capturista', ['capturista' => $logs]);
        $pdf->setPaper('LEGAL', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);
        return $pdf->stream('Reporte-Capturista.pdf');
    }

     public function export() 
    {
       // $logs = $this->obtenerLogs($request);
       // dd($logs);
        return Excel::download(new CapturistasExport, 'prueba.xlsx');
        
        //return ("holaaaa");
    } 

   public function obtenerLogs(Request $request)
    {
        
        $parametros = Input::all();
        $inicio = $request->inicio;
        $fin = $request->fin;
        $user = $request->get('user');
        //dd($user);
        $logs = DiasOtorgados::with("siglas","capturista","empleado")->whereNotNull("captura_id")->whereBetween("DATE",[$inicio,$fin]);
        $omisiones = Omisiones::with("capturista")->whereNotNull("MODIFYBY")->orderBy("DATE","DESC")->paginate();
        

        if($user != 0) {          
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

    public function obtenerchecadas(Request $request)
    {
    
      $obtengoclues = CluesUser::where("user_id","=",auth()->user()['id'])->get();
      $arreglo_clues = [];
      if(count($obtengoclues) > 0)
      {
          $arreglo_clues = $this->clues_users($obtengoclues);
          
      }
       
      $conexion = DB::connection('dinamica');
     
      if(isset($conexion)) $nombrebase = $conexion->getDatabaseName(); 

      $parametros = Input::all();
      $inicio = $request->inicio;
      $fin = $request->fin;
      $name = $request->nombre;
      //dd($user);
    //  $checadas= ChecadasTrabajador::with("empleado")->whereBetween("CHECKTIME", [$inicio."T00:00:00.000", $fin."T23:59:59.000"]);
/*       $checadas= ChecadasTrabajador::with(['empleado'=>function($query)use($nombre){
        $query->where('Name','LIKE','%'.$nombre.'%')->orWhere('TITLE','LIKE','%'.$nombre.'%')->orWhere('Badgenumber','%'.$nombre.'%');}])   
      
        ->whereBetween("CHECKTIME", [$inicio."T00:00:00.000", $fin."T23:59:59.000"])
        ->orderBy("CHECKTIME","DESC")
        
    ->get(); */

    $checadas= $conexion->table("checkinout")->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
    ->whereBetween("CHECKTIME", [$inicio."T00:00:00.000", $fin."T23:59:59.000"]);
    if($name !='')
    $checadas=$checadas->Where(function($query2)use($name){
       $query2->where("Name",'LIKE','%'.$name.'%')
               ->orWhere("TITLE",'LIKE','%'.$name.'%')
               ->orWhere("Badgenumber",'=',$name);
   });
   $checadas=$checadas->orderBy("CHECKTIME","ASC")->WHEREIN("FPHONE", $arreglo_clues)
        
   ->get();
     // return response()->json(["logs" => $logs,"omision" => $omisiones]); 
      return array("checadas" => $checadas);
        
        //}
    }


    function clues_users($arreglo)
    {
        $arreglo_clues = array();
        $arrprueba = [];
        foreach ($arreglo as $key => $value) {
            $arreglo_clues[] = $value->clues;
           
        }
        return $arreglo_clues;//$arreglo_clues;
    }
    
    
}