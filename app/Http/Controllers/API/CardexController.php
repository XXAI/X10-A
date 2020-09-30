<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon, DB, PDF, View, Dompdf\Dompdf;;

use App\Models\Usuarios;
use App\Models\ReglasHorarios;
use App\Models\Festivos;
use App\Models\SalidaAutorizada;
use App\Models\Departamentos;
use Illuminate\Support\Facades\Input;

class CardexController extends Controller
{
    public function index(Request $request)
    {
        $parametros = Input::all();
        
        $empleados = Usuarios::leftJoin("empleados_sirh", "empleados_sirh.rfc", "=", "USERINFO.TITLE")
                                ->whereNull("USERINFO.state")
                                ->whereIn("USERINFO.FPHONE", ['CSSSA017213','CSSSA017324'])
                                ->Where(function($query2)use($parametros){
                                    $query2->where('Name','LIKE','%'.$parametros['filtro'].'%')
                                            ->orWhere('TITLE','LIKE','%'.$parametros['filtro'].'%')
                                            ->orWhere('Badgenumber', $parametros['filtro']);
                                })
                                ->get();
        
        return response()->json(["usuarios" => $empleados]);
    }

    public function reporteCardex(Request $request)
    {
        $parametros = Input::all();
        $datos = $this->claseAsistencia($parametros['empleado']);
        ##return response()->json(["usuarios" => $datos]);
        $empleados = Usuarios::leftJoin("empleados_sirh", "empleados_sirh.rfc", "=", "USERINFO.TITLE")
                                ->whereNull("USERINFO.state")
                                ->whereIn("USERINFO.FPHONE", ['CSSSA017213','CSSSA017324'])
                                ->Where('Badgenumber', $parametros['empleado'])
                                ->first();

        $empleados->asistencia = $datos['datos'];    
        $empleados->jornada = $datos['horario'];    
        //return response()->json(["usuarios" => $empleados]);                    
        $pdf = PDF::loadView('reportes//reporte-cardex', ['empleados' => $empleados]);
        $pdf->setPaper('LETTER', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream('Reporte-Cardex.pdf');
    }

    function claseAsistencia($empleado)
    {
        $reglas     = ReglasHorarios::where("CheckIn", "=", 1)->get();

        $resultado = [];
        $arreglo_reglas = array();
        foreach ($reglas as $key => $value) { $arreglo_reglas[$value->schClassid] = $value;  }
        
        $fecha_limite_actual = Carbon::now();
        $anio_reporte = $fecha_limite_actual->year;
        
        $mes_reporte = 1;
        $mes_actual = $fecha_limite_actual->month;
        /*while($mes_reporte <= 1 && $mes_reporte <= $mes_actual)
        {*/
            //$resultado[$mes_reporte]['dias'] = [];

            $parametro_inicial = Carbon::now();
            $parametro_inicial->month = 1; 
            $parametro_inicial->day = 1; 
            $fecha_inicio = $parametro_inicial->format('Y-m-d')."T00:00:00";
            
            $parametro_final = Carbon::now();
            $fecha_fin = $parametro_final->format('Y-m-d').'T23:59:59';
            #Obtenemos los dias Festivos
            $festivos   = Festivos::where("STARTTIME", ">=", $fecha_inicio)->where("STARTTIME", "<=", $fecha_fin)->get();
            $arreglo_festivos = array();
            if(count($festivos) > 0){ $arreglo_festivos = $this->festivos($festivos); }

            #Obtenemos salidas autorizadas
            $salidas   = SalidaAutorizada::where("STARTTIME", ">=", $fecha_inicio)->where("STARTTIME", "<=", $fecha_fin)->get();
            $arreglo_salidas = array();
            if(count($salidas) > 0){ $arreglo_salidas = $this->salidas($salidas); }

            #Obtenemos datos del empleado
            $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'dias_otorgados.siglas', 'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
            }, 'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("STARTDATE", "<=", $fecha_inicio)
                ->orWhere("STARTDATE", "<=", $fecha_fin)
                ->orderBy('STARTDATE');
            }, 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
            }, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("STARTSPECDAY", ">=", $fecha_inicio)->where("STARTSPECDAY", "<=", $fecha_fin);
            }])
            ->Where('Badgenumber', $empleado)->first();
            
            #Obtenemos los dias totales del reporte
            $dias_totales = $parametro_inicial->diffInDays($parametro_final);
            
            #Parseamos los datos obtenidos en la consulta para generar indice de fecha
            $horarios_periodo = $empleados->horarios;
            $indice_horario_seleccionado = 0;
            $dias_habiles = array();
            $jornada = "";
            $checadas_empleado  = $this->checadas_empleado($empleados->checadas);
            $omisiones          = $this->omisiones($empleados->omisiones);
            $dias_otorgados     = $this->dias_otorgados($empleados->dias_otorgados);
            #Empieza lo bueno, revision de checadas
            #por default ponemos los dias del pimer periodo, ya que sale de la consulta, pero validamos
            
            $contador_horario = 0;
            
            for($dia_periodo = 1; $dia_periodo < $dias_totales; $dia_periodo++)
            {
                #Verificamos la vigencia de los horarios
                if($contador_horario == 0)
                {
                    $horario_evaluar = $this->validar_horario($horarios_periodo, $indice_horario_seleccionado, $parametro_inicial );
                   
                    $dias_habiles = $horario_evaluar['dias_habiles'];
                    $contador_horario = $horario_evaluar['dias_restantes'];
                    $indice_horario_seleccionado = $horario_evaluar['indice'];
                    $jornada = $horario_evaluar['horario'];
                }

                ############## Desde aqui
                if(array_key_exists($parametro_inicial->dayOfWeekIso, $dias_habiles))
                {
                    if(!array_key_exists($parametro_inicial->format('Y-m-d'), $dias_otorgados))
                    {
                        $dia_seleccionado = $dias_habiles[$parametro_inicial->dayOfWeekIso]->reglaAsistencia;
                        
                        $tolerancia = ( intval($dia_seleccionado->LateMinutes) + 1);#Se agrega regla de tolerancia 
                        $fecha_hora_entrada_exacta = new Carbon($parametro_inicial->format('Y-m-d')."T".substr($dias_habiles[$parametro_inicial->dayOfWeekIso]->STARTTIME, 11, 8));
                        $fecha_hora_entrada_r_menor = $fecha_hora_entrada_exacta->addMinutes(40);
                        $fecha_hora_entrada_r_mayor = $fecha_hora_entrada_exacta->addMinutes(180);
                        $fecha_hora_entrada_exacta->addMinutes($tolerancia);
                        

                        $inicio_entrada = new Carbon($parametro_inicial->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime1, 11,8));
                        $fin_entrada =  new Carbon($parametro_inicial->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime2, 11,8));

                        $inicio_salida =  new Carbon($parametro_inicial->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime1, 11,8));
                        $fin_salida =  new Carbon($parametro_inicial->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime2, 11,8));
                        
                        $checada_entrada = 0;
                        $checada_salida  = 0;
                        if(!array_key_exists($parametro_inicial->format('Y-m-d'), $arreglo_festivos))
                        {
                            if(array_key_exists($parametro_inicial->format('Y-m-d'), $checadas_empleado))
                            {
                                foreach ($checadas_empleado[$parametro_inicial->format('Y-m-d')] as $index_checada => $dato_checada) {
                                    $checada = new Carbon($dato_checada->CHECKTIME);
                                    if($checada_entrada == 0)
                                    {
                                        if($checada->lessThanOrEqualTo($fecha_hora_entrada_exacta))
                                        {
                                            $checada_entrada = 1;
                                        }else if($checada->lessThanOrEqualTo($fecha_hora_entrada_r_menor))
                                        {
                                            $checada_entrada = 2;
                                        }else if($checada->lessThanOrEqualTo($fecha_hora_entrada_r_mayor))
                                        {
                                            $checada_entrada = 3;
                                        }    
                                    }
                                    if($checada_salida == 0)
                                    {
                                        if($checada->greaterThanOrEqualTo($inicio_salida) && $checada->lessThanOrEqualTo($fin_salida))
                                        {
                                            $checada_salida = 1;
                                        }
                                    }
                                
                                }

                                if(array_key_exists($parametro_inicial->format('Y-m-d'), $omisiones))
                                {
                                    foreach ($omisiones[$parametro_inicial->format('Y-m-d')] as $index_omision => $dato_omision) {
                                        if($dato_omision->CHECKTYPE == "I"){ $checada_entrada = 1; }
                                        if($dato_omision->CHECKTYPE == "O"){ $checada_salida = 1;  }
                                    }
                                }
                                
                        
                                if($checada_entrada == 1 and $checada_salida == 1){
                                    $resultado[$parametro_inicial->month][$parametro_inicial->day] =  ""; #Revisar
                                }else if($checada_entrada == 2 and $checada_salida == 1){
                                    $resultado[$parametro_inicial->month][$parametro_inicial->day] =  "R/1"; #Revisa
                                }else if(($checada_entrada == 1 or $checada_entrada == 2) and $checada_salida == 0)
                                { 
                                    if(array_key_exists($parametro_inicial->format('Y-m-d'), $arreglo_salidas))
                                    {
                                        if($checada_entrada == 1 )
                                        {
                                            $resultado[$parametro_inicial->month][$parametro_inicial->day] =  ""; #Revisa
                                        }else if($checada_entrada == 2 ){
                                            $resultado[$parametro_inicial->month][$parametro_inicial->day] =  "R/1"; #Revisa
                                        }
                                    }else{
                                        $resultado[$parametro_inicial->month][$parametro_inicial->day] =  "F"; #Revisa
                                    } 
                                    
                                }else if($checada_entrada == 0 and $checada_salida == 1)  
                                {
                                    $resultado[$parametro_inicial->month][$parametro_inicial->day] =  "F"; 
                                }else if($checada_entrada == 0 and $checada_salida == 0)  
                                {
                                    $resultado[$parametro_inicial->month][$parametro_inicial->day] =  "F";
                                }
                                
                            }else
                            {
                                $resultado[$parametro_inicial->month][$parametro_inicial->day] =  "F";
                            }
                        }else{
                            $resultado[$parametro_inicial->month][$parametro_inicial->day] =  "S";
                        }
                        //unset($dias_otorgados[$parametro_inicial->format('Y-m-d')]);
                    }else{

                        
                        /*if($dias_otorgados[$parametro_inicial->format('Y-m-d')][0]['DATEID'] == 6)
                        {
                            $dia_laboral = intval($dias_habiles[$parametro_inicial->dayOfWeekIso]['reglaAsistencia']['WorkDay']);//Revisar urgente
                            $dia_economico = $dia_economico + $dia_laboral;
                        }
                        if($dia_economico == 2)
                        {
                            break;
                        }*/
                        
                        # En caso de ser dia otorgado se pone las letras
                        $resultado[$parametro_inicial->month][$parametro_inicial->day] =  $dias_otorgados[$parametro_inicial->format('Y-m-d')][0]['siglas']['ReportSymbol'];
                    }    
                }else
                {   
                    # No hacemos nada en los dias no habiles
                    $resultado[$parametro_inicial->month][$parametro_inicial->day] =  "";
                }
                ############## Hasta aqui
                
                #reseteamos contadores
                $parametro_inicial->addDay();
                $contador_horario--;
            }
        /*}*/
        
        
        
        
        return array("datos" => $resultado, "horario" => $jornada);
    }

    function validar_horario($horarios, $indice, $fecha_validacion)
    {
        $numero_horarios = count($horarios);
        $dias_habiles = array();
        $diferencia_dias = 0;
        $horario = "";
        if($indice < $numero_horarios )
        {
            $horarios_seleccionado = $horarios[$indice];
            
            $fecha_inicio_periodo =  new Carbon($horarios_seleccionado->STARTDATE);
            $fecha_fin_periodo =  new Carbon(substr($horarios_seleccionado->ENDDATE, 0,10)."T23:59:59");
            
            $dias_habiles = $this->dias_horario($horarios_seleccionado->detalleHorario);
            $diferencia_dias = $fecha_validacion->diffInDays($fecha_fin_periodo);

            
            while($fecha_validacion->greaterThan($fecha_inicio_periodo) && $fecha_fin_periodo->lessThan($fecha_validacion) && $indice < count($horarios))
            {
                $indice++;
                if($indice < count($horarios))
                {
                    $fecha_inicio_periodo =  new Carbon($horarios[$indice]->STARTDATE);
                    $fecha_fin_periodo =  new Carbon(substr($horarios[$indice]->ENDDATE, 0,10)."T23:59:59");
                    $dias_habiles = $this->dias_horario($horarios[$indice]->detalleHorario);
                    $diferencia_dias = $fecha_validacion->diffInDays($fecha_fin_periodo);
                }
            }
            $indice++;
            $horario = "";
            foreach ($dias_habiles as $key => $value) {
                
                $horario = "De ".substr($value->STARTTIME, 11,5)." a ".substr($value->ENDTIME,11,5);
            }
            
            

            return array("dias_restantes"=> $diferencia_dias, "indice" => $indice, "dias_habiles" => $dias_habiles, "horario"=>$horario);
        }else{

        }
    }
   
    function dias_horario($arreglo)
    {
        $arreglo_nuevo = array();
        foreach ($arreglo as $key => $value) {
            $arreglo_nuevo[$value->SDAYS] = $value;
        }
        return $arreglo_nuevo;
    }

    function checadas_empleado($arreglo)
    {
        $arreglo_checadas = array();
        foreach ($arreglo as $key => $value) {
            $arreglo_checadas[substr($value->CHECKTIME, 0,10)][] = $value;
        }
        return $arreglo_checadas;
    }

    function festivos($arreglo)
    {
        $arreglo_festivos = array();
        foreach ($arreglo as $key => $value) {
            $arreglo_festivos[substr($value->STARTTIME, 0,10)][] = $value;
        }
        return $arreglo_festivos;
    }

    function salidas($arreglo)
    {
        $arreglo_salidas = array();
        foreach ($arreglo as $key => $value) {
            $arreglo_salidas[substr($value->STARTTIME, 0,10)][] = $value;
        }
        return $arreglo_salidas;
    }

    function omisiones($arreglo)
    {
        $arreglo_omisiones = array();
        foreach ($arreglo as $key => $value) {
            $arreglo_omisiones[substr($value->CHECKTIME, 0,10)][] = $value;
        }
        return $arreglo_omisiones;
    }

    function dias_otorgados($arreglo)
    {
        $arreglo_dias = array();
        $bandera = 0;
        $licencia_medica = 0;
        foreach ($arreglo as $key => $value) {
            if($value->DATEID == 21 || $value->DATEID == 22 ){ $bandera = 1; }
            if($value->DATEID == 8){ $licencia_medica++; }

            $arreglo_dias[substr($value->STARTSPECDAY, 0,10)][] = $value;
        }
        if($bandera == 1 || $licencia_medica >= 10)
        {
            return -1;
        }else
        {
            return $arreglo_dias;
        }
        
    }

}  