<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
//use APP\Http\Middleware\CheckDataBase;
use Carbon\Carbon, DB, PDF, View, Dompdf\Dompdf;  ;
use App\Models\TiposIncidencia;
use App\Models\Usuarios;
use App\Models\UsuarioHorario;
use App\Models\FinSemanaFestivo;
use App\Models\DiasOtorgados;
use App\Models\User;


//use App\Models\ReglasHorario;
use App\Models\Festivos;
use App\Models\Horario;
use Illuminate\Support\Facades\Auth;
//use Closure;

class reporteController extends Controller
{


    public function imprimirTarjeta(Request $request)
    {
        $parametros = Input::all();
        $usuario = Auth::user();

        $diaActual = Carbon::now()->isoFormat('dddd D \d\e MMMM \d\e\l Y');
        
      
       
        
        //return $usuario;
        $asistencia = $this->consulta_checadas($request);
       // $datos = $asistencia;
     //   dd(($asistencia));
        $pdf = PDF::loadView('empleados//tarjeta', ['asistencia' => $asistencia, 'leyenda' => $parametros['leyenda'], 'hoy' => $diaActual]);
        //$pdf = PDF::loadView('empleados//tarjeta', ['empleados' => $asistencia, 'usuario' => $usuario, "config" => $datos_configuracion]);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(['isPhpEnabled' => true ,'isRemoteEnabled' => true]);
        
        return $pdf->stream('reporte-asistencia.pdf');
    }

    public function consulta_checadas(Request $request)
    {
        $parametros = Input::all();
        $arreglo_fecha = array();
        $fecha_actual = Carbon::now();
        $anio_actual = $fecha_actual->year;
        $mes_actual = $fecha_actual->month;
        $dia_actual = $fecha_actual->day;
        $Rfc = $request->rfc;
        $sol = $request->soli;
        $impre = $request->impre;
        $htra=0;
        $fecha_inicio='2019-10-01T00:00:00.000';
        $fecha_fin='2200-12-31T23:59:59.000';
        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;
      
        $Rfc = str_replace("(", "/", $Rfc);
        $Rfc = str_replace(" ", "+", $Rfc);
        if (!isset($parametros['id'])){
            $desc = $this->decrypt($Rfc);
            
        }
        else
        {
            $desc =$parametros['id'];
        }

        if(is_null(auth()->user())){
            $buscaBase=DB::table("tablaBases")->where("rfc","=",$desc)->first();
            //dd($buscaBase);
            $namedb=$buscaBase->base;
            \Config::set('database.connections.dinamica.database',$namedb); // Asigno la DB que voy a usar
            $conexion = DB::connection('dinamica'); //Asigno la nueva conexión al sistema. 
        }else{
            $conexion = DB::connection('dinamica');
            
        }
       // dd($namedb);
        if(isset($namedb)) $nombrebase=$namedb;
        if(isset($conexion)) $nombrebase = $conexion->getDatabaseName(); 

        $fecha_view_inicio = Carbon::now()->startOfMonth();
        $fecha_view_fin    = Carbon::now();

        if($inicio == null){
            $f_ini = Carbon::now()->startOfMonth();
            $f_fin = Carbon::now()->addDays(1);
            $ff_fin = Carbon::now();
            $inicio = date("Y-m-")."01";
            $fin = date("Y-m-d");
        }else{
            $f_ini= new Carbon($inicio);
            $f_fin = new Carbon($fin);
            $ff_fin = new Carbon($fin);
            $f_fin = $f_fin->addDays(1);

            $fecha_view_inicio = new Carbon($inicio);
            $fecha_view_fin    = new Carbon($fin);
        }
        $f_inicio_mes=new Carbon($inicio);
        $f_fin_mes=new Carbon($inicio);
        $f_inicio_mes=$f_inicio_mes->startOfMonth();
        $f_fin_mes=$f_fin_mes->endofMonth();
        $f_inicio_mes= str_replace(" ", "T", $f_inicio_mes);
        $f_fin_mes= str_replace(" ", "T", $f_fin_mes);

        //Se sacaron variables de inicio para las consultas en la base de datos
       
         //$validacion= Usuarios::with("horarios.detalleHorario")->where("userinfo.TITLE", "=",  $desc)->first();
       //dd($conexion);
            $validacion = $conexion->table("userinfo")
            ->join("USER_OF_RUN", "USER_OF_RUN.USERID", "=", "userinfo.USERID")
            ->join("NUM_RUN_DEIL","NUM_RUN_DEIL.NUM_RUNID", "=", "USER_OF_RUN.NUM_OF_RUN_ID")
            ->where("userinfo.TITLE", "=",  $desc)->first();
          //  dd($validacion);
          
       // $checa_dias = $conexion->table("user_speday")
        $checa_dias = $conexion->table("user_speday")
        ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
        ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")                            
        ->where("TITLE", "=",  $desc)   
        ->whereBetween(DB::RAW("DATEPART(DW,STARTSPECDAY)"),[2,6])
        ->groupBy('leaveclass.LeaveId','leaveclass.LeaveName')           
        ->select("leaveclass.LeaveName as Exepcion"                            
        ,'leaveclass.LeaveId AS TIPO'
        ,DB::RAW("count(leaveclass.LeaveId) as total")                               
        )           
        ->get();

       
                
            $vac19_1=0;
            $vac20_1=0;
            $vac20_2=0;
            $vac19_2=0;
            $vac18_1=0;
            $vac18_2=0;
            $diaE=0;
            $vacEx=0;
            $vacMR=0;
            $pagoGuardia=0;
            $diaE=0;
            $ono=0;
        foreach($checa_dias as $tipos){
            switch($tipos->TIPO){
                
                case 2:                       
                    $vac19_1=$tipos->total;                                        
                    break;                                  
                
               /* case 6:                                       
                    $diaE=$tipos->total;
                    break;*/
                
                case 10:                                        
                    $ono=$tipos->total;
                    break;
                case 11:                                        
                    $vac18_1=$tipos->total;
                    break;
                case 12:                                       
                    $vac18_2=$tipos->total;
                    break;
                case 13:                                        
                    $vac19_2=$tipos->total;
                    break;                                    
                case 15:                                        
                    $vacMR=$tipos->total;
                    break;
                case 16:                                        
                    $vacEx=$tipos->total;
                    break;
                case 22:                                        
                    $pagoGuardia=$tipos->total;
                    break;

                case 30:                                        
                    $vac20_1=$tipos->total;
                    break;         
                case 32:                                        
                    $vac20_2=$tipos->total;
                    break;         
                default:
                    $impr="";
                    break;
            }                                                           
        }
        $buscaHorario=$conexion->table("USER_OF_RUN")                  
                ->where("USERID", "=",  $validacion->USERID)                                 
                ->where("STARTDATE","<=",substr($ff_fin, 0, 10).'T23:59:59.000')
                ->where("ENDDATE",">=",substr($f_ini, 0, 10).'T00:00:01.000')   
                ->orderBy("ENDDATE")   
                ->select("USERID",
                        "NUM_OF_RUN_ID",
                        DB::RAW("CONVERT(nvarchar(10), STARTDATE,120) as STARTDATE"),
                        DB::RAW("CONVERT(nvarchar(10), ENDDATE,120) as ENDDATE"),
                        "ORDER_RUN")                            
                ->get();                   
                   
                $arreglo_reglas=array();  
                $ind=0;
                foreach($buscaHorario as $key => $horario){                        
                    $empleado = $conexion->table("user_of_run")                            
                    ->join("num_run", "num_run.NUM_RUNID", "=", "user_of_run.NUM_OF_RUN_ID")
                    ->join("num_run_deil", "num_run_deil.NUM_RUNID", "=", "num_run.NUM_RUNID")
                    ->join("schclass", "schclass.schClassid", "=", "num_run_deil.SCHCLASSID")
                    ->select("num_run.name as horario"
                            ,DB::RAW("CONVERT(nvarchar(10),user_of_run.STARTDATE,120) as fecha_inicial")
                            ,DB::RAW("CONVERT(nvarchar(10),user_of_run.ENDDATE,120) as fecha_final")
                            ,"num_run_deil.SDAYS as dia"
                            ,"schclass.schName as Detalle_Horario"
                            ,DB::RAW("CONVERT(nvarchar(5), schclass.StartTime,108) as HoraInicio")
                            ,DB::RAW("CONVERT(nvarchar(5), schclass.EndTime,108) as HoraFin")
                            ,"schclass.LateMinutes as Tolerancia"
                            ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckInTime1, 108) as InicioChecarEntrada")
                            ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckInTime2, 108) as FinChecarEntrada")
                            ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckOutTime1, 108) as InicioChecarSalida")
                            ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckOutTime2, 108) as FinChecarSalida")
                            ,"schclass.CheckOutTime2 as prueba"
                            ,"schclass.schClassId as idH"
                            ,"num_run_deil.SDAYS as diaEnt"
                            ,"num_run_deil.EDAYS as diaSal")                                                             
                            ->where("user_of_run.USERID", "=",  $validacion->USERID)
                            ->where("USER_OF_RUN.NUM_OF_RUN_ID","=",$horario->NUM_OF_RUN_ID)
                            
                            ->get();
                           
                        $ind = count($arreglo_reglas);
                        $arreglo_reglas[$ind]['horario'] = $buscaHorario[$key];
                        $arreglo_reglas[$ind]['dias'] = $empleado; 
                                                
                        
                }     
                
                
