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

                $regla_festivo_fin_Semana = "";
                if($diafest != null)
                {
                    foreach ($var_reglas as $key => $value) {
                       
                        if($value != null)
                        {
                            
                            //$regla_festivo_fin_Semana = $value;
                            $var_reglas[$fecha_evaluar->dayOfWeekIso]  = $value;
                          
                        }
                    }
                }
                //return response()->json(["data" => $var_reglas]);    

               $festivos   = Festivos::where("STARTTIME", "=", $fecha_evaluar)->get();
               

               
              //  $arreglo_festivos = array();
                //if(count($festivos) > 0){ $arreglo_festivos = $this->festivos($festivos); }
              //dd($festivos);
                if($var_reglas[$fecha_evaluar->dayOfWeekIso] || $diafest != '' )
                {

          //dd($diafest->COMETIME);
              
                        $asistencia[$indice]['numero_dia'] = $fecha_evaluar->dayOfWeekIso;
                        $asistencia[$indice]['validacion'] = 1;
                      
                        $jorIni=new Carbon($var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraInicio);
                        $jorFin=new Carbon($var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraFin);
                       
                        if(substr($var_reglas[$fecha_evaluar->dayOfWeekIso]->horario,0,2)<>"HT")
                            $htra=$jorFin->diffInRealHours($jorIni);
                        else
                            $htra=0;

                            $asistencia[$indice]['fecha'] = $fecha_evaluar->format('Y-m-d');
                        
                            $fecha_eval = $asistencia[$indice]['fecha'];
    
                            $inicio_entra=$fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarEntrada.":00.000";                   
                            $final_entra=$fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarEntrada.":00.000";
                            $diatrab=$var_reglas[$fecha_evaluar->dayOfWeekIso]->diaSal-$var_reglas[$fecha_evaluar->dayOfWeekIso]->diaEnt;
                            $inicio_sal=$fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarSalida.":00.000"; 
                            $final_sal=$fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida.":00.000";
    
                           $asistencia[$indice]['jorini'] = $fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraInicio.":00.000";
                           $asistencia[$indice]['jorfin'] = $fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraFin.":00.000";
    
                            if ($diatrab>=1)
                                {
                                    $inicio_sal=new Carbon($fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarSalida.":00.000");
                                    $final_sal=new Carbon($fecha_eval."T".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida.":00.000");
                                    $modif=$inicio_sal;                                                             
                                    $inicio_sal->addDays($diatrab);
                                    $final_sal->addDays($diatrab);
                                    $inicio_sal= str_replace(" ", "T", $inicio_sal);
                                    $final_sal= str_replace(" ", "T", $final_sal);
                                    $modif=$modif->subDays($diatrab);
    
                                }
                               
                           
                               // return "InicioSalida: ". $inicio_sal."  SAlidadddddddda: ".$final_sal;         
                                
                           
                            $inicio_entra_fuera=$fecha_eval."T".'00:00:01.000';
                             
                            
    
    
                            $inicio_sal_fuera=new Carbon($fecha_eval." ".$var_reglas[$fecha_evaluar->dayOfWeekIso]->InicioChecarSalida.":00.000"); 
                            $final_sal_fuera=$fecha_eval."T".'23:59:59.000';  
                            $inicio_sal_fuera->subHours(2);
                            $final_entra_fuera=$inicio_sal_fuera->subHours(2);
                            $final_entra_fuera->subMinute();
                            $final_entra_fuera= str_replace(" ", "T", $final_entra_fuera);
                            $inicio_sal_fuera= str_replace(" ", "T", $inicio_sal_fuera);
                           
                                                   
                            
                            
                        $asistencia[$indice]['horario'] = $inicio;

      
                        $checada_entrada = $conexion->table("checkinout")
                                ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("CHECKTIME", [$inicio_entra, $final_entra])                                           
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"),"checkinout.sn")
                                ->groupBy("checkinout.sn")
                                                
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
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"),"checkinout.sn")
                                ->groupBy("checkinout.sn")
                                ->first();
                        
                                
                        //return $checada_salida;
                        $checada_sal_fuera = $conexion->table("checkinout")
                                ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("CHECKTIME", [$inicio_sal_fuera, $final_sal_fuera])
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))
                                ->first();

                        
                               
                        
                                $checada_extra = $conexion->table("user_speday")
                                ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
                                ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")
                                ->where("TITLE", "=",  $desc)
                              ->where("STARTSPECDAY","<=",$fecha_eval."T23:59:59.000")
                              ->where("ENDSPECDAY",">=",$fecha_eval."T00:00:00.000")   
                                                   
                                ->select("leaveclass.LeaveName as Exepcion"
                                    ,DB::RAW("MIN(CONVERT(nvarchar(5), STARTSPECDAY, 108)) AS HORA")
                                    ,DB::RAW("datediff(MINUTE,STARTSPECDAY, ENDSPECDAY) AS DIFHORA")
                                    ,DB::RAW("datediff(DAY,STARTSPECDAY, ENDSPECDAY) AS DIFDIA")
                                    ,'STARTSPECDAY AS INI','ENDSPECDAY AS FIN','leaveclass.LeaveId AS TIPO'
                                    ,'user_speday.YUANYING AS REPO'
                                    ,'user_speday.incidencia_id as Ban_Inci'
                                    ,'user_speday.captura_id as captura_id'
                                    )
                                ->groupBy('leaveclass.LeaveName','user_speday.ENDSPECDAY','user_speday.STARTSPECDAY','leaveclass.LeaveId','user_speday.YUANYING','user_speday.incidencia_id','user_speday.captura_id')
                                ->first();
                               // dd($conexion);

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
                                            $impr=$checada_extra->HORA." "."(Pase de Salida)";                                
                                            
                                                                                    
                                            if ($diatrab>=1)
                                                {
                                                    $hps=new Carbon($fecha_eval." ".$checada_extra->HORA.":00.000");
                                                    $hps=$modif->diffInMinutes($hps);
                                                    $ps=$hps;
                                                }                                                    
                                            else
                                                $ps=$ps+$checada_extra->DIFHORA;
                                            break;
                                        case 2:
                                            $impr= "Vacaciones 2019 Primavera-Verano";
                                            break;                               
                                        
                                        case 3:
                                            $impr= "Comisión ".$memo;
                                            break;
                                        case 4:
                                            $impr= "Omisión Salida".$memo;
                                            //$oS=$oS+1;
                                            break;
                                        case 5:
                                            $impr="Omisión Entrada".$memo ;
                                            //$oE=$oE+1;
                                            break;
                                        case 6:
                                            $impr="Día Económico"; 
                                            $diaE=$diaE+1;                                   
                                            break;
                                        case 8:
                                            $impr="Licencia Médica";
                                            break;
                                        case 10:
                                            $impr= "Onomástico";                                    
                                            break;
                                        case 11:
                                            $impr="Vacaciones 2018 Primavera-Verano";                                    
                                            break;
                                        case 12:
                                            $impr="Vacaciones 2018 Invierno";
                                            
                                            break;
                                        case 13:
                                            $impr="Vacaciones 2019 Invierno";                                    
                                            break;
                                        case 14:
                                            $impr="Reposición".$memo; 
                                            break;                                 
                                        case 15:                                
                                            $impr="Vacaciones Mediano Riesgo";                                
                                                break;
                                        case 16:
                                            $impr="Vacaciones Extra Ordinarias";                                    
                                            break;
                                        case 17:
                                            $impr="Cuidados Maternos";                                    
                                            break;
                                        case 18:
                                            $impr="Constancia de Entrada";                                    
                                            break;
                                        case 19:
                                            $impr="Curso ".$memo;                                    
                                            break;
                                        case 20:
                                            $impr="Licencia Sin Goce ";                                    
                                            break;
                                        case 21:
                                            $impr="Licencia Con Goce ";                                    
                                            break;
                                        case 22:
                                            $impr="Pago de Guardia ";                                    
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
                                            $impr="Vacaciones 2020 Invierno";                                   
                                            break;
                                        case 33:
                                            $impr="Vacaciones 2021 Primavera";                                   
                                            break;
                                        case 34:
                                            $impr="Vacaciones 2021 Invierno";                                   
                                            break;
                                        case 36:
                                            $impr="Cuidados Paternales ".$memo;                                   
                                            break;
                                        case 40:
                                            $impr="Dia Autorizado ".$memo;                                    
                                            break;
                                        case 41:
                                            $impr="Vacaciones de Alto Riesgo".$memo;                                    
                                            break;
                                        case 42:
                                            $impr="Vacaciones de Bajo Riesgo".$memo;                                    
                                            break;
                                        case 43:
                                            $impr="Segun Memorandúm ".$memo;                                    
                                            break;
                                        case 44:
                                            $impr="Licencia por Maternidad ".$memo;                                    
                                            break;
                                        default:
                                            $impr="";
                                            break;
                                        
                                }

                            
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

                                
                                        if ($formato_checado>($tolerancia)){
                                            if ($formato_checado->diffInMinutes($tolerancia) >= 1 && $formato_checado->diffInMinutes($tolerancia)<=25){
                                                    if(is_null($checada_extra)|| ($checada_extra->TIPO==1)){
                                                        $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA." Retardo Menor";
                                                        $rme=$rme+1;
                                                    }
                                                    else{
                                                        $asistencia[$indice]['checado_entrada'] = $impr;
                                                    }
                                                }
                                            if ($formato_checado->diffInMinutes($tolerancia) >= 26){
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
                                             $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA; }                               
                                            else{
                                                if($nombrebase<>'ZKAccess')
                                                     $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA. " Omisión Entrada";
                                                else $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA;
                                            }
                                        

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


                    if(isset($checada_sal_fuera)){
                        $asistencia[$indice]['checado_salida_fuera'] =$checada_sal_fuera->HORA;
                       
                    }

                    if(isset($checada_entrada_fuera)){
                        $asistencia[$indice]['checado_entrada_fuera'] =$checada_entrada_fuera->HORA;
                       
                    }

                    if(isset($checada_salida) || !is_null($checada_salida)){                           
                        
                        
                        if(($checada_salida->HORA>$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida) )
                           { 
                               
                               $asistencia[$indice]['checado_salida'] =$checada_salida->HORA. " (Verifique Su Registro)";
                               $asistencia[$indice]['validacion'] = 1;
                           }
                        //|| ($checada_salida->HORA<$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida)
                            
                        else
                        if (is_null($checada_salida->sn)){
                            $asistencia[$indice]['checado_salida'] = $checada_salida->HORA; }                               
                           else{
                                if($nombrebase<>'ZKAccess')
                                     $asistencia[$indice]['checado_salida'] = $checada_salida->HORA. " Omisión Salida";
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
                        $checa_inhabil = $conexion->table("HOLIDAYS")
                        ->where("STARTTIME","=",$fecha_eval.'T00:00:00.000') 
                        ->first();
                        if(isset($checa_inhabil) && $diafest ==''){
                            $asistencia[$indice]['checado_entrada']=$checa_inhabil->HOLIDAYNAME;
                            $asistencia[$indice]['checado_salida']=$checa_inhabil->HOLIDAYNAME;
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
                        }
                    }
                }
            }
        
        $ps=$ps/60;
        //dd( $asistencia); 
        $resumen = array(['horastra'=>$htra,'pagoGuardia'=>$pagoGuardia,'Pase_Salida'=>$ps,'Retardo_Mayor'=>$rm,'Retardo_Menor'=>$rme,'Vacaciones_2019_Primavera_Verano'=> $vac19_1,'Vacaciones_2019_Invierno'=>$vac19_2,'Vacaciones_2020_Primavera_Verano'=> $vac20_1,'Vacaciones_2020_Invierno'=>$vac20_2,'Vacaciones_2018_Primavera_Verano'=>$vac18_1,'Vacaciones_2018_Invierno'=>$vac18_2,'Día_Económico'=>$diaE,'Onomástico'=>$ono,'Omisión_Entrada'=> $oE,'Omisión_Salida'=>$oS,'Falta'=>$falta,'Vacaciones_Mediano_Riesgo'=>$vacMR,'Vacaciones_Extra_Ordinarias'=>$vacEx]);
       if($impre==0){
        return response()->json(["data" => $asistencia, "resumen" => $resumen, "validacion"=> $validacion, "fecha_inicial"=> $fecha_view_inicio->format('Y-m-d'), "fecha_final"=> $fecha_view_fin->format('Y-m-d')]);
       }else{  
           return array("data" => $asistencia, "validacion"=> $validacion, "fecha_inicial"=> $fecha_view_inicio->format('d/m/Y'), "fecha_final"=> $fecha_view_fin->format('d/m/Y'));
       }
    }

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
