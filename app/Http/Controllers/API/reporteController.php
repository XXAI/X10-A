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
                                    )
                            ->where("userinfo.USERID", "=", 1274)->get();

        $arreglo_dias = array();
        for($dias = 1; $dias<8; $dias++)
            $arreglo_dias[$dias] = null;
        
        foreach ($empleado as $key => $value) {
            $arreglo_dias[$value->dia] = $value;    
        }                    

        $asistencia = array();
        for($i = 1; $i<33; $i++)
        {
            $fecha_evaluar = $fecha_actual;
            $fecha_evaluar->day = $i;
            if($arreglo_dias[$fecha_evaluar->dayOfWeekIso])
            {
                $asistencia[$i]['fecha'] = $fecha_evaluar->format('Y-m-d');
                $fecha_eval = $asistencia[$i]['fecha'];
                //$asistencia[$i]['fecha'] = $fecha_evaluar->format('Y-m-d');
                $checada_entrada = DB::table("checkinout")
                        ->where("USERID", "=", 1274)
                        ->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarEntrada, $fecha_eval." ".$value->FinChecarEntrada])
                        ->select(DB::RAW("SUBSTRING(MIN(CHECKTIME), 12, 5) AS HORA"))
                        ->first();
                
                $checada_salida = DB::table("checkinout")
                        ->where("USERID", "=", 1274)
                        ->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarSalida, $fecha_eval." ".$value->FinChecarSalida])
                        ->select(DB::RAW("SUBSTRING(MIN(CHECKTIME), 12, 5) AS HORA"))
                        ->first();      
                
                
                if(isset($checada_entrada))
                {
                    $asistencia[$i]['checado_entrada'] = $checada_entrada->HORA;
                    
                    $formato_checado = new Carbon($fecha_eval." ".$checada_entrada->HORA);   
                    $hora_con_tolerancia = new Carbon($fecha_eval." ".$value->HoraInicio);        
                    $hora_con_tolerancia->addMinutes($value->Tolerancia);        
                    $hora_retardo_menor = $hora_con_tolerancia->addMinutes(15);
                    $hora_retardo_mayor = $hora_retardo_menor->addMinutes(10);
                    
                    $asistencia[$i]['checado'] = 0;
                    $asistencia[$i]['retardo_menor'] = 0;
                    $asistencia[$i]['retardo_mayor'] = 0;
                    
                    //echo $formato_checado->diffInMinutes($hora_retardo_menor)."<br>";
                    if($formato_checado->diffInMinutes($hora_con_tolerancia) > 24)
                        $asistencia[$i]['checado'] = 1;   
                    else if($formato_checado->diffInMinutes($hora_retardo_menor) <= 24 && $formato_checado->diffInMinutes($hora_retardo_menor) > 9)
                        $asistencia[$i]['retardo_menor'] = 1;
                    else if($formato_checado->diffInMinutes($hora_retardo_mayor) <= 9)
                        $asistencia[$i]['retardo_mayor'] = 1;
                }
                if(isset($checada_salida))
                    $asistencia[$i]['checado_salida'] = $checada_salida->HORA;
                
            }else{
                $asistencia[$i] = null;
            }
            //$checada = DB::table("checkinout")->get();
            //echo $fecha_evaluar->format('y-m-d')."<br>";
        }

        return $asistencia;
    }
}
