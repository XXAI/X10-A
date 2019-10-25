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

           // DB::enableQueryLog();
        $empleado = DB::TABLE("userinfo")
                            ->join("user_of_run", "userinfo.USERID", "=", "user_of_run.USERID")
                            ->join("num_run", "num_run.NUM_RUNID", "=", "user_of_run.NUM_OF_RUN_ID")
                            ->join("num_run_deil", "num_run_deil.NUM_RUNID", "=", "num_run.NUM_RUNID")
                            ->join("schclass", "schclass.schClassid", "=", "num_run_deil.SCHCLASSID")
                            ->select("userinfo.name"
                                    ,"num_run.name as horario"
                                    ,DB::RAW("SUBSTRING(num_run.STARTDATE, 1, 10) as fecha_inicial")
                                    ,DB::RAW("SUBSTRING(num_run.ENDDATE, 1, 10) as fecha_final")
                                    ,"num_run_deil.SDAYS as dia"
                                    ,"schclass.schName as Detalle_Horario"
                                    ,DB::RAW("SUBSTRING(schclass.StartTime, 12, 5) as HoraInicio")
                                    ,"schclass.EndTime as HoraFin"
                                    ,"schclass.LateMinutes as Tolerancia"
                                    ,DB::RAW("SUBSTRING(schclass.CheckInTime1, 12, 5) as InicioChecarEntrada")
                                    ,DB::RAW("SUBSTRING(schclass.CheckInTime2, 12, 5) as FinChecarEntrada")
                                    ,DB::RAW("SUBSTRING(schclass.CheckOutTime1, 12, 5) as InicioChecarSalida")
                                    ,DB::RAW("SUBSTRING(schclass.CheckOutTime2, 12, 5) as FinChecarSalida")
                                    ,DB::RAW("left(schclass.schName, 2) as NombreHorario")
                                    )
                            //->where("userinfo.USERID", "=",  $request -> get('id'))
                            ->where("userinfo.TITLE", "=",  $request -> get('rfc'))
                            ->get();

                       //$query = DB::getQueryLog();
                        //dd($query);


        $arreglo_dias = array();
        for($dias = 1; $dias<8; $dias++)
            $arreglo_dias[$dias] = null;

        foreach ($empleado as $key => $value) {
            $arreglo_dias[$value->dia] = $value;
        }
        $f_ini= new Carbon($request -> get('trip-start'));
        $f_fin= new Carbon($request -> get('trip-fin'));
         $diff= $f_ini->diffInDays($f_fin);
        $asistencia = array();
        $rm=0;
        $rme=0;
        $ps=0;
        $vac19_1=0;
        $vac19_2=0;
        $vac18_1=0;
        $vac18_2=0;
        $diaE=0;
        $ono=0;
        $oE=0;
        $oS=0;
        $falta=0;
        $vacMR=0;
        $vacEx=0;
        for($i = 1; $i<=30; $i++)
        {
            $fecha_evaluar = $fecha_actual;
            $fecha_evaluar->day = $i;

            if($arreglo_dias[$fecha_evaluar->dayOfWeekIso])
            {
                $asistencia[$i]['fecha'] = $fecha_evaluar->format('Y-m-d');
                $fecha_eval = $asistencia[$i]['fecha'];

                $inicio=$fecha_eval." ".$value->InicioChecarEntrada;
                $final=$fecha_eval." 11:00:00";

                $checada_entrada = DB::table("checkinout")
                        ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=",  $request -> get('rfc'))
                      ->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarEntrada,
                        $fecha_eval." 11:00:00"])
                        ->select(DB::RAW("SUBSTRING(MIN(CHECKTIME), 12, 5) AS HORA"))
                        ->first();


                $checada_salida = DB::table("checkinout")
                         ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=",  $request -> get('rfc'))
                        ->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarSalida, $fecha_eval." ".$value->FinChecarSalida])
                        ->select(DB::RAW("SUBSTRING(MIN(CHECKTIME), 12, 5) AS HORA"))
                        ->first();

                       // $checada_extra="";
                       // DB::enableQueryLog();

                        $checada_extra = DB::table("user_speday")
                        ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
                        ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")
                       ->where("TITLE", "=",  $request -> get('rfc'))
                        //->whereBetween("STARTSPECDAY", [$fecha_eval." 00:00:00",$fecha_eval." 23:59:00"])
                        //->orWhereBetween("ENDSPECDAY", [$fecha_eval." 00:00:00",$fecha_eval." 23:59:00"])
                       ->where("STARTSPECDAY",'<=',$fecha_eval." 23:59:00")
                        ->where("ENDSPECDAY",'>=',$fecha_eval." 00:00:00")

                       // ->orWhereBetween("ENDSPECDAY",[$fecha_eval." 00:00:00",$fecha_eval." 23:59:00"])
                        //->orWhereBetween("STARTSPECDAY",[$fecha_eval." 00:00:00",$fecha_eval." 23:59:00"])
                        ->select("leaveclass.LeaveName as Exepcion"
                            ,DB::RAW("SUBSTRING(MIN(STARTSPECDAY), 12, 5) AS HORA")
                           ,DB::RAW("TIMESTAMPDIFF(MINUTE,STARTSPECDAY, ENDSPECDAY) AS DIFHORA")
                           ,DB::RAW("TIMESTAMPDIFF(DAY,STARTSPECDAY, ENDSPECDAY) AS DIFDIA")
                           ,'STARTSPECDAY AS INI','ENDSPECDAY AS FIN','leaveclass.LeaveId AS TIPO'
                        )
                        ->first();
                       // $query = DB::getQueryLog();
                      //  dd($query);

                        switch($checada_extra->TIPO){
                            case 1:
                              //  echo $fecha_eval."  Pase de Salida"."<br>";
                                $impr=$checada_extra->HORA. " ".$checada_extra->Exepcion;
                                $ps=$ps+$checada_extra->DIFHORA;
                                break;
                            case 2:
                               // echo $fecha_eval. "   Vacaciones 2019 1er Periodo"."<br>";
                                $dia_ini=$checada_extra->INI;
                                $dia_fin=$checada_extra->FIN;
                                $dif_dia=$checada_extra->DIFDIA;
                               // echo "INICIO  ".$dia_ini."    FIN  ".$dia_fin."    DIFERENCIA  ".$dif_dia;
                                $impr= "Vacaciones 2019 1er Periodo";
                                $vac19_1=$vac19_1+1;
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
                                $diaE=$diaE+1;
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
                                $vac18_1=$vac18_1+1;
                                break;
                            case 12:
                                $impr="Vacaciones 2018 2do Periodo";
                                $vac18_2=$vac18_2+1;
                                break;
                            case 13:
                                $impr="Vacaciones 2019 2do Periodo";
                                $vac19_2=$vac19_2+1;
                                break;
                            case 14:
                                $impr="Reposicion";
                                break;
                            case 15:
                                $impr="Vacaciones Mediano Riesgo";
                                $vacMR=$vacMR+1;
                                break;
                            case 15:
                                $impr="Vacaciones Extra Ordinarias";
                                $vacEx=$vacEx+1;
                                break;
                            default:
                                $impr="";
                                break;
                        }
                         $hora_extra=$checada_extra->HORA;
                if(isset($checada_entrada))
                {
                    $formato_checado = new Carbon($fecha_eval." ".$checada_entrada->HORA);
                    $hora_con_tolerancia = new Carbon($fecha_eval." ".$value->HoraInicio);
                    $hora_permitida = new Carbon($fecha_eval." ".$value->FinChecarEntrada);
                    $tolerancia=$hora_con_tolerancia->addMinutes($value->Tolerancia);

                            if ($formato_checado>($tolerancia)){
                              if ($formato_checado->diffInMinutes($tolerancia) >= 1 && $formato_checado->diffInMinutes($tolerancia)<=25){
                                    $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA." Retardo Menor";
                                    $rme=$rme+1;
                                }
                                if ($formato_checado->diffInMinutes($tolerancia) >= 26){
                                    $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA." Retardo Mayor";
                                    $rm=$rm+1;
                                }
                            }
                            else
                            $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA;

                }
                if(is_null($asistencia[$i]['checado_entrada'])){
                    if(($hora_extra)<>"")
                        $asistencia[$i]['checado_entrada'] = $impr;
                    else
                    $asistencia[$i]['checado_entrada'] = "SIN REGISTRO";
                    $falta = $falta+1;
                 }
                if(isset($checada_salida))
                    $asistencia[$i]['checado_salida'] =$checada_salida->HORA;
               if(is_null($asistencia[$i]['checado_salida'])){
                    if(($hora_extra)<>""){
                        $asistencia[$i]['checado_salida'] = $impr;
                        $ini = new Carbon($checada_extra->INI);
                        $fin = new Carbon($checada_extra->FIN);
                    }
                    else
                       $asistencia[$i]['checado_salida'] ="SIN REGISTRO";
               }
            }
            /*else{
                $asistencia[$i] = null;
            }*/
         }
        $ps=$ps/60;
        echo $falta;
        //echo $vac19_1;
      /* $asistencia[$i]['ps']=$ps;
        $asistencia[$i]['rm']=$rm;
        $asistencia[$i]['rme']=$rme;
        $asistencia[$i]['vac19_1']= $vac19_1;
        $asistencia[$i]['vac19_2']=$vac19_2;
        $asistencia[$i]['vac18_1']=$vac18_1;
        $asistencia[$i]['vac18_2']=$vac18_2;
        $asistencia[$i]['diaE']=$diaE;
        $asistencia[$i]['ono']=$ono;
       $asistencia[$i]['oE']= $oE;
        $asistencia[$i]['oS']=$oS;
        $asistencia[$i]['falta']=$falta;
        $asistencia[$i]['vacMR']=$vacMR;
        $asistencia[$i]['vacEx']=$vacEx;*/

      return view('home',['asistencia' => $asistencia]);
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
                                    ,DB::RAW("SUBSTRING(num_run.STARTDATE, 1, 10) as fecha_inicial")
                                    ,DB::RAW("SUBSTRING(num_run.ENDDATE, 1, 10) as fecha_final")
                                    ,"num_run_deil.SDAYS as dia"
                                    ,"schclass.schName as Detalle_Horario"
                                    ,DB::RAW("SUBSTRING(schclass.StartTime, 12, 5) as HoraInicio")
                                    ,"schclass.EndTime as HoraFin"
                                    ,"schclass.LateMinutes as Tolerancia"
                                    ,DB::RAW("SUBSTRING(schclass.CheckInTime1, 12, 5) as InicioChecarEntrada")
                                    ,DB::RAW("SUBSTRING(schclass.CheckInTime2, 12, 5) as FinChecarEntrada")
                                    ,DB::RAW("SUBSTRING(schclass.CheckOutTime1, 12, 5) as InicioChecarSalida")
                                    ,DB::RAW("SUBSTRING(schclass.CheckOutTime2, 12, 5) as FinChecarSalida")
                                    ,"schclass.CheckOutTime2 as prueba"
                                    ,DB::RAW("left(schclass.schClassId, 2) as idH")
                                    )
                            //->where("userinfo.USERID", "=",  $request -> get('id'))
                            ->where("userinfo.TITLE", "=", $desc)
                            ->get();

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
        $ps=0;
        $vac19_1=0;
        $vac19_2=0;
        $vac18_1=0;
        $vac18_2=0;
        $diaE=0;
        $ono=0;
        $oE=0;
        $oS=0;
        $falta=0;
        $vacMR=0;
        $vacEx=0;
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
                        ->where("TITLE", "=", $desc)
                      ->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarEntrada,
                        $fecha_eval." 11:00:00"])
                        ->select(DB::RAW("SUBSTRING(MIN(CHECKTIME), 12, 5) AS HORA"))
                        ->first();


                $checada_salida = DB::table("checkinout")
                         ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=", $desc)
                        //->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarSalida, $fecha_eval." ".$value->FinChecarSalida])
                        ->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarSalida, $fecha_eval." 23:00:00"])
                        ->select(DB::RAW("SUBSTRING(MIN(CHECKTIME), 12, 5) AS HORA"))
                        ->first();

                       // $checada_extra="";
                       // DB::enableQueryLog();

                        $checada_extra = DB::table("user_speday")
                        ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
                        ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")
                       ->where("TITLE", "=", $desc)
                       ->where("STARTSPECDAY",'<=',$fecha_eval." 23:59:00")
                        ->where("ENDSPECDAY",'>=',$fecha_eval." 00:00:00")
                        ->select("leaveclass.LeaveName as Exepcion"
                            ,DB::RAW("SUBSTRING(MIN(STARTSPECDAY), 12, 5) AS HORA")
                           ,DB::RAW("TIMESTAMPDIFF(MINUTE,STARTSPECDAY, ENDSPECDAY) AS DIFHORA")
                           ,DB::RAW("TIMESTAMPDIFF(DAY,STARTSPECDAY, ENDSPECDAY) AS DIFDIA")
                           ,'STARTSPECDAY AS INI','ENDSPECDAY AS FIN','leaveclass.LeaveId AS TIPO'
                        )
                        ->first();
                       // $query = DB::getQueryLog();
                      //  dd($query);

                        switch($checada_extra->TIPO){
                            case 1:
                              //  echo $fecha_eval."  Pase de Salida"."<br>";
                                $impr=$checada_extra->HORA. " ".$checada_extra->Exepcion;
                                $ps=$ps+$checada_extra->DIFHORA;
                                break;
                            case 2:
                               // echo $fecha_eval. "   Vacaciones 2019 1er Periodo"."<br>";
                                $dia_ini=$checada_extra->INI;
                                $dia_fin=$checada_extra->FIN;
                                $dif_dia=$checada_extra->DIFDIA;
                               // echo "INICIO  ".$dia_ini."    FIN  ".$dia_fin."    DIFERENCIA  ".$dif_dia;
                                $impr= "Vacaciones 2019 1er Periodo";
                                $vac19_1=$vac19_1+1;
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
                                $diaE=$diaE+1;
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
                                $vac18_1=$vac18_1+1;
                                break;
                            case 12:
                                $impr="Vacaciones 2018 2do Periodo";
                                $vac18_2=$vac18_2+1;
                                break;
                            case 13:
                                $impr="Vacaciones 2019 2do Periodo";
                                $vac19_2=$vac19_2+1;
                                break;
                            case 14:
                                $impr="Reposicion";
                                break;
                            case 15:
                                $impr="Vacaciones Mediano Riesgo";
                                $vacMR=$vacMR+1;
                                break;
                            case 15:
                                $impr="Vacaciones Extra Ordinarias";
                                $vacEx=$vacEx+1;
                                break;
                            default:
                                $impr="";
                                break;
                        }
                         $hora_extra=$checada_extra->HORA;
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
                                  if ($formato_checado->diffInMinutes($tolerancia) >= 1 && $formato_checado->diffInMinutes($tolerancia)<=25){
                                        $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA." Retardo Menor";
                                        $rme=$rme+1;
                                    }
                                    if ($formato_checado->diffInMinutes($tolerancia) >= 26){
                                        $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA." Retardo Mayor";
                                        $rm=$rm+1;
                                    }
                                }
                                else
                                $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA;
                        }

                }
                if(is_null($asistencia[$i]['checado_entrada'])){
                    if(($hora_extra)<>"")
                        $asistencia[$i]['checado_entrada'] = $impr;
                    else
                    $asistencia[$i]['checado_entrada'] = "SIN REGISTRO";
                    $falta = $falta+1;
                 }
                if(isset($checada_salida)){
                    //echo "HoraChecada:    ".$checada_salida->HORA."       HoraSalida: " .$value->prueba."<br>";
                    if($checada_salida->HORA>$value->FinChecarSalida)
                        $asistencia[$i]['checado_salida'] =$checada_salida->HORA. " (Verifique Su Registro)";
                    else
                        $asistencia[$i]['checado_salida'] =$checada_salida->HORA;
                }
               if(is_null($asistencia[$i]['checado_salida'])){
                    if(($hora_extra)<>""){
                        $asistencia[$i]['checado_salida'] = $impr;
                        $ini = new Carbon($checada_extra->INI);
                        $fin = new Carbon($checada_extra->FIN);
                    }
                    else
                       $asistencia[$i]['checado_salida'] ="SIN REGISTRO";
               }
            }
         }
        $ps=$ps/60;
       // echo $falta;
        //echo $vac19_1;
        $resumen = array(['ps'=>$ps,'rm'=>$rm,'rme'=>$rme,'vac19_1'=> $vac19_1,'vac19_2'=>$vac19_2,'vac18_1'=>$vac18_1,
            'vac18_2'=>$vac18_2,'diaE'=>$diaE,'ono'=>$ono,'oE'=> $oE,'oS'=>$oS,'falta'=>$falta,'vacMR'=>$vacMR,'vacEx'=>$vacEx]);
       /* $asistencia[$i]['ps']=$ps;
        $asistencia[$i]['rm']=$rm;
        $asistencia[$i]['rme']=$rme;
        $asistencia[$i]['vac19_1']= $vac19_1;
        $asistencia[$i]['vac19_2']=$vac19_2;
        $asistencia[$i]['vac18_1']=$vac18_1;
        $asistencia[$i]['vac18_2']=$vac18_2;
        $asistencia[$i]['diaE']=$diaE;
        $asistencia[$i]['ono']=$ono;
        $asistencia[$i]['oE']= $oE;
        $asistencia[$i]['oS']=$oS;
        $asistencia[$i]['falta']=$falta;
        $asistencia[$i]['vacMR']=$vacMR;
        $asistencia[$i]['vacEx']=$vacEx;*/

        return response()->json(["data" => $asistencia, "resumen" => $resumen, "validacion"=> $validacion]);
      //return $asistencia;
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
