<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon, DB;

class reporteController extends Controller
{
    public function index(Request $request)
    {
        $arreglo_fecha = array();
        $fecha_actual = Carbon::now();
        $anio_actual = $fecha_actual->year;
        $mes_actual = $fecha_actual->month;
        $dia_actual = $fecha_actual->day;
       
        $Rfc =$request -> get('rfc');
        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;
        $desc = $Rfc;

        //$desc = $this->decrypt($Rfc);
           // DB::enableQueryLog();
        $empleado = DB::TABLE("userinfo")
                            ->join("user_of_run", "userinfo.USERID", "=", "user_of_run.USERID")
                            ->join("num_run", "num_run.NUM_RUNID", "=", "user_of_run.NUM_OF_RUN_ID")
                            ->join("num_run_deil", "num_run_deil.NUM_RUNID", "=", "num_run.NUM_RUNID")
                            ->join("schclass", "schclass.schClassid", "=", "num_run_deil.SCHCLASSID")
                            ->select("userinfo.name"
                                    ,"num_run.name as horario"
                                    ,DB::RAW("CONVERT(varchar, num_run.STARTDATE, 112) as fecha_inicial")
                                    ,DB::RAW("CONVERT(varchar,num_run.ENDDATE, 112) as fecha_final")
                                    ,"num_run_deil.SDAYS as dia"
                                    ,"schclass.schName as Detalle_Horario"
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.StartTime,108) as HoraInicio")
                                    ,"schclass.EndTime as HoraFin"
                                    ,"schclass.LateMinutes as Tolerancia"
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckInTime1, 108) as InicioChecarEntrada")
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckInTime2, 108) as FinChecarEntrada")
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckOutTime1, 108) as InicioChecarSalida")
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckOutTime2, 108) as FinChecarSalida")
                                    ,"schclass.CheckOutTime2 as prueba"
                                    ,DB::RAW("left(schclass.schClassId, 2) as idH")
                                    )
                           
                            ->where("userinfo.TITLE", "=",  $desc)
                            ->get();
                            $checa_dias = DB::table("user_speday")
                            ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
                            ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")                            
                           ->where("TITLE", "=",  $request -> get('rfc'))   
                           ->whereBetween(DB::RAW("DATEPART(DW,STARTSPECDAY)"),[2,6])
                           ->groupBy('leaveclass.LeaveId','leaveclass.LeaveName') 
                           
                            ->select("leaveclass.LeaveName as Exepcion"                            
                               ,'leaveclass.LeaveId AS TIPO'
                               ,DB::RAW("count(leaveclass.LeaveId) as total")                               
                            )
                            //'STARTSPECDAY AS INI','ENDSPECDAY AS FIN',
                            ->get();
                                $vac19_1=0;
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
                                    
                                    case 6:                                       
                                        $diaE=$tipos->total;
                                        break;
                                    
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
                                    default:
                                        $impr="";
                                        break;
                                }                                                           
                            }

                            //dd($empleado);
                       //$query = DB::getQueryLog();
                        //dd($query);
        $arreglo_dias = array();
        for($dias = 1; $dias<8; $dias++)
            $arreglo_dias[$dias] = null;

        foreach ($empleado as $key => $value) {
            $arreglo_dias[$value->dia] = $value;
        }
        if($inicio == null){
            $f_ini = Carbon::now()->startOfMonth();
            $f_fin = Carbon::now();
        }else{
            $f_ini= new Carbon($inicio);
            $f_fin= new Carbon($fin);
        }
         $diff= $f_ini->diffInDays($f_fin);
        $asistencia = array();
        $rm=0;
        $rme=0;
        $ps=0;     
      
        $oE=0;
        $oS=0;
        $falta=0;
       
        for($i = 1; $i<=$diff; $i++)
        {
            $fecha_evaluar = $fecha_actual;
            $fecha_evaluar->day = $i;
            //echo $fecha_evaluar."<br>";
            if($arreglo_dias[$fecha_evaluar->dayOfWeekIso])
            {
                $asistencia[$i]['fecha'] = $fecha_evaluar->format('Y-m-d');
                $fecha_eval = $asistencia[$i]['fecha'];

                $inicio=$fecha_eval." ".$value->InicioChecarEntrada;
                $final=$fecha_eval." 11:00:00";

                $checada_entrada = DB::table("checkinout")
                        ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=",  $desc)
                      ->whereBetween("CHECKTIME", [$fecha_eval."T".$value->InicioChecarEntrada.":00.000", $fecha_eval."T".$value->FinChecarEntrada.":00.000"])
                       
                      ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))
                     
                        ->first();
                   


                $checada_salida = DB::table("checkinout")
                         ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=",  $desc)
                       
                        ->whereBetween("CHECKTIME", [$fecha_eval."T".$value->InicioChecarSalida.":00.000", $fecha_eval."T".$value->FinChecarSalida.":00.000"])
                        ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))
                        ->first();

                      
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
                        )
                        ->groupBy('leaveclass.LeaveName','user_speday.ENDSPECDAY','user_speday.STARTSPECDAY','leaveclass.LeaveId')

                        ->first();
                     
                        if(is_null($checada_extra)){
                            "checada_extra";
                        }
                        else{
                            switch($checada_extra->TIPO){
                            case 1:
                              
                                $impr=$checada_extra->HORA. " ".$checada_extra->Exepcion;
                                $ps=$ps+$checada_extra->DIFHORA;
                                break;
                            case 2:
                              
                                $dia_ini=$checada_extra->INI;
                                $dia_fin=$checada_extra->FIN;
                                $dif_dia=$checada_extra->DIFDIA;
                              
                                $impr= "Vacaciones 2019 1er Periodo";
                               
                                break;
                            case 3:
                                $impr= "Comision";
                                break;
                            case 4:
                                $impr= "Omision Salida";
                                $oS=$oS+1;
                                break;
                            case 5:
                                $impr="Omision Entrada";
                                $oE=$oE+1;
                                break;
                             case 6:
                                $impr="Dia Economico";
                                
                                break;
                             case 8:
                                $impr="Licencia Medica";
                                break;
                             case 10:
                                $impr= "Onomastico";
                                $ono=$ono+1;
                                break;
                            case 11:
                                $impr="Vacaciones 2018 1er Periodo";
                                
                                break;
                            case 12:
                                $impr="Vacaciones 2018 2do Periodo";
                              
                                break;
                            case 13:
                                $impr="Vacaciones 2019 2do Periodo";
                                
                                break;
                            case 14:
                                $impr="Reposicion";
                                break;
                            case 15:
                                $impr="Vacaciones Mediano Riesgo";
                                
                                break;
                            case 16:
                                $impr="Vacaciones Extra Ordinarias";
                               
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
                if(isset($checada_entrada))
                {
                    $formato_checado = new Carbon($fecha_eval." ".$checada_entrada->HORA);
                    $hora_con_tolerancia = new Carbon($fecha_eval." ".$value->HoraInicio);
                    $hora_permitida = new Carbon($fecha_eval." ".$value->FinChecarEntrada);
                    $tolerancia=$hora_con_tolerancia->addMinutes($value->Tolerancia);

                            if($value->idH==40)
                                $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA;
                            else{
                                if ($formato_checado>($tolerancia)){
                                  if ($formato_checado->diffInMinutes($tolerancia) >= 1 && $formato_checado->diffInMinutes($tolerancia)<=15){
                                        $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA." Retardo Menor";
                                        $rme=$rme+1;
                                    }
                                    if ($formato_checado->diffInMinutes($tolerancia) >= 16){
                                        $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA." Retardo Mayor";
                                        $rm=$rm+1;
                                    }
                                }
                                else
                                $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA;
                        }

                }
                if(is_null($asistencia[$i]['checado_entrada'])){
                        $asistencia[$i]['checado_entrada'] = "SIN REGISTRO";
                     if(is_null($checada_extra)){
                        $asistencia[$i]['checado_entrada'] = "SIN REGISTRO";
                        $falta = $falta+1;
                        }
                    else{         
                            if ($checada_extra->TIPO==1){   
                                $asistencia[$i]['checado_entrada'] = "SIN REGISTRO";
                            }
                            else{
                                $asistencia[$i]['checado_entrada'] = $impr;
                            }
                            //$falta-=1;
                           
                           
                        }
                 }
                if(isset($checada_salida)){
                    //echo "HoraChecada:    ".$checada_salida->HORA."       HoraSalida: " .$value->prueba."<br>";
                    if($checada_salida->HORA>$value->FinChecarSalida)
                        $asistencia[$i]['checado_salida'] =$checada_salida->HORA. " (Verifique Su Registro)";
                    else
                        $asistencia[$i]['checado_salida'] =$checada_salida->HORA;
                }
               if(is_null($asistencia[$i]['checado_salida'])){
                     if(is_null($checada_extra)){
                        $asistencia[$i]['checado_salida'] ="SIN REGISTRO";
                        }
                    else{
                        echo$asistencia[$i]['checado_salida'] = $impr ."<br>";
                        $asistencia[$i]['checado_salida'] = $impr;
                           
                        }
                        
                   }
               
            }
         }
        $ps=$ps/60;
      
        $resumen = array(['Pase de Salida'=>$ps,'Retardo Mayor'=>$rm,'Retardo Menor'=>$rme,'Vacaciones 2019 Primavera-Verano'=> $vac19_1,'Vacaciones 2019 Invierno'=>$vac19_2,'Vacaciones 2018 Primavera-Verano'=>$vac18_1,'Vacaciones 2018 Invierno'=>$vac18_2,'Dia Economico'=>$diaE,'Onomastico'=>$ono,'Omision Entrada'=> $oE,'oS'=>$oS,'Falta'=>$falta,'Vacaciones Mediano Riesgo'=>$vacMR,'Vacaciones Extra Ordinarias'=>$vacEx, "vac2019_1"=>"la cagas"]);
      

      return view('home',['asistencia' => $asistencia, "resumen"=>$resumen]);
      //return $asistencia;
    }

    public function consulta_checadas(Request $request)
    {
        $arreglo_fecha = array();
        $fecha_actual = Carbon::now();
        $anio_actual = $fecha_actual->year;
        $mes_actual = $fecha_actual->month;
        $dia_actual = $fecha_actual->day;
        $Rfc = $request->rfc;
        
        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;
        $desc = $this->decrypt($Rfc);

           // DB::enableQueryLog();
        $validacion = DB::TABLE("userinfo")
            ->where("userinfo.TITLE", "=",  $desc)
            ->first();
            $empleado = DB::TABLE("userinfo")
                            ->join("user_of_run", "userinfo.USERID", "=", "user_of_run.USERID")
                            ->join("num_run", "num_run.NUM_RUNID", "=", "user_of_run.NUM_OF_RUN_ID")
                            ->join("num_run_deil", "num_run_deil.NUM_RUNID", "=", "num_run.NUM_RUNID")
                            ->join("schclass", "schclass.schClassid", "=", "num_run_deil.SCHCLASSID")
                            ->select("userinfo.name"
                                    ,"num_run.name as horario"
                                    ,DB::RAW("CONVERT(varchar, num_run.STARTDATE, 112) as fecha_inicial")
                                    ,DB::RAW("CONVERT(varchar,num_run.ENDDATE, 112) as fecha_final")
                                    ,"num_run_deil.SDAYS as dia"
                                    ,"schclass.schName as Detalle_Horario"
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.StartTime,108) as HoraInicio")
                                    ,"schclass.EndTime as HoraFin"
                                    ,"schclass.LateMinutes as Tolerancia"
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckInTime1, 108) as InicioChecarEntrada")
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckInTime2, 108) as FinChecarEntrada")
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckOutTime1, 108) as InicioChecarSalida")
                                    ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckOutTime2, 108) as FinChecarSalida")
                                    ,"schclass.CheckOutTime2 as prueba"
                                    ,DB::RAW("left(schclass.schClassId, 2) as idH")
                                    )
                           
                            ->where("userinfo.TITLE", "=",  $desc)
                            ->get();

            $checa_dias = DB::table("user_speday")
            ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
            ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")                            
           ->where("TITLE", "=",  $request -> get('rfc'))   
           ->whereBetween(DB::RAW("DATEPART(DW,STARTSPECDAY)"),[2,6])
           ->groupBy('leaveclass.LeaveId','leaveclass.LeaveName') 
           
            ->select("leaveclass.LeaveName as Exepcion"                            
               ,'leaveclass.LeaveId AS TIPO'
               ,DB::RAW("count(leaveclass.LeaveId) as total")                               
            )
            //'STARTSPECDAY AS INI','ENDSPECDAY AS FIN',
            ->get();
                $vac19_1=0;
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
                    
                    case 6:                                       
                        $diaE=$tipos->total;
                        break;
                    
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
                    default:
                        $impr="";
                        break;
                }                                                           
            }
                       //$query = DB::getQueryLog();
                        //dd($query);
        $arreglo_dias = array();
        for($dias = 1; $dias<8; $dias++)
            $arreglo_dias[$dias] = null;
        foreach ($empleado as $key => $value) {
            $arreglo_dias[$value->dia] = $value;
        }
        if($inicio == null){
            $f_ini = Carbon::now()->startOfMonth();
            $f_fin = Carbon::now();
        }else{
            $f_ini= new Carbon($inicio);
            $f_fin= new Carbon($fin);
        }
        $diff= $f_ini->diffInDays($f_fin)+1;
        $asistencia = array();
        $rm=0;
        $rme=0;        
        $oE=0;
        $oS=0;
        $falta=0;
       
        for($i = 1; $i<=$diff; $i++)
        {
            $fecha_evaluar = $fecha_actual;
            $fecha_evaluar->day = $i;
            //echo $fecha_evaluar."<br>";
            
            if($arreglo_dias[$fecha_evaluar->dayOfWeekIso])
            {
                $asistencia[$i]['fecha'] = $fecha_evaluar->format('Y-m-d');
                $fecha_eval = $asistencia[$i]['fecha'];

                $inicio=$fecha_eval." ".$value->InicioChecarEntrada;
                $final=$fecha_eval." 11:00:00";

                $checada_entrada = DB::table("checkinout")
                        ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=",  $desc)
                      ->whereBetween("CHECKTIME", [$fecha_eval."T".$value->InicioChecarEntrada.":00.000", $fecha_eval."T".$value->FinChecarEntrada.":00.000"])
                       
                      ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))
                     
                        ->first();
                   


                $checada_salida = DB::table("checkinout")
                         ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=",  $desc)
                       
                        //->whereBetween("CHECKTIME", [$fecha_eval."T".$value->InicioChecarSalida.":00.000", $fecha_eval."T23:00:00.000"])
                        ->whereBetween("CHECKTIME", [$fecha_eval."T".$value->InicioChecarSalida.":00.000", $fecha_eval."T".$value->FinChecarSalida.":00.000"])
                        ->select(DB::RAW("MIN(CONVERT(nvarchar(5), CHECKTIME, 108)) AS HORA"))
                        ->first();

                      
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
                        )
                        ->groupBy('leaveclass.LeaveName','user_speday.ENDSPECDAY','user_speday.STARTSPECDAY','leaveclass.LeaveId')

                        ->first();
                     
                        if(is_null($checada_extra)){
                            "checada_extra";
                        }
                        else{
                            switch($checada_extra->TIPO){
                            case 1:
                              
                                $impr=$checada_extra->HORA. " ".$checada_extra->Exepcion;
                                $ps=$ps+$checada_extra->DIFHORA;
                                break;
                            case 2:
                              
                                $dia_ini=$checada_extra->INI;
                                $dia_fin=$checada_extra->FIN;
                                $dif_dia=$checada_extra->DIFDIA;
                              
                                $impr= "Vacaciones 2019 1er Periodo";
                               
                                break;
                            case 3:
                                $impr= "Comision";
                                break;
                            case 4:
                                $impr= "Omision Salida";
                                $oS=$oS+1;
                                break;
                            case 5:
                                $impr="Omision Entrada";
                                $oE=$oE+1;
                                break;
                             case 6:
                                $impr="Dia Economico";
                                
                                break;
                             case 8:
                                $impr="Licencia Medica";
                                break;
                             case 10:
                                $impr= "Onomastico";
                                
                                break;
                            case 11:
                                $impr="Vacaciones 2018 1er Periodo";
                                
                                break;
                            case 12:
                                $impr="Vacaciones 2018 2do Periodo";
                                
                                break;
                            case 13:
                                $impr="Vacaciones 2019 2do Periodo";
                                
                                break;
                            case 14:
                                $impr="Reposicion";
                                break;
                            case 15:
                                $impr="Vacaciones Mediano Riesgo";
                                
                                break;
                            case 15:
                                $impr="Vacaciones Extra Ordinarias";
                                
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
                if(isset($checada_entrada))
                {
                    $formato_checado = new Carbon($fecha_eval." ".$checada_entrada->HORA);
                    $hora_con_tolerancia = new Carbon($fecha_eval." ".$value->HoraInicio);
                    $hora_permitida = new Carbon($fecha_eval." ".$value->FinChecarEntrada);
                    $tolerancia=$hora_con_tolerancia->addMinutes($value->Tolerancia);

                            if($value->idH==40)
                                $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA;
                            else{
                                if ($formato_checado>($tolerancia)){
                                  if ($formato_checado->diffInMinutes($tolerancia) >= 1 && $formato_checado->diffInMinutes($tolerancia)<=15){
                                        $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA." Retardo Menor";
                                        $rme=$rme+1;
                                    }
                                    if ($formato_checado->diffInMinutes($tolerancia) >= 16){
                                        $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA." Retardo Mayor";
                                        $rm=$rm+1;
                                    }
                                }
                                else
                                $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA;
                        }

                }
                if(is_null($asistencia[$i]['checado_entrada'])){
                    $asistencia[$i]['checado_entrada'] = "SIN REGISTRO";
                 if(is_null($checada_extra)){
                    $asistencia[$i]['checado_entrada'] = "SIN REGISTRO";
                    $falta = $falta+1;
                    }
                else{         
                        if ($checada_extra->TIPO==1){   
                            $asistencia[$i]['checado_entrada'] = "SIN REGISTRO";
                        }
                        else{
                            $asistencia[$i]['checado_entrada'] = $impr;
                        }
                        //$falta-=1;
                       
                       
                    }
             }
               if(isset($checada_salida)){
                   
                   if($checada_salida->HORA>$value->FinChecarSalida)
                       $asistencia[$i]['checado_salida'] =$checada_salida->HORA. " (Verifique Su Registro)";
                   else
                       $asistencia[$i]['checado_salida'] =$checada_salida->HORA;
               }
              if(is_null($asistencia[$i]['checado_salida'])){
                    if(is_null($checada_extra)){
                       $asistencia[$i]['checado_salida'] ="SIN REGISTRO";
                       }
                   else{
                       $asistencia[$i]['checado_salida'] = $impr;
                           $ini = new Carbon($checada_extra->INI);
                           $fin = new Carbon($checada_extra->FIN);
                       }
                       
                  }
              
           }
        }
        $ps=$ps/60;
       
        $resumen = array(['Pase de Salida'=>$ps,'Retardo Mayor'=>$rm,'Retardo Menor'=>$rme,'Vacaciones 2019 Primavera-Verano'=> $vac19_1,'Vacaciones 2019 Invierno'=>$vac19_2,'Vacaciones 2018 Primavera-Verano'=>$vac18_1,'Vacaciones 2018 Invierno'=>$vac18_2,'Dia Economico'=>$diaE,'Onomastico'=>$ono,'Omision Entrada'=> $oE,'oS'=>$oS,'Falta'=>$falta,'Vacaciones Mediano Riesgo'=>$vacMR,'Vacaciones Extra Ordinarias'=>$vacEx, "vac2019_1"=>"la cagas"]);
       
        return response()->json(["data" => $asistencia, "resumen" => $resumen, "validacion"=> $validacion]);
      
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