/* 

                unset($empleados);
                $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'dias_otorgados.siglas', 'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
                    
                }, 'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
                    $query->where("STARTDATE", "<=", $fecha_inicio);//->where("ENDDATE", ">=", $fecha_fin.'T00:00:00');
                }, 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
                    $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
                }, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){
                   $query->where("ENDSPECDAY","<=", $fecha_fin)                   
                           ->where("STARTSPECDAY", ">=", $fecha_inicio)
                                ->orWhere("ENDSPECDAY", ">=", $fecha_inicio);   
                }])               
                         
                ->get();
                 print_r($empleados); 
                return $empleados; */

                $diaEconomicoanual = 0;

     /*    $diaEconomicoanual = DiasOtorgados::where("USERID", "=",  $validacion->USERID)->where("DATEID", "=",  6)->get();
        
        $dias_economicos    =  $this->dias_economicos($diaEconomicoanual);
        //dd($dias_economicos); */
                       
        $resumen2 = DiasOtorgados::where("USERID", "=",  $validacion->USERID)->get();  
        //->groupBy("USER_SPEDAY.DATEID","USER_SPEDAY.USERID","USER_SPEDAY.STARTSPECDAY","USER_SPEDAY.ENDSPECDAY","USER_SPEDAY.YUANYING","USER_SPEDAY.DATE","USER_SPEDAY.captura_id","USER_SPEDAY.id","USER_SPEDAY.incidencia_id")
          
        
        $dias_otorgados     =  $this->dias_otorgados($resumen2);
        //dd($dias_otorgados);
        


      //return  $arreglo_dias_otorgados;

        //return $resumen2;
        for($tot_hora=0;$tot_hora<=$ind; $tot_hora++){                       
            $arreglo_dias = array();

            for($dias = 1; $dias<8; $dias++)
                $arreglo_dias[$dias] = null;
                

            foreach ($arreglo_reglas[$tot_hora]['dias'] as $key => $value) {
                $arreglo_dias[$value->dia] = $value;
            }
            $arreglo_reglas[$tot_hora]['dias']=$arreglo_dias;
        }
        
           
        $diff= $f_ini->diffInDays($f_fin)+1;
        $fecha_pivote = $f_ini;

        $asistencia = array();
        $rm=0;
        $rme=0;        
        $oE=0;
        $oS=0;
        $falta=0;
        $ps=0;
       $omision=0;
       
       
            $indice = 0;
            while($fecha_pivote->diffInDays($f_fin)  > 0)
            {                
                
                $fecha_evaluar = $fecha_pivote;              
                $indice_reglas = 0;
                $var_reglas=array();
                $bandera=0;
                while(count($arreglo_reglas) > $indice_reglas && $bandera==0)
                {
                    $fecha_regla=$arreglo_reglas[$indice_reglas]['horario']->ENDDATE;
                   
                    $fecha_regla=new Carbon($fecha_regla);
                    $fecha_regla->addDays(1);
                    
                    if($fecha_evaluar->lessThan($fecha_regla)){
                        $var_reglas=($arreglo_reglas[$indice_reglas]['dias']);  
                        
                    
                        $bandera=1;
                    } 
                    $indice_reglas++;
               
                }

                $diafest = FinSemanaFestivo::with("festivo_finsemana")->where("COMETIME","=",$fecha_evaluar)->where("USERID","=",$validacion->USERID)->first();
               // dd($diafest);
                $festivo=0;
                $regla_festivo_fin_Semana = "";
                if($diafest != null)
                {
                    foreach ($var_reglas as $key => $value) {
                       
                        if($value != null)
                        {
                            
                            $festivo=1;
                            $var_reglas[$fecha_evaluar->dayOfWeekIso]  = $value;
                          
                        }
                    }
                }
               // return response()->json(["data" => $diafest]);    

             //  $festivos   = Festivos::where("STARTTIME", "=", $fecha_evaluar)->get();
             $diatrab=0;

              // dd($var_reglas[$fecha_evaluar->dayOfWeekIso]);
              //  $arreglo_festivos = array();
                //if(count($festivos) > 0){ $arreglo_festivos = $this->festivos($festivos); }
              //dd($festivos);
                if(isset($var_reglas[$fecha_evaluar->dayOfWeekIso]) || $diafest != '' || $diafest != null )
                {

         
              
                        $asistencia[$indice]['numero_dia'] = $fecha_evaluar->dayOfWeekIso;
                        $asistencia[$indice]['validacion'] = 1;
                      
                        $jorIni=new Carbon($var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraInicio);
                        $jorFin=new Carbon($var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraFin);
                       
                        if(substr($var_reglas[$fecha_evaluar->dayOfWeekIso]->horario,0,2)<>"HT")
                            $htra=$jorFin->diffInRealHours($jorIni);
                        else
                            $htra=0;


                            
                            $faltaxmemo = 0;
                            $asistencia[$indice]['fecha'] = $fecha_evaluar->format('Y-m-d');
                        
                            $fecha_eval = $asistencia[$indice]['fecha'];
                            
                            
                            $inicio_entra=$fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarEntrada.":00.000";                   
                           // $final_entra=$fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarEntrada.":00.000";
                            $final_entra=new Carbon($fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarEntrada.":00.000");
                            
                            $final_entra_fuera=$final_entra;
                          //  dd($final_entra);
                        //      
                            $diatrab=$var_reglas[$fecha_evaluar->dayOfWeekIso]->diaSal-$var_reglas[$fecha_evaluar->dayOfWeekIso]->diaEnt;
                            $inicio_sal=$fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarSalida.":00.000"; 
                            $final_sal=$fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida.":00.000";
                            $final_sal=new carbon($fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida.":00.000");


                            if($nombrebase == 'gomezmaza'){
                                $final_entra->addMinute();  
                                $final_sal->addMinute(); 
                            }
                           // $final_entra->addHour();
                            $final_entra= str_replace(" ", "T", $final_entra);
                            $final_sal= str_replace(" ", "T", $final_sal);
                            
                            $final_entra= $final_entra.".000";
                            $final_sal= $final_sal.".000";


                      //  dd($value);
                           $asistencia[$indice]['jorini'] = $fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraInicio.":00.000";
                           $asistencia[$indice]['jorfin'] = $fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraFin.":00.000";
                           $asistencia[$indice]['jorfin'] = new carbon($asistencia[$indice]['jorfin']);
                           if ($diatrab !=0 ){
                            $asistencia[$indice]['jorfin']->addDays();
                           }
                           $asistencia[$indice]['jorfin'] = str_replace(" ", "T", $asistencia[$indice]['jorfin']);
                         
                        //   dd( substr($asistencia[$indice]['jorfin'],11,10));


                           //$inicio_entra_fuera=$fecha_eval."T".'00:00:01.000';
                           $inicio_entra_fuera = new carbon($fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarEntrada.":00.000"); 
                             
                           $inicio_entra_fuera->subMinutes(30);
                           $inicio_entra_fuera= str_replace(" ", "T", $inicio_entra_fuera);
                          // dd($inicio_entra_fuera);
    
    
                            $inicio_sal_fuera=new Carbon($fecha_eval." ".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarSalida.":00.000"); 
                            $final_sal_fuera=new Carbon($fecha_eval." ".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida.":00.000");
                            $final_sal_fuera2=new Carbon($fecha_eval." ".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida.":00.000");
                            $pase_ss=$inicio_sal_fuera;
                           // $final_sal_fuera=$fecha_eval."T".'23:59:59.000';  
                            //$inicio_sal_fuera->subHours(2);
                          
                          //  $final_entra_fuera=$inicio_sal_fuera->subHours(2);
                            $final_entra_fuera->addMinutes(90);
                            $final_sal_fuera2->addHours(2);
                            $final_entra_fuera= str_replace(" ", "T", $final_entra_fuera);
                            $inicio_sal_fuera= str_replace(" ", "T", $inicio_sal_fuera);
                            $final_sal_fuera= str_replace(" ", "T", $final_sal_fuera);
                            $final_sal_fuera2= str_replace(" ", "T", $final_sal_fuera2);
                          // dd($inicio_sal_fuera);
                            $trab=$diatrab;
                            if ($diatrab!=0 || $festivo==1 )
                                {
                                   
                                    $diatrab=1;                                    
                                    $inicio_sal=new Carbon($fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarSalida.":00.000");
                                    $final_sal=new Carbon($final_sal);         
                                    $inicio_sal_fuera=new Carbon($fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarSalida.":00.000"); 
                                   
                                   // $final_sal_fuera=$fecha_eval."T".'23:59:59.000'; 
                                    $final_sal_fuera=new Carbon($final_sal_fuera);  
                                    $final_sal_fuera2=new Carbon($final_sal_fuera2);                           
                                    $modif=$inicio_sal;                                                             
                                   // dd($trab);
                                    if($trab!=0){
                                        $inicio_sal->addDay();
                                        $final_sal->addDay(); 
                                        $inicio_sal_fuera->addDay();
                                        $final_sal_fuera->addDay();
                                        $final_sal_fuera2->addDay();
                                        $final_sal_fuera2->addHours(2);
                                        $pase_ss->addDay();
                                        //$jorFin->addDays($diatrab);
                                    }
                                                                 
                                    $inicio_sal= str_replace(" ", "T", $inicio_sal);
                                    $final_sal= str_replace(" ", "T", $final_sal);  
                                    $final_sal_fuera2= str_replace(" ", "T", $final_sal_fuera2);                                   
                                    $modif=$modif->subDay();
                                  // dd($inicio_sal);    

                                    
                                      
                                  //  $inicio_sal_fuera->subHours(2);                                   
                                   // $inicio_sal_fuera= str_replace(" ", "T", $inicio_sal_fuera);
            
    
                                }
                         
                                $pase_ss->subHours(2);                             
                                $pase_ss= str_replace(" ", "T", $pase_ss);
                             
                           
                              //  dd("hola: ".$inicio_sal_fuera);              
                            
                            
                        $asistencia[$indice]['horario'] = $inicio;

      
                        $checada_entrada = $conexion->table("checkinout")
                                ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("CHECKTIME", [$inicio_entra, $final_entra])                                           
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"),"checkinout.sn","WorkCode","UserExtFmt")
                                ->groupBy("checkinout.sn","WorkCode","UserExtFmt")
                                                
                                ->first();

                                $omision_entrada = $conexion->table("CHECKEXACT")
                                ->join("USERINFO", "USERINFO.USERID", "=", "CHECKEXACT.USERID")
                                ->join("checkinout","checkinout.USERID", "=", "CHECKEXACT.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("CHECKEXACT.CHECKTIME", [$inicio_entra, $final_entra])                                           
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKEXACT.CHECKTIME, 108)) AS HORAJ"),"CHECKEXACT.CHECKTYPE","CHECKEXACT.EXACTID","CHECKEXACT.MODIFYBY")    
                                ->groupBy("CHECKEXACT.CHECKTYPE","CHECKEXACT.EXACTID","CHECKEXACT.MODIFYBY")                  
                                                
                                ->first();
                       // dd($checada_entrada);
                        $checada_entrada_fuera = $conexion->table("checkinout")
                        ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=",  $desc)
                        ->whereBetween("CHECKTIME", [$inicio_entra_fuera, $final_entra_fuera])                                           
                        ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))                        
                        ->first();


                        $checada_salida = $conexion->table("checkinout")
                                ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("CHECKTIME", [$inicio_sal, $final_sal])
                               // ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"),"checkinout.sn","WorkCode","UserExtFmt")
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(16), CHECKTIME, 120)) AS FECHAHORA"),DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"),"checkinout.sn","WorkCode","UserExtFmt")
                                ->groupBy("checkinout.sn","WorkCode","UserExtFmt")
                                ->first();
                        
                               // dd($final_sal_fuera. ".......".$final_sal_fuera2);
                        //dd("pase ". $pase_ss."  ini: ".$inicio_sal_fuera);
                                 $checada_sal_fuera = $conexion->table("checkinout")
                                ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereNotBetween("CHECKTIME", [$inicio_sal_fuera, $final_sal_fuera])
                                ->whereBetween("CHECKTIME", [$pase_ss,$inicio_sal_fuera])
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))
                                ->first();
                              
                                $checada_sal_fuera2 = $conexion->table("checkinout")
                                ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereNotBetween("CHECKTIME", [$inicio_sal_fuera, $final_sal_fuera])
                                ->whereBetween("CHECKTIME", [$final_sal_fuera,$final_sal_fuera2])
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))
                                ->first();


                               // dd($checada_sal_fuera);

                                //dd($checada_sal_fuera);
                                if($trab!=0){
                                    $fecha_eval_fin= new Carbon($fecha_eval);
                                    $fecha_eval_fin= $fecha_eval_fin->addDay();                                    
                                    $fecha_eval_fin= substr($fecha_eval_fin,0,-9);
                                   // dd($fecha_eval);
                                   
                                }else{
                                    $fecha_eval_fin=$fecha_eval;
                                }


                        //dd("horainicio: ".$fecha_eval."T".substr($asistencia[$indice]['jorfin'],11,5).":00.000"."cons".$fecha_eval."T23:59:59.000");
                                $checada_extra = $conexion->table("user_speday")
                                ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
                                ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")
                                ->join('users','users.id','=',"user_speday.captura_id")
                                ->where("TITLE", "=",  $desc)
                              ->where("STARTSPECDAY","<=",$fecha_eval_fin."T".substr($asistencia[$indice]['jorfin'],11,5).":00.000")
                              ->where("ENDSPECDAY",">=",$fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraInicio.":00.000")   
                                                   
                                ->select("leaveclass.LeaveName as Exepcion"
                                    ,DB::RAW("MIN(CONVERT(nvarchar(5), STARTSPECDAY, 108)) AS HORA")
                                    ,DB::RAW("datediff(MINUTE,STARTSPECDAY, ENDSPECDAY) AS DIFHORA")
                                    ,DB::RAW("datediff(DAY,STARTSPECDAY, ENDSPECDAY) AS DIFDIA")
                                    ,'STARTSPECDAY AS INI','ENDSPECDAY AS FIN','leaveclass.LeaveId AS TIPO'
                                    ,'user_speday.YUANYING AS REPO'
                                    ,'user_speday.id as Ban_Inci'
                                    ,'user_speday.captura_id as captura_id'
                                    ,'users.username as capturista'
                                    )
                                ->groupBy('leaveclass.LeaveName','user_speday.ENDSPECDAY','user_speday.STARTSPECDAY','leaveclass.LeaveId','user_speday.YUANYING','user_speday.id','user_speday.captura_id','users.username')
                                ->first();


                                $pasesSalidas = DiasOtorgados::where("USERID", "=",  $validacion->USERID)->where("DATEID","=","1")->where("STARTSPECDAY","<=",$f_fin_mes)
                                ->where("ENDSPECDAY",">=",$f_inicio_mes)->get();
                                            $hs1=0;
                                            $diff=0;

                                            foreach ($pasesSalidas as $i => $value) {
                                                
                                                if($value != null) {                                       
                                                    
                                                  // for ($i=0; $i < count($pasesSalidas); $i++) { 
                                                    $final=$pasesSalidas[$i]['ENDSPECDAY'];
                                                    $inicio=$pasesSalidas[$i]['STARTSPECDAY'];
                                                  
                                                  $inicio = new Carbon($value->STARTSPECDAY);
                                                  $final = new Carbon($value->ENDSPECDAY);
                                                   $diff = $inicio->diffInMinutes($final);            
                                                   $hs1=$hs1+$diff;
                                                 //  dd($inicio);
                                                 //  }
                                                  
                                                }
                                                
                                            }
                                           
                                

                                $ban_inci=0;
                                if(is_null($checada_extra)){
                                    "checada_extra";
                                }
                                else{
                                    
                                    if (is_null ($checada_extra->REPO)){
                                        $memo = "";
                                    }else{
                                        $memo =  " (".$checada_extra->REPO.")";
                                    }
                                   $ban_inci=$checada_extra->Ban_Inci;
                                   $sol=$checada_extra->captura_id;

                                    
                                   //return $ban_inci;
                                    switch($checada_extra->TIPO){
                                        case 1:                                
                                            $impr=$checada_extra->HORA." "."(Pase de Salida) " .$memo;                                
                                           $ps=$hs1;
                                                                                    
                                           
                                            break;
                                           
                                        case 2:
                                            $impr= "Vacaciones 2019 Primavera-Verano ".$memo;
                                            break;                               
                                        
                                        case 3:
                                            $impr= "Comisión ".$memo;
                                            break;
                                        case 4:
                                            $impr= "Omisión Salida".$memo;
                                            //$oS=$oS+1;
                                            break;
                                        case 5:
                                            $impr="Omisión Entrada ".$memo;
                                            //$oE=$oE+1;
                                            break;
                                        case 6:
                                            $impr="Día Económico " .$memo; 
                                            $diaE=$diaE+1;                                   
                                            break;
                                        case 8:
                                            $impr="Licencia Médica ".$memo;
                                            break;
                                        case 10:
                                            $impr= "Onomástico ".$memo;                                    
                                            break;
                                        case 11:
                                            $impr="Vacaciones 2018 Primavera-Verano ".$memo;                                    
                                            break;
                                        case 12:
                                            $impr="Vacaciones 2018 Invierno ".$memo;
                                            
                                            break;
                                        case 13:
                                            $impr="Vacaciones 2019 Invierno ".$memo;                                    
                                            break;
                                        case 14:
                                            $impr="Reposición ".$memo; 
                                            break;                                 
                                        case 15:                                
                                            $impr="Mediano Riesgo ".$memo;                                
                                                break;
                                        case 16:
                                            $impr="Vacaciones Extra Ordinarias " .$memo;                                    
                                            break;
                                        case 17:
                                            $impr="Cuidados Maternos ".$memo;                                    
                                            break;
                                        case 18:
                                            $impr="Constancia de Entrada";                                    
                                            break;
                                        case 19:
                                            $impr="Curso ".$memo;                                    
                                            break;
                                        case 20:
                                            $impr="Licencia Sin Goce " .$memo;                                    
                                            break;
                                        case 21:
                                            $impr="Licencia Con Goce " .$memo;                                    
                                            break;
                                        case 22:
                                            $impr="Pago de Guardia ".$memo;                                    
                                            break;
                                        case 27:
                                            $impr="Lista de Asistencia segun Memoradúm ".$memo;                                    
                                            break;
                                        case 29:
                                            $impr="Comisión Sindical según Memoradúm ".$memo;                                    
                                            break;
                                        case 30:
                                            $impr="Vacaciones 2020 Primavera-Verano";                                    
                                            break;       
                                        case 31:
                                            $impr="Contingencia COVID19 ".$memo;                                    
                                            break;
                                        case 32:
                                            $impr="Vacaciones 2020 Invierno " .$memo;                                   
                                            break;
                                        case 33:
                                            $impr="Vacaciones 2021 Primavera ".$memo;                                   
                                            break;
                                        case 34:
                                            $impr="Vacaciones 2021 Invierno ".$memo;                                   
                                            break;
                                        case 36:
                                            $impr="Cuidados Paternales ".$memo;                                   
                                            break;

                                        case 35:
                                            $impr="BECA " .$memo;                                 
                                            break;
                                        case 37:
                                            $impr="Licencia Matrimonial ".$memo;                                   
                                            break;
                                        case 38:
                                            $impr="Licencia Sindical ".$memo;                                   
                                            break;
                                        case 39:
                                            $impr="Licencia por Fallecimiento Familiar ".$memo;                                   
                                            break;
                                        case 40:
                                            $impr="Dia Autorizado ".$memo;                                    
                                            break;
                                        case 41:
                                            $impr="Alto Riesgo".$memo;                                    
                                            break;
                                        case 42:
                                            $impr="Bajo Riesgo".$memo;                                    
                                            break;
                                        case 43:
                                            $impr="Segun Memorandúm ".$memo;                                    
                                            break;
                                        case 44:
                                            $impr="Licencia por Maternidad ".$memo;                                    
                                            break;
                                        case 45:
                                            $impr="FALTA POR MEMORÁNDUM ".$memo;   
                                            $faltaxmemo = 1;                                 
                                            break;
                                        case 46:
                                            $impr="ABANDONO DE LABORES ";   
                                            $faltaxmemo = 1;                                 
                                            break;

                                        case 48:
                                            $impr="REUNIÓN SINDICAL ";   
                                                                           
                                            break;
                                        case 47:
                                            $impr="LICENCIA PREJUBILATORIA ";   
                                                                            
                                            break;

                                        case 49:
                                            $impr="LICENCIA PRESIDENCIAL ";   
                                                                            
                                            break;

                                        case 50:
                                            $impr="Vacaciones 2022 Primavera ".$memo;                                   
                                            break;
                                        case 51:
                                            $impr="Vacaciones 2022 Invierno ".$memo;                                   
                                            break;


                                        default:
                                            $impr="";
                                            break;
                                        
                                }

                                $capturista=$checada_extra->capturista;
                                $asistencia[$indice]['capturista']= $capturista;    

                            
                            }

                        if(is_null($checada_extra)){
                                "checada_extra";
                        }
                        else{
                                $hora_extra=$checada_extra->HORA;
                            }

                        if(isset($checada_entrada) || !is_null($checada_entrada)){                        
                            $formato_checado = new Carbon($fecha_eval." ".$checada_entrada->HORA);
                            $hora_con_tolerancia = new Carbon($fecha_eval." ".$var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraInicio);                          
                            $hora_permitida = new Carbon($fecha_eval." ".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarEntrada);
                            $tolerancia=$hora_con_tolerancia->addMinutes($var_reglas[$fecha_evaluar->dayOfWeekIso]->Tolerancia);

                            $asistencia[$indice]['retardo'] =0;
                                        if ($formato_checado>($tolerancia)){
                                            if ($formato_checado->diffInMinutes($tolerancia) >= 1 && $formato_checado->diffInMinutes($tolerancia)<=40){
                                                    if(is_null($checada_extra)|| ($checada_extra->TIPO==1)){
                                                        $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA;//." Retardo Menor";
                                                        $asistencia[$indice]['retardo'] = 1;
                                                        $rme=$rme+1;
                                                    }
                                                    else{
                                                        $asistencia[$indice]['checado_entrada'] = $impr;
                                                        $asistencia[$indice]['retardo'] = 0;
                                                    }
                                                }
                                            if ($formato_checado->diffInMinutes($tolerancia) >= 41){
                                                if(is_null($checada_extra) || ($checada_extra->TIPO==1)){
                                                    $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA." Retardo Mayor";
                                                    $rm=$rm+1;
                                                }
                                                else{
                                                    $asistencia[$indice]['checado_entrada'] = $impr;
                                                }
                                            }
                                        }
                                        else
                                        //dd($checada_entrada);
                                           if (is_null($checada_entrada->sn)){
                                             $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA; 
                                             
                                            }                               
                                            else{
                                                $tipo=0;
                                                $asistencia[$indice]['omision'] = $checada_entrada->WorkCode;
                                                $asistencia[$indice]['user_omision'] = $checada_entrada->UserExtFmt;
                                                $asistencia[$indice]['captura_omision'] = User::where("id","=",$asistencia[$indice]['user_omision'])->first();
                                                if($nombrebase<>'ZKAccess'){
                                                    switch($checada_entrada->sn){
                                                        
                                                            case "I":
                                                                $tipo=" Omisión Entrada";
                                                                break;
                                                           
                                                          
                                                            
                                                        
                                                
                                                    }
                                                    $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA. $tipo;
                                                     
                                                }
                                                else{
                                                    $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA;
                                                } 
                                            }
                                        

                        }
                        if(isset($omision_entrada)) {
                            $tipo=0;
                                                    $asistencia[$indice]['omision'] = $omision_entrada->EXACTID;
                                                    $asistencia[$indice]['user_omision'] = $omision_entrada->MODIFYBY;
                                                    $asistencia[$indice]['captura_omision'] = User::where("id","=",$asistencia[$indice]['user_omision'])->first();
                                                    if($nombrebase<>'ZKAccess'){
                                                        switch($omision_entrada->CHECKTYPE){
                                                            
                                                                case "I":
                                                                    $tipo=" Omisión Entrada";
                                                                    break;
                                                               
                                                                case "E":
                                                                    $tipo=" Constancia de Entrada";
                                                                    break;
                                                                case "R":
                                                                    $tipo=" Justificado por Retardo";
                                                                    $asistencia[$indice]['retardo'] = 0;
                                                                    $rme=$rme-1;
                                                                    break;
                                                                
                                                            
                                                    
                                                        }
                                                        $asistencia[$indice]['checado_entrada'] = $omision_entrada->HORAJ. $tipo;
                                                         
                                                    }
                                                    else{
                                                        $asistencia[$indice]['checado_entrada'] = $omision_entrada->HORAJ;
                                                    } 
                                                    
                                                   // $asistencia[$indice]['checado_entrada_fuera'] =$checada_entrada_fuera->HORA;
                                                   // $asistencia[$indice]['checado_entrada_fuera']=null;
                        }
                        
                    if(empty($asistencia[$indice]['checado_entrada'])){
                        $asistencia[$indice]['checado_entrada'] = "SIN REGISTRO";
                        $asistencia[$indice]['validacion'] = 0;
                        if(is_null($checada_extra)){
                            $asistencia[$indice]['checado_entrada'] = "SIN REGISTRO";
                            $asistencia[$indice]['validacion'] = 0;
                           
                            }
                        else{
                        
                                $asistencia[$indice]['validacion'] = 1;      
                                if ($checada_extra->TIPO==1){                                   
                                    $asistencia[$indice]['checado_entrada'] = "SIN REGISTRO";
                                    $asistencia[$indice]['validacion'] = 0;
                                }
                                else{
                                    $asistencia[$indice]['checado_entrada'] = $impr;
                                    
                            }
                           
                        
                        
                        }
                     }

                     
                     $asistencia[$indice]['ban_inci'] = $ban_inci;
                     $asistencia[$indice]['sol'] = $sol;


                   
                    if(isset($checada_sal_fuera2) || !is_null($checada_sal_fuera2) ){
                        $asistencia[$indice]['checado_salida_fuera2'] =$checada_sal_fuera2->HORA;
                       
                    }

                    if(isset($checada_sal_fuera) || !is_null($checada_sal_fuera)){
                        $asistencia[$indice]['checado_salida_fuera'] =$checada_sal_fuera->HORA;
                       
                    }
                   // dd($checada_sal_fuera);
                    if(isset($checada_entrada_fuera) && is_null($omision_entrada)){
                        $asistencia[$indice]['checado_entrada_fuera'] =$checada_entrada_fuera->HORA;
                       
                    }
                   // dd($checada_sal_fuera);
                      

                    if(isset($checada_salida) || !is_null($checada_salida)){                           
                        
                        
                        if(($checada_salida->HORA>$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida) )
                           { 
                                if($trab!=0){
                                    $asistencia[$indice]['checado_salida'] =$checada_salida->FECHAHORA;
                                }else{
                                    $asistencia[$indice]['checado_salida'] =$checada_salida->HORA;
                                }
                               //. " (Verifique Su Registro)";
                               $asistencia[$indice]['validacion'] = 1;
                           }
                        //|| ($checada_salida->HORA<$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida)
                            
                        else
                        if (is_null($checada_salida->sn)){
                            if($trab!=0){
                                $asistencia[$indice]['checado_salida'] =$checada_salida->FECHAHORA;
                            }else{
                                $asistencia[$indice]['checado_salida'] =$checada_salida->HORA;
                            }
                         }                               
                           else{
                                $tipo=" ";
                                $asistencia[$indice]['omisionsal'] = $checada_salida->WorkCode;
                                $asistencia[$indice]['user_omision'] = $checada_salida->UserExtFmt;
                                $asistencia[$indice]['captura_omision'] = User::where("id","=",$asistencia[$indice]['user_omision'])->first();
                                if($nombrebase<>'ZKAccess'){
                                    switch($checada_salida->sn){
                                                    
                                       
                                        case "O":
                                            $tipo=" Omisión Salida";
                                            break;
                                        
                                        case "S":
                                            $tipo=" Constancia de Salida";
                                            break;     
                                        
                                
                                    }
                                    if($trab!=0){
                                        $asistencia[$indice]['checado_salida'] =$checada_salida->FECHAHORA.$tipo;
                                    }else{
                                        $asistencia[$indice]['checado_salida'] =$checada_salida->HORA.$tipo;
                                    }
                                   //  $asistencia[$indice]['checado_salida'] = $checada_salida->HORA.$tipo;
                                }
                                else $asistencia[$indice]['checado_salida'] = $checada_salida->HORA;
                           }
                            
                    }
                    if(empty($asistencia[$indice]['checado_salida'])){
                            if(is_null($checada_extra)){
                            $asistencia[$indice]['checado_salida'] ="SIN REGISTRO";
                            $asistencia[$indice]['validacion'] = 0;
                            }
                        else{
                            $asistencia[$indice]['checado_salida'] = $impr;                            
                                $ini = new Carbon($checada_extra->INI);
                                $fin = new Carbon($checada_extra->FIN);
                                $asistencia[$indice]['validacion'] = 1;
                            }                        
                    }
                       
                    if(($asistencia[$indice]['checado_salida']=="SIN REGISTRO")&&($asistencia[$indice]['checado_entrada']=="SIN REGISTRO")){

                        if($trab!=0){
                            $fecha_eval = new Carbon($fecha_eval);
                           
                           // $fecha_eval= $fecha_eval->subDay();                                    
                            $fecha_eval= substr($fecha_eval,0,-9);
                           // dd($fecha_eval);
                           
                        }
                        //dd($diafest);
                        
                        $checa_inhabil = $conexion->table("HOLIDAYS")
                        ->where("STARTTIME","=",$fecha_eval.'T00:00:00.000') 
                        ->first();

                      // dd($diafest);
                        if(isset($checa_inhabil) && $diafest ==''){
                            $asistencia[$indice]['checado_entrada']=$checa_inhabil->HOLIDAYNAME;
                            $asistencia[$indice]['checado_salida']=$checa_inhabil->HOLIDAYNAME;
                            $asistencia[$indice]['validacion'] = 1;
                            $asistencia[$indice]['validacion'] = 1;
                        }
        
                    }
                    if(($asistencia[$indice]['checado_salida']=="SIN REGISTRO")&&($asistencia[$indice]['checado_entrada']<>"SIN REGISTRO")){
                        $checa_inhabil = $conexion->table("SAL_AUTO")
                        ->where("STARTTIME","=",$fecha_eval.'T00:00:00.000') 
                        ->first();
                        if(isset($checa_inhabil)){                           
                            $asistencia[$indice]['checado_salida']=$checa_inhabil->HOLIDAYNAME;
                            $asistencia[$indice]['validacion'] = 1;
                        }
        
                    }

                
                    if($validacion->SEP!=0  && $fecha_eval<'2021-01-01'){
                        if(($asistencia[$indice]['checado_salida']=="SIN REGISTRO")&&($asistencia[$indice]['checado_entrada']=="SIN REGISTRO")){
                            $checa_contingencia = $conexion->table("contingencia")
                            ->where("STARTTIME","=",$fecha_eval.'T00:00:00.000') 
                            ->first();
                            if(isset($checa_contingencia) ){
                                $asistencia[$indice]['checado_entrada']=$checa_contingencia->HOLIDAYNAME;
                                $asistencia[$indice]['checado_salida']=$checa_contingencia->HOLIDAYNAME;
                                $asistencia[$indice]['validacion'] = 1;
                            }
            
                        }
                        if(($asistencia[$indice]['checado_salida']=="SIN REGISTRO")&&($asistencia[$indice]['checado_entrada']<>"SIN REGISTRO")){
                            $checa_inhabil = $conexion->table("SAL_AUTO")
                            ->where("STARTTIME","=",$fecha_eval.'T00:00:00.000') 
                            ->first();
                            if(isset($checa_inhabil)){                           
                                $asistencia[$indice]['checado_salida']=$checa_inhabil->HOLIDAYNAME;
                                $asistencia[$indice]['validacion'] = 1;
                            }
            
                        }
                    }

                    if(($asistencia[$indice]['checado_salida']=="SIN REGISTRO")||($asistencia[$indice]['checado_entrada']=="SIN REGISTRO"))
                      $falta = $falta+1;

                      $asistencia[$indice]['faltaxmemo'] = $faltaxmemo;
        
                      
                }


                
           

            $indice++;
            $fecha_pivote->addDays(1);

            if($diafest != null)
                {
                    foreach ($var_reglas as $key => $value) {
                        if($value != null)
                        {
                            //$regla_festivo_fin_Semana = $value;
                            unset($var_reglas[$fecha_evaluar->dayOfWeekIso]);
                            $festivo=0;
                        }
                    }
                }
            }
        
        $ps=$ps/60;
     
        
        $resumen = array(['dea'=>$diaEconomicoanual,'resumen2'=>$resumen2,'horastra'=>$htra,'pagoGuardia'=>$pagoGuardia,'Pase_Salida'=>$ps,'Retardo_Mayor'=>$rm,'Retardo_Menor'=>0,'Vacaciones_2019_Primavera_Verano'=> $vac19_1,'Vacaciones_2019_Invierno'=>$vac19_2,'Vacaciones_2020_Primavera_Verano'=> $vac20_1,'Vacaciones_2020_Invierno'=>$vac20_2,'Vacaciones_2018_Primavera_Verano'=>$vac18_1,'Vacaciones_2018_Invierno'=>$vac18_2,'Día_Económico'=>$diaE,'Onomástico'=>$ono,'Omisión_Entrada'=> $oE,'Omisión_Salida'=>$oS,'Falta'=>$falta,'Vacaciones_Mediano_Riesgo'=>$vacMR,'Vacaciones_Extra_Ordinarias'=>$vacEx]);
       if($impre==0){
        return response()->json(["data" => $asistencia, "nocturno"=> $diatrab, "resumen" => $resumen, "validacion"=> $validacion, "fecha_inicial"=> $fecha_view_inicio->format('Y-m-d'), "fecha_final"=> $fecha_view_fin->format('Y-m-d')]);
       }else{  
           return array("data" => $asistencia, "validacion"=> $validacion, "fecha_inicial"=> $fecha_view_inicio->format('d/m/Y'), "fecha_final"=> $fecha_view_fin->format('d/m/Y'));
       }
    }
    function dias_otorgados($arreglo)
    {
        $arreglo_dias = array();
       

        foreach ($arreglo as $key => $value) {
           //  $arreglo_dias[substr($value->STARTSPECDAY, 0,10)][] = $value;
            
            $inicio = new Carbon($value->STARTSPECDAY);
             $fin = new Carbon($value->ENDSPECDAY);
             $diff = $inicio->diffInDays($fin);            
             $arreglo_dias[substr($inicio, 0,10)][] = $value;
             for ($i=0; $i < $diff; $i++) { 
                $arreglo_dias[substr($inicio->addDays(), 0,10)][] = $value;
                
             } 

         }
       //dd($arreglo_dias);
         return $arreglo_dias;
       
    }

   /*  function dias_economicos($arreglo)
    {
        $arreglo_economico = array();
       

        foreach ($arreglo as $key => $value) {
           //  $arreglo_dias[substr($value->STARTSPECDAY, 0,10)][] = $value;
            
             $inicio = new Carbon($value->STARTSPECDAY);
             $fin = new Carbon($value->ENDSPECDAY);
             $diff = $inicio->diffInDays($fin);            
             $arreglo_economico[substr($inicio, 0,10)][] = $value;
             $ndias=0;
             for ($i=0; $i < $diff; $i++) { 
                $arreglo_economico[substr($inicio->addDays(), 0,10)][] = $value;
                $ndias+=1;
                
             } 

         }
     
         return $ndias;
       
    } */


    
    public function decrypt($string) {

        $result = '';
        $key = "%%pGCrTPUthfUV_s7y=4gEE";
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
     }

}
