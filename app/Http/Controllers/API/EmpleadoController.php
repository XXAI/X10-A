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
use App\Models\Festivos;
use App\Models\Omisiones;
use App\Models\DiasJustifica;
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
       
    //    if(auth()->user()['is_superuser']==1){

          
            // $buscaBase=DB::table("catalogo_bases")->where("id","=",auth()->user()['base_id'])->first();
            //dd($buscaBase);
          /*  $namedb=$buscaBase->base;
            \Config::set('database.connections.dinamica.database',$namedb); // Asigno la DB que voy a usar
            $conexion = DB::connection('dinamica'); //Asigno la nueva conexión al sistema.  */
      
     

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
    
        $usuarios = Usuarios::with("horarios.detalleHorario","dias_justificados")->where('status', '=', 0)->WHEREIN("FPHONE", $arreglo_clues);        
     
      
         if($name !='')
         $usuarios=$usuarios->Where(function($query2)use($name){
            $query2->where("Name",'LIKE','%'.$name.'%')
                    ->orWhere("TITLE",'LIKE','%'.$name.'%')
                    ->orWhere("Badgenumber",'=',$name);
        });

        

 //DB::enableQueryLog(); 
         //CSSSA009162
        $usuarios = $usuarios->where("HOLIDAY",'<>',0)->orderBy('USERID','DESC')->paginate(200);
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
        $data = Horario::select('NUM_RUNID','NAME')->where("NAME",'LIKE','%'.$bh.'%')->get();        
        
      
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

   
       // if($request->ajax()){
                $buscaBase=DB::table("catalogo_bases")->where("id","=",auth()->user()['base_id'])->first();
                $edificio = Edificios::select("DEPTID")->where("type","=",$request->clues)->first();
                $edificio = $edificio['DEPTID'];
                $buscaBase=$buscaBase->descripcion;
               // dd($buscaBase);
                $max=Usuarios::max('USERID');
                if(isset($max)){
                $maxid=Usuarios::select('Badgenumber as num_max')
                
                ->where('USERID','=',$max)
                ->get();   
                $maxid=($maxid[0]->num_max)+1;}
                else{
                    $maxid=1; 
                }
                 
                    $registro = new Usuarios;
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
       
    //   dd($dia);
         if ($dia<=15){
             $fechaIni=$fmesini->firstOfMonth();
             $fechaFin= $fmesfin->firstOfMonth()->addDays(15); 
           // dd("dia menor de 15");
        } else{
           // dd("dia mayor o igual  de 16");
                $fechaIni=$fmesini->firstOfMonth()->addDays(15);
                $fechaFin= $fmesfin->lastOfMonth(); 
        } 
     // dd(substr($fechaIni,-19,10)."T".'00:00:01.000');
       
        $parametros = Input::all();
        $omisiones = omisiones::where("userid","=",$id)
        ->whereBetween("CHECKTIME",[(substr($fecha_mes_inicio->firstOfMonth(),-19,10)."T".'00:00:01.000'),(substr($fecha_mes_fin->lastOfMonth(),-19,10)."T".'23:59:59.000')]) ;
        if($tipoomi !=''){
            $omisiones = $omisiones->where("CHECKTYPE","=",$tipoomi);
        }
                    

        $omisiones = $omisiones->get();
       // dd($omisiones)
 /*   ->where("STARTSPECDAY","<=",(substr($fechaFin,-19,10)."T".'23:59:59.000'))
                        -> where("ENDSPECDAY",">=",(substr($fechaIni,-19,10)."T".'00:00:01.000')) ; */

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
        
        
      //  dd($omisiones);

        return response()->json(["omisiones" => $omisiones,"diasJustificados" => $diasJustificados]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
       
            $registro= Usuarios::findOrFail($USERID);            
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
           //$registro->DEFAULTDEPTID=$request->tipotra;   
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
