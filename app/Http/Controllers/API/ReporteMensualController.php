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

class ReporteMensualController extends Controller
{

    public $catalogo_meses = ['01' => "ENERO", "02" => "FEBRERO", "03" => "MARZO", "04" => "ABRIL", "05" => "MAYO", "06" => "JUNIO", "07" => "JULIO", "08" => "AGOSTO", "09" => "SEPTIEMBRE", "10" => "OCTUBRE", "11" => "NOVIEMBRE", "12" => "DICIEMBRE"];
        
    public function index(Request $request)
    {
        //return Input::all();
        $empleados = $this->claseAsistencia($request);
        
        return response()->json(["usuarios" => $empleados['datos']]);
    }

    public function reporteMensual(Request $request)
    {

        $asistencia = $this->claseAsistencia($request);
        $pdf = PDF::loadView('reportes//reporte-mensual', ['empleados' => $asistencia]);
        $pdf->setPaper('LEGAL', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);
        //return make::view('reportes\\reporte-mensual', ['empleados' => $asistencia]);
        //return View::make('reportes\\reporte-mensual', ['empleados' => $asistencia]);
        return $pdf->stream('Reporte-Mensual.pdf');
    }

    function claseAsistencia(Request $request)
    {
        
        $parametros = Input::all();
        //return response()->json(["usuarios" => "hola"]);
        $reglas     = ReglasHorarios::where("CheckIn", "=", 1)->get();
        
        $arreglo_reglas = array();
        foreach ($reglas as $key => $value) {
            $arreglo_reglas[$value->schClassid] = $value;
        }
        
        $anio = date("Y");
        $mes  = date("m");
        $tipo_trabajador = 1;
        $quincena = 1;
        //$parametros = Input::all();

        $fecha_limite_actual = Carbon::now();

        //return response()->json(["usuarios" => $parametros]);
        if(count($parametros) > 0)
        {
            if($parametros['anio'] != "" && $parametros['mes']!="" && $parametros['tipo_trabajador'] != "")
            {
                $anio = $parametros['anio'];
                $mes = $parametros['mes'];
                $tipo_trabajador = $parametros['tipo_trabajador'];
                $nombre = $parametros['nombre'];
                //$quincena = $parametros['quincena'];
            }
        }

        $catalogo_meses = ['01' => "ENERO", "02" => "FEBRERO", "03" => "MARZO", "04" => "ABRIL", "05" => "MAYO", "06" => "JUNIO", "07" => "JULIO", "08" => "AGOSTO", "09" => "SEPTIEMBRE", "10" => "OCTUBRE", "11" => "NOVIEMBRE", "12" => "DICIEMBRE"];
        //print_r($parametros);
        
        $fecha_actual = Carbon::now();
        $fecha_actual->year = $anio;

        $fecha_inicio = $anio."-".$mes."-01";
        $dias_mes = $fecha_actual->daysInMonth;
        $fecha_fin    = $anio."-".$mes."-".$dias_mes;
        
        
        //Obtenemos los dias Festivos
        $festivos   = Festivos::where("STARTTIME", ">=", $fecha_inicio.'T00:00:00')->where("STARTTIME", "<=", $fecha_fin.'T23:59:59')->get();
        $arreglo_festivos = array();
        if(count($festivos) > 0)
            $arreglo_festivos = $this->festivos($festivos);
        

        //Obtenemos salidas autorizadas
        $salidas   = SalidaAutorizada::where("STARTTIME", ">=", $fecha_inicio.'T00:00:00')->where("STARTTIME", "<=", $fecha_fin.'T23:59:59')->get();
        $arreglo_salidas = array();
        if(count($salidas) > 0)
            $arreglo_salidas = $this->salidas($salidas);
        
        
        
        $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'dias_otorgados.siglas', 'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("CHECKTIME", ">=", $fecha_inicio.'T00:00:00')->where("CHECKTIME", "<=", $fecha_fin.'T23:59:59');
        }, 'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("STARTDATE", "<=", $fecha_inicio.'T00:00:00');//->where("ENDDATE", ">=", $fecha_fin.'T00:00:00');
        }, 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("CHECKTIME", ">=", $fecha_inicio.'T00:00:00')->where("CHECKTIME", "<=", $fecha_fin.'T23:59:59');
        }, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("STARTSPECDAY", ">=", $fecha_inicio.'T00:00:00')->where("STARTSPECDAY", "<=", $fecha_fin.'T23:59:59');
        }])
        ->whereNull("state")
        ->WHERE("FPHONE", "=", 'CSSSA017213')
        ->Where(function($query2)use($parametros){
            $query2->where('Name','LIKE','%'.$parametros['nombre'].'%')
                    ->orWhere('TITLE','LIKE','%'.$parametros['nombre'].'%')
                    ->orWhere('Badgenumber', $parametros['nombre']);
        })
        ->where("DEFAULTDEPTID", "=", $tipo_trabajador)
        ->orderBy("carType", "DESC")
        ->get();
        
        foreach ($empleados as $index_empleado => $data_empleado) {
            $empleado_seleccionado = $empleados[$index_empleado];
            $horarios_periodo = $data_empleado->horarios;
            $indice_horario_seleccionado = 0;
            $arreglo_consulta = array();
            $dias_habiles = array();

            $resumen = ["ASISTENCIA" => 0, "FALTAS" => 0, "RETARDOS" => 0, 'RETARDOS_1' =>0, 'RETARDOS_2' =>0, "OMISIONES" => 0, "JUSTIFICADOS" => 0];
            
            $checadas_empleado  = $this->checadas_empleado($data_empleado->checadas);
            $omisiones          = $this->omisiones($data_empleado->omisiones);
            $dias_otorgados     = $this->dias_otorgados($data_empleado->dias_otorgados);
            //return response()->json(["usuarios" => $dias_otorgados]);
            /*if($quincena == 1)
            {
                $i = 1;
            }else if($quincena == 2)
            {
                $i = 16;
            } */   

            //return response()->json(["usuarios" => $i]);
            $i = 1;
            for($i; $i<=$dias_mes; $i++)
            {
                $fecha_evaluar = new Carbon($fecha_inicio);
                $fecha_evaluar->day = $i;
            
                //if($fecha_evaluar->lessThanOrEqualTo($fecha_limite_actual))
                if($fecha_evaluar->lessThan($fecha_limite_actual))
                {
                    //return response()->json(["usuarios" => $horarios_periodo[$indice_horario_seleccionado]->ENDDATE]);
                    if($indice_horario_seleccionado < count($horarios_periodo))
                    {
                        
                        $fecha_inicio_periodo =  new Carbon($horarios_periodo[$indice_horario_seleccionado]->STARTDATE);
                        $fecha_fin_periodo =  new Carbon(substr($horarios_periodo[$indice_horario_seleccionado]->ENDDATE, 0,10)."T23:59:59");

                        if(count($dias_habiles) == 0)
                        {
                            $dias_habiles = $this->dias_horario($horarios_periodo[$indice_horario_seleccionado]->detalleHorario);
                        }

                        
                        //while($fecha_evaluar->lessThan($fecha_inicio_periodo) && $fecha_evaluar->greaterThan($fecha_fin_periodo) && $indice_horario_seleccionado < count($horarios_periodo))
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
                        //return array("datos" =>$indice_horario_seleccionado);
                        //return array("datos" => $fecha_fin_periodo->lessThan($fecha_evaluar), 'evaluar'=>$fecha_evaluar, 'inicio'=> $fecha_inicio_periodo, 'fin'=>$fecha_fin_periodo);

                        if($indice_horario_seleccionado < count($horarios_periodo))
                        {
                            
                            if(array_key_exists($fecha_evaluar->dayOfWeekIso, $dias_habiles))
                            {
                                //return response()->json(["usuarios" => $fecha_evaluar]);
                                if(!array_key_exists($fecha_evaluar->format('Y-m-d'), $dias_otorgados))
                                {
                                    //$arreglo_consulta[$i] = $i;
                                    // Empieza la consulta por dia 
                                    
                                    $dia_seleccionado = $dias_habiles[$fecha_evaluar->dayOfWeekIso]->reglaAsistencia;
                                    
                                    $retardo = intval($dia_seleccionado->LateMinutes);
                                    //$hora_entrada_exacta = substr($horarios_periodo[$indice_horario_seleccionado]->STARTTIME, 11,8);
                                    $fecha_hora_entrada_exacta = new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dias_habiles[$fecha_evaluar->dayOfWeekIso]->STARTTIME, 11, 8));
                                    $fecha_hora_entrada_exacta->addMinutes($retardo);
                                    $fecha_hora_entrada_exacta->addMinutes(1);

                                    //return response()->json(["usuarios" => substr($dias_habiles[$fecha_evaluar->dayOfWeekIso]->STARTTIME, 11, 8)]);
                                    $inicio_entrada = new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime1, 11,8));
                                    $fin_entrada =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime2, 11,8));
                                    $inicio_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime1, 11,8));
                                    $fin_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime2, 11,8));

                                    $checada_entrada = 0;
                                    $checada_salida  = 0;
                                    if(!array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_festivos))
                                    {
                                        if(array_key_exists($fecha_evaluar->format('Y-m-d'), $checadas_empleado))
                                        {
                                            foreach ($checadas_empleado[$fecha_evaluar->format('Y-m-d')] as $index_checada => $dato_checada) {
                                                
                                                $checada = new Carbon($dato_checada->CHECKTIME);
                                                if($checada_entrada == 0)
                                                {
                                                    if($checada->greaterThanOrEqualTo($inicio_entrada) && $checada->lessThanOrEqualTo($fin_entrada))
                                                    {
                                                        if($checada->greaterThan($fecha_hora_entrada_exacta))
                                                            $checada_entrada = 2;
                                                        else
                                                            $checada_entrada = 1;    
                                                    }
                                                    
                                                }
                                                if($checada_salida == 0)
                                                {
                                                    if($checada->greaterThanOrEqualTo($inicio_salida) && $checada->lessThanOrEqualTo($fin_salida))
                                                        $checada_salida = 1;
                                                }
                                            }

                                            
                                            //Checar si existe una omision
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
                                                //unset($omisiones[$fecha_evaluar->format('Y-m-d')]);
                                            }
                                            
                                    
                                            if($checada_entrada == 1 and $checada_salida == 1){
                                                $arreglo_consulta[$i] = "A";
                                                $resumen['ASISTENCIA']++;
                                            }else if($checada_entrada == 2 and $checada_salida == 1){
                                                $arreglo_consulta[$i] = "R1";
                                                $resumen['RETARDOS']++;
                                                if($fecha_evaluar->day <= 15)
                                                {
                                                    $resumen['RETARDOS_1']++;
                                                }else{
                                                    $resumen['RETARDOS_2']++;
                                                }
                                            }else if(($checada_entrada == 1 or $checada_entrada == 2) and $checada_salida == 0)
                                            { 
                                                
                                                if(array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_salidas))
                                                {
                                                    if($checada_entrada == 1 )
                                                    {
                                                        $arreglo_consulta[$i] = "A";
                                                        $resumen['ASISTENCIA']++;
                                                    }else if($checada_entrada == 2 ){
                                                        $arreglo_consulta[$i] = "R1";
                                                        $resumen['RETARDOS']++;
                                                        if($fecha_evaluar->day <= 15)
                                                        {
                                                            $resumen['RETARDOS_1']++;
                                                        }else{
                                                            $resumen['RETARDOS_2']++;
                                                        }
                                                    }
                                                }else{
                                                    $arreglo_consulta[$i] = "FS";
                                                    $resumen['FALTAS']++;
                                                } 
                                                
                                            }else if($checada_entrada == 0 and $checada_salida == 1)  
                                            {
                                                $arreglo_consulta[$i] = "FE";
                                                $resumen['FALTAS']++;
                                            }else if($checada_entrada == 0 and $checada_salida == 0)  
                                            {
                                                $arreglo_consulta[$i] = "F";
                                                $resumen['FALTAS']++;
                                            }
                                            
                                        }else
                                        {
                                            $arreglo_consulta[$i] = "F";
                                            $resumen['FALTAS']++;
                                        }
                                    }else{
                                        $arreglo_consulta[$i] = "DF";
                                        $resumen['JUSTIFICADOS']++;
                                    }
                                    //unset($dias_otorgados[$fecha_evaluar->format('Y-m-d')]);
                                }else{
                                    //array_key_exists($fecha_evaluar->format('Y-m-d'), $dias_otorgados)
                                    $obj = $dias_otorgados[$fecha_evaluar->format('Y-m-d')];
                                    //return array("datos" => $obj[0]['siglas']['ReportSymbol']);
                                    $arreglo_consulta[$i] =  $obj[0]['siglas']['ReportSymbol'];
                                    $resumen['JUSTIFICADOS']++;
                                }    
                            }else
                            {   
                                $arreglo_consulta[$i] = "N/A";
                            }
                        }else{
                            $arreglo_consulta[$i] = "ERROR/HORARIO";        
                        }
                    }else{
                        $arreglo_consulta[$i] = "S/H";    
                    }
                }else
                {
                    $arreglo_consulta[$i] = "N/C";
                }    
                
            }
            
            $empleados[$index_empleado]->asistencia = $arreglo_consulta;
            $resumen['FALTAS'] += intval($resumen['RETARDOS'] / 7);
            $empleados[$index_empleado]->resumen = $resumen;
            if($resumen['FALTAS'] == 0 && $resumen['RETARDOS_1'] < 4 && $resumen['RETARDOS_2'] < 4)
            {
                unset($empleados[$index_empleado]);
            }
        }
        
        //$empleado->nombre_mes = $catalogo_meses;#[$parametros['mes']];
        $tipo_nomina = Departamentos::where("DEPTID", "=",$tipo_trabajador)->first();
        return array("datos" => $empleados, "filtros" => $parametros, "nombre_mes"=> $catalogo_meses[$parametros['mes']], "tipo_trabajador" => $tipo_nomina);
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
        foreach ($arreglo as $key => $value) {
            
            switch(intval($value->siglas->Classify))
            {
                case 1: $arreglo_dias['festivos'][substr($value->STARTSPECDAY, 0,10)][] = $value; break;
                case 2: 
                case 3: $arreglo_dias['entradas'][substr($value->STARTSPECDAY, 0,10)][] = $value; break;
                case 4: 
                case 3: $arreglo_dias['salidas'][substr($value->STARTSPECDAY, 0,10)][] = $value; break;

            }
            //$arreglo_dias[substr($value->STARTSPECDAY, 0,10)][] = $value;
        }
        return $arreglo_dias;
    }

    public function catalogo(Request $request)
    {
        $departamentos = Departamentos::all();
        return response()->json(["catalogo" => $departamentos]);
    }

    public function dias_festivos($fecha_inicio, $fecha_fin)
    {
        $festivos   = Festivos::where("STARTTIME", ">=", $fecha_inicio)->where("STARTTIME", "<=", $fecha_fin)->get();
        $arreglo_festivos = array();
        if(count($festivos) > 0){
            $arreglo_festivos = $this->festivos($festivos);
        }
        return $arreglo_festivos;
    }

    public function salidas_autorizadas($fecha_inicio, $fecha_fin)
    {
        $salidas   = SalidaAutorizada::where("STARTTIME", ">=", $fecha_inicio)->where("STARTTIME", "<=", $fecha_fin)->get();
        $arreglo_salidas = array();
        if(count($salidas) > 0)
        {
            $arreglo_salidas = $this->salidas($salidas);
        }
        return $arreglo_salidas;
    }

    public function empleados_checadas($fecha_inicio, $fecha_fin, $parametros)
    {
        $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'dias_otorgados.siglas', 'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
        }, 'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("STARTDATE", "<=", $fecha_inicio);//->where("ENDDATE", ">=", $fecha_fin.'T00:00:00');
        }, 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
        }, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("STARTSPECDAY", ">=", $fecha_inicio)->where("STARTSPECDAY", "<=", $fecha_fin);
        }])
        ->whereNull("state")
        ->where("FPHONE", "=", 'CSSSA017213')
        ->where(function($query2)use($parametros){
            $query2->where('Name','LIKE','%'.$parametros['nombre'].'%')
                    ->orWhere('TITLE','LIKE','%'.$parametros['nombre'].'%')
                    ->orWhere('Badgenumber', $parametros['nombre']);
        })
        ->where("DEFAULTDEPTID", "=", $parametros['tipo_trabajador'])
        ->where("USERID", "=","643")
        ->orderBy("carType", "DESC")
        ->get();
        return $empleados;
    }

    function validaHorario($fecha_evaluar, $indice_horario_seleccionado, $horarios_periodo, $dias_habiles)
    {
        if($indice_horario_seleccionado < count($horarios_periodo))
        {
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

            if($indice_horario_seleccionado >= count($horarios_periodo))
            {
                return 3; //Ya no tiene horarios
            }

            return array("indice" => $indice_horario_seleccionado, "inicio_periodo" => $fecha_inicio_periodo, "fin_periodo" => $fecha_fin_periodo, "habiles" => $dias_habiles);
        }else{
            return 0;//Sin Horario
        }
    }

    function VerificadorAsistencia($fecha_evaluar, $validacion_horario, $checadas_empleado, $omisiones, $dias_otorgados)
    {
        $dia = $fecha_evaluar->day;
        $dia_inicio = intval($validacion_horario['habiles'][$dia]->SDAYS);
        $dia_final  = intval($validacion_horario['habiles'][$dia]->EDAYS);
        $diferencia_dias = $dia_final - $dia_inicio;
        
        $dia_seleccionado = $validacion_horario['habiles'][$fecha_evaluar->dayOfWeekIso]->reglaAsistencia;
                                
        $tolerancia = ( intval($dia_seleccionado->LateMinutes) + 1);//Se agrega regla de tolerancia 
        $fecha_hora_entrada_exacta = new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($validacion_horario['habiles'][$fecha_evaluar->dayOfWeekIso]->STARTTIME, 11, 8));
        $fecha_hora_entrada_exacta->addMinutes($tolerancia);
        
        $inicio_entrada = new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime1, 11,8));
        $fin_entrada =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime2, 11,8));

        $checada_entrada = 0;
        $checada_salida  = 0;
        foreach ($checadas_empleado[$fecha_evaluar->format('Y-m-d')] as $index_checada => $dato_checada) {
                                                
            $checada = new Carbon($dato_checada->CHECKTIME);
            if($checada_entrada == 0)
            {
                if($checada->greaterThanOrEqualTo($inicio_entrada) && $checada->lessThanOrEqualTo($fin_entrada))
                {
                    if($checada->greaterThan($fecha_hora_entrada_exacta)){
                        $checada_entrada = 2;
                    }else{
                        $checada_entrada = 1;    
                    }
                }
                
            }
            /*if($checada_salida == 0 && $diferencia_dias == 0)
            {
                if($checada->greaterThanOrEqualTo($inicio_salida) && $checada->lessThanOrEqualTo($fin_salida))
                    $checada_salida = 1;
            }*/
        }
        //return $dias_otorgados;
        //$inicio_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime1, 11,8));
        //$fin_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime2, 11,8));
    }

    function claseFaltas(Request $request)
    {
        
        $parametros = Input::all();
        $fecha_limite_actual = Carbon::now();
        $anio = $fecha_limite_actual->year;
        $mes  = $fecha_limite_actual->month;
        if(count($parametros) > 0)
        {
            if($parametros['anio'] != "" && $parametros['mes']!="" && $parametros['tipo_trabajador'] != "")
            {
                $anio = $parametros['anio'];
                $mes = $parametros['mes'];
                $tipo_trabajador = $parametros['tipo_trabajador'];
                $nombre = $parametros['nombre'];
            }
        }
        
        $fecha_inicio = Carbon::create($anio, $mes, 01,0,0,1);  
        $fecha_fin = Carbon::create($anio, $mes, $fecha_inicio->daysInMonth, 23,59,59); 
        
        $arreglo_festivos = $this->dias_festivos($fecha_inicio, $fecha_fin);
        $arreglo_salidas = $this->salidas_autorizadas($fecha_inicio, $fecha_fin);
        $empleados = $this->empleados_checadas($fecha_inicio, $fecha_fin, $parametros);
        
///Aqui empieza
        foreach ($empleados as $index_empleado => $data_empleado) {
            $empleado_seleccionado = $empleados[$index_empleado];
            $horarios_periodo = $data_empleado->horarios;
            $indice_horario_seleccionado = 0;
            $arreglo_consulta = array();
            $dias_habiles = array();
            $banderaHorarios = false;

            $resumen = ["ASISTENCIA" => 0, "FALTAS" => 0, "RETARDOS" => 0, 'RETARDOS_1' =>0, 'RETARDOS_2' =>0, "OMISIONES" => 0, "JUSTIFICADOS" => 0];
            
            $checadas_empleado  = $this->checadas_empleado($data_empleado->checadas);
            $omisiones          = $this->omisiones($data_empleado->omisiones);
            $dias_otorgados     = $this->dias_otorgados($data_empleado->dias_otorgados);
            
            $i = 1;
            for($i; $i<=$fecha_inicio->daysInMonth; $i++)
            {
                $fecha_evaluar = new Carbon($fecha_inicio);
                $fecha_evaluar->day = $i;
                
                if($fecha_evaluar->greaterThan($fecha_limite_actual))
                {
                    $arreglo_consulta[$i] = "N/C";  
                    continue; //salimos brincamos el for por si no tiene horario
                }
                
                if($banderaHorarios)
                {
                    $arreglo_consulta[$i] = "S/H";  
                    continue; //salimos brincamos el for por que ya no tiene horarios
                }
                
                $validacion_horario = $this->validaHorario($fecha_evaluar, $indice_horario_seleccionado, $horarios_periodo, $dias_habiles);
                
                if($validacion_horario == 0)// Sin horario
                {
                    $arreglo_consulta[$i] = "S/H";  
                    continue; //salimos brincamos el for por si no tiene horario
                }else if($validacion_horario == 3)//No cuenta con horarios para verificar se llego al maximo
                {
                    $banderaHorarios = true;
                    $arreglo_consulta[$i] = "S/H";  
                    continue; //salimos brincamos el for por que ya no tiene horarios
                }else{//Tiene horario disponible para hacer el calculo
                    $indice_horario_seleccionado = $validacion_horario['indice'];
                    $fecha_inicio_periodo = $validacion_horario["inicio_periodo" ]; 
                    $fecha_fin_periodo = $validacion_horario["fin_periodo" ]; 
                    $dias_habiles = $validacion_horario["habiles" ]; 
                    
                    if(!array_key_exists($fecha_evaluar->dayOfWeekIso, $dias_habiles)){  $arreglo_consulta[$i] = "N/A"; continue; } //No es día habil para su horario
                    if(array_key_exists($fecha_evaluar->format('Y-m-d'), $dias_otorgados['festivos'])){ $arreglo_consulta[$i] = $dias_otorgados[$fecha_evaluar->format('Y-m-d')][0]->siglas->ReportSymbol; continue; }//Se Valida el dia completo con justificante
                    if(array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_festivos)){ $arreglo_consulta[$i] = "DF"; continue; }//Se Valida el dia completo festivo
                    if(array_key_exists($fecha_evaluar->dayOfWeekIso, $dias_habiles) && !array_key_exists($fecha_evaluar->format('Y-m-d'), $checadas_empleado)){ $arreglo_consulta[$i] = "F"; continue; }// Tiene horario y no checadas es falta directa, habiendo justificado dias
                    
                    //return $validacion_horario['habiles'];
                    return $this->VerificadorAsistencia($fecha_evaluar, $validacion_horario, $checadas_empleado, $omisiones, $dias_otorgados);
                }
                
                
            }
        }
///Aqui termina

        return array("datos" => $empleados);
        
        return array("datos" => $empleados, "filtros" => $parametros, "nombre_mes"=> $catalogo_meses[$parametros['mes']], "tipo_trabajador" => $tipo_nomina);
    }
    
}
