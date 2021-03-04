<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon, \Auth, DB, PDF, View, Dompdf\Dompdf;    

use App\Models\Usuarios;
use App\Models\User;
use App\Models\ReglasHorarios;
use App\Models\Festivos;
use App\Models\Contingencia;
use App\Models\SalidaAutorizada;
use App\Models\Departamentos;
use App\Models\ConfiguracionTrimestral;
use Illuminate\Support\Facades\Input;

class ReporteTrimestralController extends Controller
{
    public function index(Request $request)
    {
        //return Input::all();
        $empleados = $this->claseAsistencia($request);
        
        //return response()->json(["usuarios" => $empleados['datos']]);
        return response()->json(["usuarios" => $empleados]);
    }

    public function reporteTrimestral(Request $request)
    {
        $parametros = Input::all();
        $usuario = Auth::user();
       
        $datos_configuracion = ConfiguracionTrimestral::where("trimestre", $parametros['trimestre'])
                                                    ->where("anio", $parametros['anio'])
                                                    ->where("tipo_trabajador", $parametros['tipo_trabajador'])
                                                    ->first();
        //return $usuario;
        $asistencia = $this->claseAsistencia($request);
        //return $asistencia;
        $pdf = PDF::loadView('reportes//reporte-trimestral', ['empleados' => $asistencia, 'usuario' => $usuario, "config" => $datos_configuracion]);
        $pdf->setPaper('LEGAL', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream('Reporte-Trimestral.pdf');
    }

    function claseAsistencia(Request $request)
    {
        
        $parametros = Input::all();
        $reglas     = ReglasHorarios::where("CheckIn", "=", 1)->get();
        
        $arreglo_reglas = array();
        foreach ($reglas as $key => $value) {
            $arreglo_reglas[$value->schClassid] = $value;
        }
        
        $anio = date("Y");
        $mes  = date("m");
        $tipo_trabajador = 1;
        $trimestre = 1;
        
        $fecha_limite_actual = Carbon::now();

        if(count($parametros) > 0)
        {
            if($parametros['anio'] != "" && $parametros['trimestre']!="" && $parametros['tipo_trabajador'] != "")
            {
                $anio = $parametros['anio'];
                $trimestre = $parametros['trimestre'];
                $tipo_trabajador = $parametros['tipo_trabajador'];
                $nombre = $parametros['nombre'];
            }
        }

        $catalogo_trimestre = [ 1 =>[1,2,3], 2 => [4,5,6], 3=> [7,8,9], 4=> [10,11,12]];
        
        $empleados_trimestral = [];

        
        foreach ($catalogo_trimestre[$trimestre] as $index_trimestre => $data_trimestre) {

            $fecha_ejercicio = Carbon::now();
            $fecha_ejercicio->year =  $anio;
            $fecha_ejercicio->month = $data_trimestre;
            $fecha_ejercicio->day = 1;
            $fecha_inicio = $fecha_ejercicio->format('Y-m-d');
            $fecha_fin = $fecha_ejercicio->format('Y-m-').$fecha_ejercicio->daysInMonth;
            $dias_mes = $fecha_ejercicio->daysInMonth;
            
            //Obtenemos los dias Festivos
            $festivos   = Festivos::where("STARTTIME", ">=", $fecha_inicio.'T00:00:00')->where("STARTTIME", "<=", $fecha_fin.'T23:59:59')->get();
            $arreglo_festivos = array();
            if(count($festivos) > 0)
            {
                $arreglo_festivos = $this->festivos($festivos);
            }


            //obtener dias contingencia

            $contingencia  = Contingencia::where("STARTTIME", ">=", $fecha_inicio.'T00:00:00')->where("STARTTIME", "<=", $fecha_fin.'T23:59:59')->get();
            $arreglo_contingencia = array();
            if(count($contingencia) > 0)
            {
                $arreglo_contingencia = $this->contingencia($contingencia);
            }

            
            //Obtenemos salidas autorizadas
            $salidas   = SalidaAutorizada::where("STARTTIME", ">=", $fecha_inicio.'T00:00:00')->where("STARTTIME", "<=", $fecha_fin.'T23:59:59')->get();
            $arreglo_salidas = array();
            if(count($salidas) > 0)
            {
                $arreglo_salidas = $this->salidas($salidas);
            }
            
            //Obtenemos las checadas de todoos los trabajadores
            $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("CHECKTIME", ">=", $fecha_inicio.'T00:00:00')->where("CHECKTIME", "<=", $fecha_fin.'T23:59:59');
            }, 'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("STARTDATE", "<=", $fecha_inicio.'T00:00:00')
                ->orWhere("STARTDATE", "<=", $fecha_fin.'T00:00:00')
                ->orderBy('STARTDATE');
            }, 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("CHECKTIME", ">=", $fecha_inicio.'T00:00:00')->where("CHECKTIME", "<=", $fecha_fin.'T23:59:59');
            }, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("STARTSPECDAY", ">=", $fecha_inicio.'T00:00:00')->where("STARTSPECDAY", "<=", $fecha_fin.'T23:59:59');
            }])
            ->whereNull("state")
            ->WHERE("PAGER", "NOT LIKE", 'CF%')
            ->WHEREIN("FPHONE", ['CSSSA017213','CSSSA017324'])
            ->WHERE("OPHONE", "!=", 3)
            ->Where(function($query2)use($parametros){
                $query2->where('Name','LIKE','%'.$parametros['nombre'].'%')
                        ->orWhere('TITLE','LIKE','%'.$parametros['nombre'].'%')
                        ->orWhere('Badgenumber', $parametros['nombre']);
            })
            //->where("TITLE","=", 'AESS770416PJ4')
            ->where("DEFAULTDEPTID", "=", $tipo_trabajador)
            //->limit(200)
            ->orderBy("carType", "DESC")
            ->get();
            

            foreach ($empleados as $index_empleado => $data_empleado) {
                $empleado_seleccionado = $empleados[$index_empleado];
                
                if(!array_key_exists($empleados[$index_empleado]->TITLE, $empleados_trimestral))
                {
                    $empleados_trimestral[$empleados[$index_empleado]->TITLE] = $empleados[$index_empleado];
                    $empleados_trimestral[$empleados[$index_empleado]->TITLE]['TRIMESTRAL'] = 0;
                    $empleados_trimestral[$empleados[$index_empleado]->TITLE]['jornada_laboral'] = 0;
                }
                

                $horarios_periodo = $data_empleado->horarios;
                $indice_horario_seleccionado = 0;
                $arreglo_consulta = array();
                $dias_habiles = array();
    
                $checadas_empleado  = $this->checadas_empleado($data_empleado->checadas);
                $omisiones          = $this->omisiones($data_empleado->omisiones);
                $dias_otorgados     = $this->dias_otorgados($data_empleado->dias_otorgados);
                //return array("datos" => $dias_otorgados);
                $verificador = 0;
                
                $jornada_laboral = 0;
                $dia_economico = 0;
                if($dias_otorgados != -1)
                {
                    for($i = 1; $i<=$dias_mes; $i++)
                    {
                        $fecha_evaluar = new Carbon($fecha_inicio);
                        $fecha_evaluar->day = $i;
                    
                        if($fecha_evaluar->lessThan($fecha_limite_actual))
                        {
                            if($indice_horario_seleccionado < count($horarios_periodo))
                            {
                                //verificador de horas de jornada
                                if($jornada_laboral == 0)
                                {
                                        $inicio_jornada = $horarios_periodo[$indice_horario_seleccionado]['detalleHorario'][0]['STARTTIME'];
                                       
                                        $fin_jornada    = $horarios_periodo[$indice_horario_seleccionado]['detalleHorario'][0]['ENDTIME'];
                                        $jornada_inicio =new Carbon($inicio_jornada);
                                        $jornada_fin    =new Carbon($fin_jornada);
                                        $jornada_fin->addMinutes(30);
                                        $jornada_laboral = $jornada_fin->diffInHours($jornada_inicio);
                                        //$jornada_laboral =1;
                                        $empleados_trimestral[$empleados[$index_empleado]->TITLE]['jornada_laboral'] = $jornada_fin->diffInHours($jornada_inicio);
                                        //return array("datos" => $jornada_laboral);
                                }
                                //fin veririficador
                                $fecha_inicio_periodo =  new Carbon($horarios_periodo[$indice_horario_seleccionado]->STARTDATE);
                                $fecha_fin_periodo =  new Carbon(substr($horarios_periodo[$indice_horario_seleccionado]->ENDDATE, 0,10)."T23:59:59");

                                
                                if(count($dias_habiles) == 0)
                                {
                                    $dias_habiles = $this->dias_horario($horarios_periodo[$indice_horario_seleccionado]->detalleHorario);
                                }
                                
                                
                                while($fecha_evaluar->greaterThan($fecha_inicio_periodo) && $fecha_fin_periodo->lessThan($fecha_evaluar) && $indice_horario_seleccionado < count($horarios_periodo))
                                {
                                    $indice_horario_seleccionado++;
                                    if($indice_horario_seleccionado < count($horarios_periodo))
                                    {
                                        $fecha_inicio_periodo =  new Carbon($horarios_periodo[$indice_horario_seleccionado]->STARTDATE);
                                        $fecha_fin_periodo =  new Carbon(substr($horarios_periodo[$indice_horario_seleccionado]->ENDDATE, 0,10)."T23:59:59");
                                        $dias_habiles = $this->dias_horario($horarios_periodo[$indice_horario_seleccionado]->detalleHorario);
                                    }
                                }

                                
                                
                                if($indice_horario_seleccionado < count($horarios_periodo))
                                {
                                    
                                    if(array_key_exists($fecha_evaluar->dayOfWeekIso, $dias_habiles))
                                    {
                                        if(!array_key_exists($fecha_evaluar->format('Y-m-d'), $dias_otorgados))
                                        {
                                            $dia_seleccionado = $dias_habiles[$fecha_evaluar->dayOfWeekIso]->reglaAsistencia;
                                            
                                            //$retardo = intval($dia_seleccionado->LateMinutes);
                                            $fecha_hora_entrada_exacta = new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dias_habiles[$fecha_evaluar->dayOfWeekIso]->STARTTIME, 11, 8));
                                            //$fecha_hora_entrada_exacta->addMinutes($retardo);
                                            $fecha_hora_entrada_exacta->addMinutes(1);

                                            
                                            //$inicio_entrada = new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime1, 11,8));
                                            //$fin_entrada =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime2, 11,8));
                                            $inicio_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime1, 11,8));
                                            $fin_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime2, 11,8));

                                            
                                            $checada_entrada = 0;
                                            $checada_salida  = 0;
                                            if(!array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_festivos) && !array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_contingencia))
                                            {
                                                if(array_key_exists($fecha_evaluar->format('Y-m-d'), $checadas_empleado))
                                                {
                                                    foreach ($checadas_empleado[$fecha_evaluar->format('Y-m-d')] as $index_checada => $dato_checada) {
                                                        
                                                        $checada = new Carbon($dato_checada->CHECKTIME);
                                                        if($checada_entrada == 0)
                                                        {
                                                                if($checada->lessThanOrEqualTo($fecha_hora_entrada_exacta))
                                                                {
                                                                    $checada_entrada = 1;
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
                                                    

                                                    if(array_key_exists($fecha_evaluar->format('Y-m-d'), $omisiones))
                                                    {
                                                        foreach ($omisiones[$fecha_evaluar->format('Y-m-d')] as $index_omision => $dato_omision) {
                                                            if($dato_omision->CHECKTYPE == "I")
                                                            {
                                                                $checada_entrada = 1;
                                                            }
                                                            
                                                            if($dato_omision->CHECKTYPE == "O")
                                                            {
                                                                $checada_salida = 1;
                                                            }
                                                        }
                                                    }
                                                    
                                            
                                                    if($checada_entrada == 1 and $checada_salida == 1){
                                                    $verificador++;
                                                    }else if($checada_entrada == 2 and $checada_salida == 1){
                                                        break;
                                                    }else if(($checada_entrada == 1 or $checada_entrada == 2) and $checada_salida == 0)
                                                    { 
                                                        if(array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_salidas))
                                                        {
                                                            if($checada_entrada == 1 )
                                                            {
                                                                $verificador++;
                                                            }else if($checada_entrada == 2 ){
                                                                break;
                                                            }
                                                        }else{
                                                            break;
                                                        } 
                                                        
                                                    }else if($checada_entrada == 0 and $checada_salida == 1)  
                                                    {
                                                        break;
                                                    }else if($checada_entrada == 0 and $checada_salida == 0)  
                                                    {
                                                        break;
                                                    }
                                                    
                                                }else
                                                {
                                                    break;
                                                }
                                            }else{
                                                //Dias festivos
                                                $verificador++;
                                            }
                                            //unset($dias_otorgados[$fecha_evaluar->format('Y-m-d')]);
                                        }else{
                                            //return array("datos" =>intval($dias_habiles[$fecha_evaluar->dayOfWeekIso]['reglaAsistencia']['WorkDay']));
                                            if($dias_otorgados[$fecha_evaluar->format('Y-m-d')][0]['DATEID'] == 6)
                                            {
                                                $dia_laboral = intval($dias_habiles[$fecha_evaluar->dayOfWeekIso]['reglaAsistencia']['WorkDay']);//Revisar urgente
                                                $dia_economico = $dia_economico + $dia_laboral;
                                            }
                                            if($dia_economico == 2)
                                            {
                                                break;
                                            }
                                            //Dias otorgados (aqui hay que verificar)
                                            $verificador++;
                                        }    
                                    }else
                                    {   
                                        //dias no habiles para su horario
                                        $verificador++;
                                    }
                                }else{
                                    break;        
                                }
                            }else{
                                break;  
                            }
                        }else
                        {
                            break; 
                        }    

                        //echo $fecha_evaluar->format("Y-m-d")."--";
                    }
                }
                
                if($trimestre == 4)
                {
                    $empleados_trimestral[$empleados[$index_empleado]->TITLE]['TRIMESTRAL'] = 3;
                }else if($verificador == $dias_mes)
                {
                    
                    //$empleados[$index_empleado]->TRIMESTRAL += 1;
                    $empleados_trimestral[$empleados[$index_empleado]->TITLE]['TRIMESTRAL'] += 1;
                }
                
                //$empleados_trimestral[$empleados[$index_empleado]->TITLE]['jornada_laboral'] = $jornada_laboral;
            }

           
            $lista_empleados_trimestral = [];
            foreach ($empleados_trimestral as $index_trimestral => $data_trimestral) {
                
                if($data_trimestral['TRIMESTRAL'] > 0)
                {
                    /*if(count($data_trimestral['horarios']))
                    {
                        $inicio_jornada = $data_trimestral['horarios'][0]['detalleHorario'][0]['STARTTIME'];
                        $fin_jornada    = $data_trimestral['horarios'][0]['detalleHorario'][0]['ENDTIME'];
                        $jornada_inicio =new Carbon($inicio_jornada);
                        $jornada_fin    =new Carbon($fin_jornada);
                        $data_trimestral['jornada_laboral'] = $jornada_fin->diffInHours($jornada_inicio);
                    }else
                    {
                        $data_trimestral['jornada_laboral'] = "N/A";
                    }*/
                    //return array("datos" =>$data_trimestral['horarios'][0]['detalleHorario'][0]);
                    $lista_empleados_trimestral[] = $data_trimestral;
                }
            }
            //return array("datos" => $lista_empleados_trimestral);
        } 
        
        //return array("datos" => $empleados_trimestral);
        $tipo_nomina = Departamentos::where("DEPTID", "=",$tipo_trabajador)->first();
        return array("datos" => $lista_empleados_trimestral, "filtros" => $parametros, "trimestre"=> $parametros['trimestre'], "tipo_trabajador" => $tipo_nomina);
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

    function contingencia($arreglo)
    {
        $arreglo_contingencia = array();
        foreach ($arreglo as $key => $value) {
            $arreglo_contingencia [substr($value->STARTTIME, 0,10)][] = $value;
        }
        return $arreglo_contingencia;
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

    public function catalogo(Request $request)
    {
        $departamentos = Departamentos::all();
        return response()->json(["catalogo" => $departamentos]);
    }
}
