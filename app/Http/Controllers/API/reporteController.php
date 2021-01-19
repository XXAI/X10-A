<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use Carbon\Carbon, DB;
use App\Models\TiposIncidencia;
use App\Models\Usuarios;
use App\Models\UsuarioHorario;
use App\Models\Horario;

class reporteController extends Controller
{

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
//        return $sol;

        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;
      
        $Rfc = str_replace("(", "/", $Rfc);
        $Rfc = str_replace(" ", "+", $Rfc);
        if (!isset($parametros['id'])){
            $desc = $this->decrypt($Rfc);
            
        }
        else
            $desc =$parametros['id'];

        //return $desc;
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
        
       // return substr($f_ini, 0, 10).'T00:00:00.000';
    

           // DB::enableQueryLog()p;
          // dd($desc);

          $validacion= Usuarios::with("horarios.detalleHorario")->where("userinfo.TITLE", "=",  $desc)
            /*  $validacion = DB::TABLE("userinfo")
             ->with("horarios.detalleHorario")
            ->where("userinfo.TITLE", "=",  $desc) */
            ->first();
           // return $validacion;

                $checa_dias = DB::table("user_speday")
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
        $buscaHorario=DB::table("USER_OF_RUN")                  
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
                    $empleado = DB::TABLE("user_of_run")                            
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
               
              
                if($var_reglas[$fecha_evaluar->dayOfWeekIso])
                {

                        /*if($var_reglas[$fecha_evaluar->dayOfWeekIso]->idH==12);{
                        $buscaFestivo=DB::TABLE("USER_TEMP_SCH")
                        ->where("USER_TEMP_SCH.TITLE", "=",  $desc)
                        ->first();
                        }*/
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

                        $checada_entrada = DB::table("checkinout")
                                ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("CHECKTIME", [$inicio_entra, $final_entra])                                           
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))                        
                                ->first();
                        $checada_entrada_fuera = DB::table("checkinout")
                        ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=",  $desc)
                        ->whereBetween("CHECKTIME", [$inicio_entra_fuera, $final_entra_fuera])                                           
                        ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))                        
                        ->first();


                        $checada_salida = DB::table("checkinout")
                                ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("CHECKTIME", [$inicio_sal, $final_sal])
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))
                                ->first();
                        //return $checada_salida;
                        $checada_sal_fuera = DB::table("checkinout")
                                ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("CHECKTIME", [$inicio_sal_fuera, $final_sal_fuera])
                                ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))
                                ->first();

                        
                               
                        /* if($sol<>1){ */
                                $checada_extra = DB::table("user_speday")
                                ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
                                ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("STARTSPECDAY",[$fecha_eval."T00:00:00.000",$fecha_eval."T23:59:59.000"])                        
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
                                
                   /*          }
                            else{
                                $checada_extra = DB::table("incidencias")
                                ->join("USERINFO", "USERINFO.USERID", "=", "incidencias.USERID")
                                ->join("leaveclass","leaveclass.LeaveId", "=", "incidencias.incidencias_tipo_id")
                                ->where("TITLE", "=",  $desc)
                                ->whereBetween("fecha_ini",[$fecha_eval."T00:00:00.000",$fecha_eval."T23:59:59.000"])                        
                                ->select("leaveclass.LeaveName as Exepcion"
                                    ,DB::RAW("MIN(CONVERT(nvarchar(5), fecha_ini, 108)) AS HORA")
                                    ,DB::RAW("datediff(MINUTE,fecha_ini, fecha_fin)AS DIFHORA")
                                    ,DB::RAW("datediff(DAY,fecha_ini, fecha_fin) AS DIFDIA")
                                    ,'fecha_ini AS INI','fecha_fin AS FIN','leaveclass.LeaveId AS TIPO'
                                    ,'incidencias.documentos as REPO'
                                    ,'incidencias.id as Ban_Inci'
                                    )
                                ->groupBy('leaveclass.LeaveName','incidencias.fecha_fin','incidencias.fecha_ini','leaveclass.LeaveId','incidencias.documentos','incidencias.id')
                                ->first();
                                
                            } */
                                
                          

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
                                        $impr= "Comisión";
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
                                        $impr="Memorandum".$memo;                                    
                                        break;
                                    case 20:
                                        $impr="Licencia Sin Goce ";                                    
                                        break;
                                    case 30:
                                        $impr="Vacaciones 2020 Primavera-Verano";                                    
                                        break;       
                                    case 31:
                                        $impr="Contingencia COVID19".$memo;                                    
                                        break;
                                    case 32:
                                        $impr="Vacaciones 2020 Invierno";                                   
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
                        if(isset($checada_entrada)){                        
                            $formato_checado = new Carbon($fecha_eval." ".$checada_entrada->HORA);
                            $hora_con_tolerancia = new Carbon($fecha_eval." ".$var_reglas[$fecha_evaluar->dayOfWeekIso]->HoraInicio);
                          
                            $hora_permitida = new Carbon($fecha_eval." ".$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarEntrada);
                            $tolerancia=$hora_con_tolerancia->addMinutes($var_reglas[$fecha_evaluar->dayOfWeekIso]->Tolerancia);

                                
                                        if ($formato_checado>($tolerancia)){
                                            if ($formato_checado->diffInMinutes($tolerancia) >= 1 && $formato_checado->diffInMinutes($tolerancia)<=25){
                                                    $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA." Retardo Menor";
                                                    $rme=$rme+1;
                                                }
                                            if ($formato_checado->diffInMinutes($tolerancia) >= 26){
                                                $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA." Retardo Mayor";
                                                $rm=$rm+1;
                                            }
                                        }
                                        else
                                        $asistencia[$indice]['checado_entrada'] = $checada_entrada->HORA;                                

                        }
                      
                    if(is_null($asistencia[$indice]['checado_entrada'])){
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

                    if(isset($checada_salida)){
                            
                        
                        
                        if(($checada_salida->HORA>$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida) )
                           { 
                               
                               $asistencia[$indice]['checado_salida'] =$checada_salida->HORA. " (Verifique Su Registro)";
                               $asistencia[$indice]['validacion'] = 1;
                           }
                        //|| ($checada_salida->HORA<$var_reglas[$fecha_evaluar->dayOfWeekIso]->FinChecarSalida)
                            
                        else
                            $asistencia[$indice]['checado_salida'] =$checada_salida->HORA;
                    }
                    if(is_null($asistencia[$indice]['checado_salida'])){
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
                        $checa_inhabil = DB::TABLE("HOLIDAYS")
                        ->where("STARTTIME","=",$fecha_eval.'T00:00:00.000') 
                        ->first();
                        if(isset($checa_inhabil)){
                            $asistencia[$indice]['checado_entrada']=$checa_inhabil->HOLIDAYNAME;
                            $asistencia[$indice]['checado_salida']=$checa_inhabil->HOLIDAYNAME;
                            $asistencia[$indice]['validacion'] = 1;
                        }
        
                    }
                    if(($asistencia[$indice]['checado_salida']=="SIN REGISTRO")&&($asistencia[$indice]['checado_entrada']<>"SIN REGISTRO")){
                        $checa_inhabil = DB::TABLE("SAL_AUTO")
                        ->where("STARTTIME","=",$fecha_eval.'T00:00:00.000') 
                        ->first();
                        if(isset($checa_inhabil)){                           
                            $asistencia[$indice]['checado_salida']=$checa_inhabil->HOLIDAYNAME;
                            $asistencia[$indice]['validacion'] = 1;
                        }
        
                    }


                    if(($asistencia[$indice]['checado_salida']=="SIN REGISTRO")||($asistencia[$indice]['checado_entrada']=="SIN REGISTRO"))
                      $falta = $falta+1;
        
          
                }


            
           

            $indice++;
            $fecha_pivote->addDays(1);
            }
        
        $ps=$ps/60;
       
        $resumen = array(['horastra'=>$htra,'Pase_Salida'=>$ps,'Retardo_Mayor'=>$rm,'Retardo_Menor'=>$rme,'Vacaciones_2019_Primavera_Verano'=> $vac19_1,'Vacaciones_2019_Invierno'=>$vac19_2,'Vacaciones_2020_Primavera_Verano'=> $vac20_1,'Vacaciones_2020_Invierno'=>$vac20_2,'Vacaciones_2018_Primavera_Verano'=>$vac18_1,'Vacaciones_2018_Invierno'=>$vac18_2,'Día_Económico'=>$diaE,'Onomástico'=>$ono,'Omisión_Entrada'=> $oE,'Omisión_Salida'=>$oS,'Falta'=>$falta,'Vacaciones_Mediano_Riesgo'=>$vacMR,'Vacaciones_Extra_Ordinarias'=>$vacEx]);
       
        return response()->json(["data" => $asistencia, "resumen" => $resumen, "validacion"=> $validacion, "fecha_inicial"=> $fecha_view_inicio->format('Y-m-d'), "fecha_final"=> $fecha_view_fin->format('Y-m-d')]);
      
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
