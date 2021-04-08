<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests\EmpleadoRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon, DB;
use App\Models\Usuarios;
use App\Models\Departamentos;
use App\Models\UsuarioHorario;
use App\Models\Festivos;
use App\Models\Horario;
use App\Models\TiposIncidencia;


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

         $zk = DB::connection('ZK');
         $bs = DB::connection('BS');  
        $name = $request->get('buscar');  

        /* $usuarios = $bs->table('USERINFO')->where('TITLE', 'BEBA620313GI5')->first();

        if($usuarios){

            return response()->json(["usuarios" => $usuarios]);

        }

        return response()->json(["usuarios" => $usuarios]); */

        $idcap = Auth::id();          

        /*  $usuarios =  $zk->table("userinfo")
            ->join("USER_OF_RUN", "USER_OF_RUN.USERID", "=", "userinfo.USERID")
            ->join("NUM_RUN_DEIL","NUM_RUN_DEIL.NUM_RUNID", "=", "USER_OF_RUN.NUM_OF_RUN_ID")->where('userinfo.status', '=', 0)->select("userinfo.*");  */
        $usuarios = Usuarios::with("horarios.detalleHorario")->where('status', '=', 0);        
     
      //  $usuarios = Usuarios::with("horarios.detalleHorario")->where('status', '=', 0);
        //berriozabal
        if ($idcap==2){
           $usuarios=$usuarios->where('FPHONE','=','CSSSA009203'); 
        } 

        //
        if ($idcap==11){
            $usuarios=$usuarios->where('FPHONE','=','CSSSA017213'); 
         } 
         if ($idcap==14){
            $usuarios=$usuarios->where('FPHONE','=','CSSSA009162'); 
         }
         if($name !='')
         $usuarios=$usuarios->Where(function($query2)use($name){
            $query2->where("Name",'LIKE','%'.$name.'%')
                    ->orWhere("TITLE",'LIKE','%'.$name.'%')
                    ->orWhere("Badgenumber",'=',$name);
        });

        

 //DB::enableQueryLog(); 
         //CSSSA009162
        $usuarios = $usuarios->where("HOLIDAY",'<>',0)->orderBy('USERID','DESC')->paginate();
        $incidencias = TiposIncidencia::orderBy('LeaveName','ASC')->whereNotIn('LeaveId', [4,5,7,9,18,28])->get();  
        $departamentos = Departamentos::where("DEPTID","<>",1)->get();   
        $festivos = Festivos::get();   
        
        //print_r($usuarios);
        return response()->json(["usuarios" => $usuarios,"incidencias" => $incidencias,"departamentos" => $departamentos,"festivos" => $festivos]); 
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


    public function tipoincidencia(Request $request)
    {
        
       /*  if($request->get('bh'))
        {  */ 
        $bi = $request->get('bi');
        $data_in = TiposIncidencia::orderBy('LeaveName','ASC')->where("LeaveName",'LIKE','%'.$bi.'%')->whereNotIn('LeaveId', [4,5,7,9,18,28])->get();          
        
      
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
       
           
                $max=Usuarios::max('USERID');
                $maxid=Usuarios::select('Badgenumber as num_max')
                
                ->where('USERID','=',$max)
                ->get();         
                $maxid=($maxid[0]->num_max)+1;  
                    $registro = new Usuarios;
                    $registro->Badgenumber= $maxid;
                    $registro->Name = $request->name;
                    $registro->Gender = $request->sexo;
                    $registro->TITLE = $request->rf;        
                    $registro->PAGER = $request->codigo;
                    $registro->BIRTHDAY = $request->fecnac;
                    $registro->HIREDDAY=$request->fechaing;
                    $registro->street=$request->street;
                    $registro->CITY=$request->city;
                    $registro->STATE=0;
                    $registro->ATT=$request->mmi;
                    $registro->INLATE= $request->interino;
                    $registro->ZIP= 1;
                    $registro->FPHONE=$request->clues;
                    $registro->DEFAULTDEPTID=$request->tipotra;            
                    $registro->MINZU=$request->area;   
                    $registro->save();
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
        $empleado = Usuarios::with("horarios.detalleHorario")->find($id);
        //->join("num_run", "num_run.NUM_RUNID", "=", "user_of_run.NUM_OF_RUN_ID"); 
    
        return response()->json(["data" => $empleado]);
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
           $registro->DEFAULTDEPTID=$request->tipotra;            
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
           /*  if ($request->code!=''){              

                $maxhora=UsuarioHorario::findOrFail($USERID)->max('ENDDATE');                           
                $modif_hora = UsuarioHorario::findOrFail($USERID)->where('ENDDATE','=',$maxhora)->first();    

                    $inifec = new Carbon($request->ini_fec);
                    
                        if ($modif_hora->ENDDATE>=$inifec){
                             //DB::enableQueryLog(); 
                            $inifec->subDay();    
                           // dd(substr($inifec, 0).".000"); 
                            $modif_hora->ENDDATE=substr($inifec, 0).".000"; 
                            DB::table('USER_OF_RUN')                            
                            ->where('USERID','=',$USERID)
                            ->where('ENDDATE','=',$maxhora)
                            ->update(['ENDDATE' => substr($inifec, 0).".000" ]);

                           // dd(DB::getQueryLog()); 
                            
                        }
                      
                       // 2020-11-29 00:00:00.0 UTC (+00:00)
                        
                       // dd( $modif_hora);
                
                
                   $user_hora = new UsuarioHorario;
                   $user_hora->USERID=$USERID;
                   $user_hora->NUM_OF_RUN_ID=$request->code;
                   $user_hora->STARTDATE=$request->ini_fec;
                   $user_hora->ENDDATE=$request->fin_fec;
                   $user_hora->ISNOTOF_RUN=0;
                   $user_hora->ORDER_RUN=0;
                   $user_hora->save();                

                   // $ss="Exitoo!! Los Datos se han Modificado  correctamente!!!";



             
               
                    
            }
            */
          
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


    public function modifica_horario_empleado(Request $request, $idhorario)
    {
        
        try {

            //DB::enableQueryLog(); 
            //$user_hora= UsuarioHorario::where('id','=',$idhorario);  
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
}
