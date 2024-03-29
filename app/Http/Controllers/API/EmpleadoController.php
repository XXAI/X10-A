<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\EmpleadoRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon, DB;
use App\Models\Usuarios;
use App\Models\Departamentos;
use App\Models\UsuarioHorario;
use App\Models\ReglasHorarios;
use App\Models\Festivos;
use App\Models\Omisiones;
use App\Models\DiasJustifica;
use App\Models\DiasOtorgados;
use App\Models\Horario;
use App\Models\TiposIncidencia;
use App\Models\ChecadasTrabajador;
use App\Models\CluesUser;
use App\Models\CatalogoBases;
use App\Models\EmpleadoBase;
use App\Models\Edificios;
use App\Models\RfcBase;




use App\Models\User;
//use \Hash, \Response;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      
        $name = $request->get('buscar');     
    
        $buscaBase=DB::table("catalogo_bases")->where("id","=",auth()->user()['base_id'])->first();
        $max=Usuarios::max('USERID');
                if(isset($max)){
                $maxid=Usuarios::select('Badgenumber as num_max')
                
                ->where('USERID','=',$max)
                ->get();   
                $maxid=($maxid[0]->num_max)+1;}
                else{
                    $maxid=1; 
                };
        $obtengoclues = CluesUser::where("user_id","=",auth()->user()['id'])->get();
        $arreglo_clues = [];
        if(count($obtengoclues) > 0)
        {
            $arreglo_clues = $this->clues_users($obtengoclues);
            
        }  
      //  dd($arreglo_clues);
        $idcap = Auth::id();         
    
        $usuarios = Usuarios::with("horarios.detalleHorario","dias_justificados","tipotrabajador")->where('status', '=', 0)->WHEREIN("FPHONE", $arreglo_clues);        
     
      
         if($name !='')
         $usuarios=$usuarios->Where(function($query2)use($name){
            $query2->where("Name",'LIKE','%'.$name.'%')
                    ->orWhere("TITLE",'LIKE','%'.$name.'%')
                    ->orWhere("Badgenumber",'=',$name);
        });

        

 //DB::enableQueryLog(); 
         //CSSSA009162
        $usuarios = $usuarios->where("HOLIDAY",'<>',0)->orderBy('USERID','DESC')->paginate(200);
        //dd($usuarios);
        $incidencias = TiposIncidencia::orderBy('LeaveName','ASC')->whereNotIn('LeaveId', [4,5,7,9,28])->get();  
        $departamentos = Departamentos::get();  
        $edificios = Edificios::get();   
        $festivos = Festivos::get();   
        
        //dd($incidencias);
        return response()->json(["max" => $maxid,"base" => $buscaBase,"usuarios" => $usuarios,"incidencias" => $incidencias,"departamentos" => $departamentos,"festivos" => $festivos,"edificios" => $edificios]); 
      // dd(DB::getQueryLog());
    }

    public function fetch(Request $request)
    {
        
       /*  if($request->get('bh'))
        {  */ 
        $bh = $request->get('bh');
        $data = Horario::with('deta_Horario')->select('NUM_RUNID','NAME')->where("NAME",'LIKE','%'.$bh.'%')->get();        
        
      
      return response()->json($data);  
        
        //}
    }
    public function catalogo_bases(Request $request)
    {
        $catalogobases = CatalogoBases::all();
        return response()->json(["catalogo" => $catalogobases]);
    }


    public function tipoincidencia(Request $request)
    {
        
       /*  if($request->get('bh'))
        {  */ 
        $bi = $request->get('bi');
        $data_in = TiposIncidencia::orderBy('LeaveName','ASC')->where("LeaveName",'LIKE','%'.$bi.'%')->whereNotIn('LeaveId', [4,5,7,9,28])->get();          
        
      
      return response()->json($data_in);  
        
        //}
    }
    public function llenarSelect()
    {
        $incidencias = TiposIncidencia::all();         
        return \View::make('incidenciaform',compact('incidencias'));
        //return $incidencias;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmpleadoRequest $request)
    {

        try {
       // if($request->ajax()){
                $buscaBase=DB::table("catalogo_bases")->where("id","=",auth()->user()['base_id'])->first();
                $edificio = Edificios::select("DEPTID")->where("type","=",$request->clues)->first();
                $edificio = $edificio['DEPTID'];
                $buscaBase=$buscaBase->descripcion;
               // dd($buscaBase);
               
                 
                    $registro = new Usuarios;
                    
                    if($buscaBase=="gomezmaza"){
                        $maxid=$request->biome;
                       
                    }
                    else{
                        $max=Usuarios::max('USERID');
                        if(isset($max)){
                        $maxid=Usuarios::select('Badgenumber as num_max')
                        
                        ->where('USERID','=',$max)
                        ->get();   
                        $maxid=($maxid[0]->num_max)+1;}
                        else{
                            $maxid=1; 
                        }                   
                     }
                    $registro->Badgenumber= $maxid;
                    $registro->Name = $request->name;
                    $registro->Gender = $request->sexo;
                    $registro->TITLE = $request->rf;        
                    $registro->PAGER = $request->codigo;
                    $registro->DEFAULTDEPTID = $edificio;
                    $registro->BIRTHDAY = $request->fecnac;
                    $registro->HIREDDAY=$request->fechaing;
                    $registro->street=$request->street;
                    $registro->CITY=$request->city;
                    $registro->STATE=0;
                    $registro->ATT=$request->mmi;
                    $registro->INLATE= $request->interino;
                    $registro->ZIP= 1;
                    $registro->FPHONE=$request->clues;
                    $registro->ur_id=$request->tipotra;            
                    $registro->MINZU=$request->area;   
                   $registro->save();

                   $rfcbase= new RfcBase;
                   $rfcbase->rfc = $request->rf; 
                   $rfcbase->base = $buscaBase; 
                   $rfcbase->save();
                    if ($request->code!=''){
                        
                        $id_user=Usuarios::max('USERID');       
                        $user_hora = new UsuarioHorario;
                        $user_hora->USERID=$id_user;
                        $user_hora->NUM_OF_RUN_ID=$request->code;
                        $user_hora->STARTDATE=$request->ini_fec;
                        $user_hora->ENDDATE=$request->fin_fec;
                        $user_hora->ISNOTOF_RUN=0;
                        $user_hora->ORDER_RUN=0;
                       $user_hora->save();
                        }
                    return response()->json(['mensaje'=>'Registrado Correctamente ID:  '. $maxid]); 
       // }
    }
    catch (\Exception $e) {            
        return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empleado = Usuarios::with("horarios.detalleHorario","dias_justificados")->find($id);
        //->join("num_run", "num_run.NUM_RUNID", "=", "user_of_run.NUM_OF_RUN_ID"); 
    
        return response()->json(["data" => $empleado]);
    }


    public function omisiones(Request $request)
    {
       $id = $request->id;
       $fecha = $request->fecha;
       $fini = $request->fini;
       $ffin = $request->ffin;
       $codein = $request->codein;
       $tipoomi = $request->tipoomi;


       $fecha_mes = new Carbon($fini);
       $fecha_mes_inicio = new Carbon($fecha);
       $fecha_mes_fin = new Carbon($fecha);
       $fmesini = new Carbon($fini);
       $fmesfin = new Carbon($ffin);
       $dia =  $fecha_mes->day;
       
   
         if ($dia<=15){
             $fechaIni=$fmesini->firstOfMonth();
             $fechaFin= $fmesfin->firstOfMonth()->addDays(15); 
          
        } else{
           
                $fechaIni=$fmesini->firstOfMonth()->addDays(15);
                $fechaFin= $fmesfin->lastOfMonth(); 
        } 
   
       
        $parametros = Input::all();
        $omisiones = omisiones::where("userid","=",$id)
        ->whereBetween("CHECKTIME",[(substr($fecha_mes_inicio->firstOfMonth(),-19,10)."T".'00:00:01.000'),(substr($fecha_mes_fin->lastOfMonth(),-19,10)."T".'23:59:59.000')]) ;
        if($tipoomi !=''){
            $omisiones = $omisiones->where("CHECKTYPE","=",$tipoomi);
        }
                    

        $omisiones = $omisiones->get();
      
         $diasJustificados = DiasJustifica::where("userid","=",$id);        
  

        if($codein !=''){
                    $diasJustificados = $diasJustificados->where('DATEID','=',$codein);
                    if($codein == 22){
                        $diasJustificados = $diasJustificados                     
                        ->where("STARTSPECDAY","<=",$fechaFin)
                        ->where("ENDSPECDAY",">=",$fechaIni);
                    } 
                }
       $diasJustificados =  $diasJustificados->get();
        
        
     

        return response()->json(["omisiones" => $omisiones,"diasJustificados" => $diasJustificados]);
    }

    public function buscapases(Request $request)
    {
       $id = $request->id;
       //dd($id);
       $fecha = $request->fecha;       
       $fini = $request->fini;
       $fecha_mes_fin = new Carbon($fini);
       $fecha_mes_fin=$fecha_mes_fin->lastOfMonth();
       $fecha_mes_fin= str_replace(" ", "T", $fecha_mes_fin);
       $ffin = $request->ffin; 
       $codein =  $request->codein; 
       $tipo_trabajador = Usuarios::select('ur_id')->where("userid","=",$id)->first();  
      // dd($fini);
     $tipo_ur = $tipo_trabajador->ur_id;

      $pasesSalidas= DiasOtorgados::where("userid","=",$id)->where("STARTSPECDAY","<=",$fecha_mes_fin)
      ->where("ENDSPECDAY",">=",$fini)->where("DATEID","=","1")->get();

      $totalPases=0;
      $diff=0;
        foreach ($pasesSalidas as $i => $value) {
            
            if($value != null) {                                       
                
                // for ($i=0; $i < count($pasesSalidas); $i++) { 
                $final=$pasesSalidas[$i]['ENDSPECDAY'];
                $inicio=$pasesSalidas[$i]['STARTSPECDAY'];                
                $inicio = new Carbon($value->STARTSPECDAY);
                $final = new Carbon($value->ENDSPECDAY);
                $diff = $inicio->diffInMinutes($final);            
                $totalPases = $totalPases+ $diff;
                //  dd($inicio);
                //  }
                
            }
            
        }
        $totalPases=$totalPases/60;

         if($tipo_ur<=4){
            $fecha_inicial='2021-10-01';
            $fecha_final='2022-09-30';
        }else{ 
            $fecha_inicial='2022-01-01';
            $fecha_final='2022-12-31';
        }

        $EconomicoAnual= DiasOtorgados::where("userid","=",$id)->where("STARTSPECDAY","<=",$fecha_final)
        ->where("ENDSPECDAY",">=",$fecha_inicial)->where("DATEID","=","6")->get();
  
        $arreglo_dias_anual = array();    
        $num_anual = 0;   
      foreach ($EconomicoAnual as $key => $value) {           

         // $arreglo_dias[substr($value->STARTSPECDAY, 0,10)][] = $value;
          $inicioEconomicoAnual = new Carbon($value->STARTSPECDAY);
          $finEconomicoAnual = new Carbon($value->ENDSPECDAY);
          $diffEconomicoAnual = $inicioEconomicoAnual->diffInDays($finEconomicoAnual);            
          $arreglo_dias_anual[substr($inicioEconomicoAnual, 0,10)][] = $value;
          
          for ($i=0; $i < $diffEconomicoAnual; $i++) { 
             $arreglo_dias_anual[substr($inicioEconomicoAnual->addDays(), 0,10)][] = $value;
             
             //sdd($arreglo_dias_anual);
          } 
      
      } 
      $num_anual=count($arreglo_dias_anual);


      return response()->json(["pases"=>$totalPases,"EconomicoAnual"=>$num_anual]);
        

      
    }
    public function buscapermiso(Request $request)
    {
       $id = $request->id;
       $fecha = $request->fecha;
       //substr($request->fini,0,10);
       $fini = $request->fini;
       $ffin = $request->ffin;
       

     
       // dd(intval(strlen($ffin)));
       if(intval(strlen($fini)<19)) {$fini=$fini.":00";}
       if(intval(strlen($ffin)<19))  {$ffin=$ffin.":00";}
        
      //  dd($fini."T00:00:00.000");//whereBetween($fini,["STARTDATE","ENDDATE"])
       $horario = UsuarioHorario::with("detalleHorario.reglaAsistencia")->where("USERID","=",$id)->orderBY("id","DESC")->first();
      //dd($horario['detalleHorario'][0]->SDAYS);
      $h_inicio = (substr($horario['detalleHorario'][0]['reglaAsistencia']->StartTime,11,12));
      $h_termino = (substr($horario['detalleHorario'][0]['reglaAsistencia']->EndTime,11,12));

      //dd($ffin);
      $pases = DiasOtorgados::where("userid","=",$id)->where("STARTSPECDAY","<=",$ffin)
      ->where("ENDSPECDAY",">=",$fini)->where("DATEID","=","1")
      ->get();
         $diasJustificados = DiasOtorgados::where("userid","=",$id)->where("STARTSPECDAY","<=",$ffin)
         ->where("ENDSPECDAY",">=",$fini)->where("DATEID","<>","1")

        
         /*->where("ENDSPECDAY","<=", $ffin.'T23:59:59')
            ->where(function($a)use($fini,$ffin){
            $a->where("STARTSPECDAY", ">=", $fini.'T00:00:00')
            ->orWhere("ENDSPECDAY", ">=", $fini.'T00:00:00');
         })*/->get();       
         $fecha_mes = new Carbon($fini);
        
         $fecha_mes_inicio = new Carbon($fini);
         $fecha_mes_fin = new Carbon($fini);
         
         $fecha_mes_inicio=$fecha_mes_inicio->firstOfMonth();
         $fecha_mes_fin=$fecha_mes_fin->lastOfMonth();
       
         $fecha_mes_inicio= str_replace(" ", "T", $fecha_mes_inicio);
         $fecha_mes_fin= str_replace(" ", "T", $fecha_mes_fin);

        // dd($fecha_mes_fin);
         //dd($fecha_mes_fin."--------".$fecha_mes_inicio);
                  
                 $diasEconomicoMensual= DiasOtorgados::where("userid","=",$id)                
                  ->where("STARTSPECDAY","<=",$fecha_mes_fin)
                 ->where("ENDSPECDAY",">=",$fecha_mes_inicio)                 
                 ->where("DATEID","=","6")
                ->get(); 
                   
         
                   $arreglo_dias = array();    
                   $num = 0;   
                 foreach ($diasEconomicoMensual as $key => $value) {          
          
                       
                        // $arreglo_dias[substr($value->STARTSPECDAY, 0,10)][] = $value;
                        $inicio = new Carbon($value->STARTSPECDAY);
                        $fin = new Carbon($value->ENDSPECDAY);
                        $diff = $inicio->diffInDays($fin);            
                        $arreglo_dias[substr($inicio, 0,10)][] = $value;
                        
                        for ($i=0; $i < $diff; $i++) { 
                            $arreglo_dias[substr($inicio->addDays(), 0,10)][] = $value;
                            
                        
                        } 
                    //  dd("inicio ".$inicio."  fin".$fin );
                   /*  $dia_mes_inicial = new Carbon($arreglo_dias[substr($inicio, 0,10)][count($arreglo_dias)-1]->STARTSPECDAY);
                    $primer_dia_mes= substr($arreglo_dias[substr($inicio, 0,10)][count($arreglo_dias)-1]->ENDSPECDAY,8,2);
                    $dia_mes_final =    new Carbon($arreglo_dias[substr($inicio, 0,10)][count($arreglo_dias)-1]->ENDSPECDAY);
                    $dia_compara =substr($dia_mes_final,0,10)." 11:00:00";
                    if($dia_mes_inicial->isLastMonth()==true && $primer_dia_mes=="01" && $dia_mes_final->lessThanOrEqualTo($dia_compara)){
                        $num=0;
                     }else{  
                     $num=count($arreglo_dias);
                     } */
                    } 
                 
                  
                //dd($dia_mes_inicial->isLastMonth());
                // dd($dia_mes_inicial."    fin ".$dia_mes_final." primer: ".$primer_dia_mes." diacompara: ".$dia_compara);
                  
                 $num=count($arreglo_dias);
                // dd($num); 

        return response()->json(["permisos" => $diasJustificados,"horario" => $horario,"toteconomico"=>$num,"pases"=>$pases]);
    }


    public function buscaEconomico(Request $request)
    {
       $id = $request->id;
       $fecha = $request->fecha;
       $fini = $request->fini;
       $ffin = $request->ffin;
       $tipotra = $request->tipotra;
       $fecha_mes = new Carbon($fini);
       $fecha_mes_inicio = new Carbon($fini);
       $fecha_mes_fin = new Carbon($fini);
       $fmesini = new Carbon($fini);
       $fmesfin = new Carbon($ffin);
       $dia =  $fecha_mes->day;
  

       
       

        $fecha_mes_inicio=$fecha_mes_inicio->firstOfMonth();
        $fecha_mes_fin=$fecha_mes_fin->lastOfMonth();
        $fecha_mes_inicio= str_replace(" ", "T", $fecha_mes_inicio);
        $fecha_mes_fin= str_replace(" ", "T", $fecha_mes_fin);
        //dd($fecha_mes_fin."--------".$fecha_mes_inicio);
         
        $diasEconomicoMensual= DiasOtorgados::where("userid","=",$id)
        //->whereBetween("CHECKTIME",[(substr($fecha_mes_inicio->firstOfMonth(),-19,10)."T".'00:00:01.000'),(substr($fecha_mes_fin->lastOfMonth(),-19,10)."T".'23:59:59.000')])
         ->where("STARTSPECDAY","<=",$fecha_mes_fin)
        ->where("ENDSPECDAY",">=",$fecha_mes_inicio)
        ->where("DATEID","=","6")
       ->get(); 
          

          $arreglo_dias = array();    
          $num = 0;   
        foreach ($diasEconomicoMensual as $key => $value) {           
 
           // $arreglo_dias[substr($value->STARTSPECDAY, 0,10)][] = $value;
            $inicio = new Carbon($value->STARTSPECDAY);
            $fin = new Carbon($value->ENDSPECDAY);
            $diff = $inicio->diffInDays($fin);            
            $arreglo_dias[substr($inicio, 0,10)][] = $value;
            
            for ($i=0; $i < $diff; $i++) { 
               $arreglo_dias[substr($inicio->addDays(), 0,10)][] = $value;
               
              
            } 
         
        } 
        $num=count($arreglo_dias);
        //return  $arreglo_dias;

       
        return response()->json(["economicoMensual" => $arreglo_dias,"toteconomico"=>$num]);

      // $diasJustificados =  $diasJustificados->get();

        //return response()->json(["economicoAnual" => $diasEconomicoAnual,"economicoMensual" => $diasEconomicoMensual]);
    }

    public function verificaHorario(Request $request)
    {
       $id = $request->id;
       $fecha = $request->fecha;
       //substr($request->fini,0,10);
       $fini = $request->fini;
       $ffin = $request->ffin;
       // dd(intval(strlen($ffin)));
       if(intval(strlen($fini)<19)) {$fini=$fini.":00";}
       if(intval(strlen($ffin)<19))  {$ffin=$ffin.":00";}
        
      //  dd($fini."T00:00:00.000");//whereBetween($fini,["STARTDATE","ENDDATE"])
       $horario_val = UsuarioHorario::with("detalleHorario.reglaAsistencia")->where("USERID","=",$id)->where("STARTSPECDAY","<=",$ffin)
       ->where("ENDSPECDAY",">=",$fini)->orderBY("id","DESC")->get();
          
          

      // $diasJustificados =  $diasJustificados->get();

        return response()->json(["horario_val" => $horario_val]);
    }



    public function permisos_empleados(Request $request){
        $id = $request->idempleado;
   
        $tipo_ur = 5;//$tipo_trabajador->ur_id;

        if($tipo_ur<=4){
            $fecha_inicio='2021-10-01';
            $fecha_fin='2022-09-30';
        }else{ 
            $fecha_inicio='2022-01-01';
            $fecha_fin='2022-12-31';
        }

        $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'dias_otorgados.siglas', 
        /* 'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
        $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
    },  */
    
    'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
       
       $query->whereRaw("( ENDDATE >= '". $fecha_inicio."' and  STARTDATE <= '".$fecha_fin."')");
       
    }/* , 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
        $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
    } */, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){
        $query->where("ENDSPECDAY","<=", $fecha_fin)                   
               ->where("STARTSPECDAY", ">=", $fecha_inicio)
                    ->orWhere("ENDSPECDAY", ">=", $fecha_inicio); 
     
    }])
    ->Where("USERID","=",$id)->first(); 

    //$horarios_periodo = $this->ordernarHorarios($empleados->horarios); 

   return response()->json(["permisos" => $empleados->dias_otorgados,"horario" => $empleados->horarios]);   
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $USERID)
    {
        try {
                $buscaBase=DB::table("catalogo_bases")->where("id","=",auth()->user()['base_id'])->first();
                
                $buscaBase=$buscaBase->descripcion;
       
                $registro= Usuarios::findOrFail($USERID);  
           /*  if($buscaBase=="gomezmaza"){
                $registro->Badgenumber=  $request->biome;
            }   */        
                $registro->Name = $request->name;
                $registro->Gender = $request->sexo;
                $registro->TITLE = $request->rf;        
                $registro->PAGER = $request->codigo;
                $registro->BIRTHDAY = $request->fecnac;
                $registro->HIREDDAY=$request->fechaing;
                $registro->street=$request->street;
                $registro->CITY=$request->city;          
                $registro->FPHONE=$request->clues;
                $registro->ATT=$request->mmi;
                $registro->INLATE= $request->interino;
               // $registro->DEFAULTDEPTID=$request->tipotra;   
                $registro->ur_id=$request->tipotra;          
                $registro->MINZU=$request->area;           
                $registro->save(); 

                if ($request->code!=''){
                $user_hora = new UsuarioHorario;
                $user_hora->USERID=$USERID;
                $user_hora->NUM_OF_RUN_ID=$request->code;
                $user_hora->STARTDATE=$request->ini_fec;
                $user_hora->ENDDATE=$request->fin_fec;
                $user_hora->ISNOTOF_RUN=0;
                $user_hora->ORDER_RUN=0;
                $user_hora->save();
                }
            
            
                return response()->json(['mensaje'=>"Exitoo!! Los Datos se han Modificado  correctamente!!!"]); 
        }
        catch (\Exception $e) {            
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }

    public function elimina_horario($id)
    {
       // dd("HOLA REPENDEJO");

         try {
            
            $registro=UsuarioHorario::where('id','=',$id)->delete();   
            //$result = $registro->delete();

            if($registro){
                return response()->json(['mensaje'=>'Registro Eliminado']);
                
            }
        } 
        catch (\Exception $e) {
            
            return Response::json(['estas rependejo' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
            } 
    }
    public function modifica_horario_empleado(Request $request, $idhorario)
    {
        
        try {

          
            $user_hora= UsuarioHorario::findOrFail($idhorario);  
            
            $user_hora->NUM_OF_RUN_ID=$request->code;
            $user_hora->STARTDATE=$request->ini_fec;
            $user_hora->ENDDATE=$request->fin_fec;            
            $user_hora->save();      
            return response()->json(['data'=>$user_hora]);
            //dd(DB::getQueryLog());
        } 
        catch (\Exception $e) {
            
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
            }

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
