<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon, DB;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $ch = curl_init();
        $rfc = $request->buscar;
        $header[]         = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER,     $header);
        curl_setopt($ch, CURLOPT_URL, env('URL_RH').'?buscar='.$rfc);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);

        $api_response = curl_exec($ch);

        return $api_response[0];

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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function consulta_checadas(Request $request)
    {
        $arreglo_fecha = array();
        $fecha_actual = Carbon::now();
        $anio_actual = $fecha_actual->year;
        $mes_actual = $fecha_actual->month;
        $Rfc = $request->rfc;

        $desc = $this->decrypt($Rfc);

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
                            ->where("userinfo.TITLE", "=",  $desc)
                            ->get();

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
                        ->where("TITLE", "=",  $desc)

                      /*  ->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarEntrada, $fecha_eval." ".$value->FinChecarEntrada])*/
                      ->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarEntrada,
                        $fecha_eval." 11:00:00"])
                        ->select(DB::RAW("SUBSTRING(MIN(CHECKTIME), 12, 5) AS HORA"))
                        ->first();



                $checada_salida = DB::table("checkinout")
                         ->join("USERINFO", "USERINFO.USERID", "=", "checkinout.USERID")
                        ->where("TITLE", "=",  $desc)
                        ->whereBetween("CHECKTIME", [$fecha_eval." ".$value->InicioChecarSalida, $fecha_eval." ".$value->FinChecarSalida])
                        ->select(DB::RAW("SUBSTRING(MIN(CHECKTIME), 12, 5) AS HORA"))
                        ->first();

                       // $checada_extra="";
                        $checada_extra = DB::table("user_speday")
                        ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")
                         ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
                        ->where("TITLE", "=",  $desc)
                        //->where("USERID", "=", $request -> get('id'))
                        ->whereBetween("STARTSPECDAY", [$fecha_eval." 00:00:00",$fecha_eval." 23:59:00"])
                        ->select("leaveclass.LeaveName as Exepcion"
                            ,DB::RAW("SUBSTRING(MIN(STARTSPECDAY), 12, 5) AS HORA")
                            ,'STARTSPECDAY AS INI','ENDSPECDAY AS FIN'
                        )
                        ->first();
                         $hora_extra=$checada_extra->HORA;

                        //echo($i.$hora_extra);

                       /*
                        DB::enableQueryLog();
                        $query = DB::getQueryLog();
                        dd($query);*/

                if(isset($checada_entrada))
                {
                    $formato_checado = new Carbon($fecha_eval." ".$checada_entrada->HORA);
                    $hora_con_tolerancia = new Carbon($fecha_eval." ".$value->HoraInicio);
                    $hora_permitida = new Carbon($fecha_eval." ".$value->FinChecarEntrada);
                    $tolerancia=$hora_con_tolerancia->addMinutes($value->Tolerancia);

                    //return $formato_checado->diffInMinutes($tolerancia);
                    //return $tolerancia;

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
                if(is_null($asistencia[$i]['checado_entrada']))
                $asistencia[$i]['checado_entrada'] ="NO CHECO";

                if(isset($checada_salida))
                    $asistencia[$i]['checado_salida'] =$checada_salida->HORA;

               if(is_null($asistencia[$i]['checado_salida'])){
                    if(($hora_extra)<>""){
                        $asistencia[$i]['checado_salida'] = $checada_extra->HORA. " ".$checada_extra->Exepcion;
                        $paseSalida = new Carbon($fecha_eval." ".$checada_extra->HORA);
                        $salida = new Carbon($fecha_eval." ".$value->FinChecarSalida);
                        //return $checada_extra//->INI->diffInMinutes($checada_extra->FIN);

                    }
                    else{

                       $asistencia[$i]['checado_salida'] ="NO CHECO";
                    }

               }



            }
            else{
                $asistencia[$i] = null;
            }
        }
        //echo "Usted Tiene ".$rm." Retardo(s) Mayor"."<br>";
       // echo $checada_entrada->HORA. "Tolerancia".$hora_con_tolerancia;

        return response()->json(['datos_checada' => $asistencia]);
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
