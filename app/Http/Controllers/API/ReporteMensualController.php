<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon, DB, PDF, View, Dompdf\Dompdf;;

use App\Models\Usuarios;
use App\Models\ReglasHorarios;
use App\Models\Festivos;
use App\Models\Contingencia;
use App\Models\SalidaAutorizada;
use App\Models\Departamentos;
use App\Models\CluesUser;
use Illuminate\Support\Facades\Input;

class ReporteMensualController extends Controller
{

    public $catalogo_meses = ['01' => "ENERO", "02" => "FEBRERO", "03" => "MARZO", "04" => "ABRIL", "05" => "MAYO", "06" => "JUNIO", "07" => "JULIO", "08" => "AGOSTO", "09" => "SEPTIEMBRE", "10" => "OCTUBRE", "11" => "NOVIEMBRE", "12" => "DICIEMBRE"];
        
    public function index(Request $request)
    {
        //return Input::all();
        //$empleados = $this->claseAsistencia($request);
        $empleados = $this->claseFaltas($request);
        
        return response()->json(["usuarios" => $empleados['datos']]);
    }

    public function reporteMensual(Request $request)
    {

        #$asistencia = $this->claseAsistencia($request);
        $asistencia = $this->claseFaltas($request);
       // dd($asistencia);
        //return $asistencia;
        $pdf = PDF::loadView('reportes//reporte-mensual', ['empleados' => $asistencia]);
        $pdf->setPaper('LEGAL', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);
        return $pdf->stream('Reporte-Mensual.pdf');
    }

    public function reporteMensual_8002(Request $request)
    {

        $asistencia = $this->claseFaltas($request);
        $pdf = PDF::loadView('reportes//reporte-mensual-8002', ['empleados' => $asistencia]);
        $pdf->setPaper('LEGAL', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);
        return $pdf->stream('Reporte-Mensual-8002.pdf');
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
            if($parametros['anio'] != "" && $parametros['mes']!="" && $parametros['tipo_trabajador'] != "" && $parametros['documento'] != "")
            {
                $anio = $parametros['anio'];
                $mes = $parametros['mes'];
                $tipo_trabajador = $parametros['tipo_trabajador'];
                $nombre = $parametros['nombre'];
                $documento = $parametros['documento'];
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
        
        //obtener dias contingencia

        $contingencia  = Contingencia::where("STARTTIME", ">=", $fecha_inicio.'T00:00:00')->where("STARTTIME", "<=", $fecha_fin.'T23:59:59')->get();
        $arreglo_contingencia = array();
        if(count($contingencia) > 0)
        {
            $arreglo_contingencia = $this->contingencia($contingencia);
        }


        $obtengoclues = CluesUser::where("user_id","=",auth()->user()['id'])->get();
        $arreglo_clues = [];
        if(count($obtengoclues) > 0)
        {
            $arreglo_clues = $this->clues_users($obtengoclues);
            
        }  
        //dd($obtengoclues);
        //Obtenemos salidas autorizadas
        $salidas   = SalidaAutorizada::where("STARTTIME", ">=", $fecha_inicio.'T00:00:00')->where("STARTTIME", "<=", $fecha_fin.'T23:59:59')->get();
        $arreglo_salidas = array();
        if(count($salidas) > 0)
            $arreglo_salidas = $this->salidas($salidas);
        
       
        
        $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'dias_otorgados.siglas', 'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("CHECKTIME", ">=", $fecha_inicio.'T00:00:00')->where("CHECKTIME", "<=", $fecha_fin.'T23:59:59');
        }, 'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("STARTDATE", "<=", $fecha_inicio.'T00:00:00');//->where("ENDDATE", ">=", $fecha_fin.'T00:00:00');
        }/*, 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("CHECKTIME", ">=", $fecha_inicio.'T00:00:00')->where("CHECKTIME", "<=", $fecha_fin.'T23:59:59');
        }*/, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){       
            $query->where("ENDSPECDAY","<=", $fecha_fin.'T23:59:59')                   
                   ->where("STARTSPECDAY", ">=", $fecha_inicio.'T00:00:00')
                        ->orWhere("ENDSPECDAY", ">=", $fecha_inicio.'T00:00:00'); 
        }])
        ->whereNull("state")
        ->where("carType", '<>','700230001')
        //->WHERE("FPHONE", "=", 'CSSSA017213')
        ->WHEREIN("FPHONE", $arreglo_clues)
        ->Where(function($query2)use($parametros){
            $query2->where('Name','LIKE','%'.$parametros['nombre'].'%')
                    ->orWhere('TITLE','LIKE','%'.$parametros['nombre'].'%')
                    ->orWhere('Badgenumber', $parametros['nombre']);
        })
        ->where("ur_id", "=", $tipo_trabajador)
       //->where("userid", "=", "28353")
        ->orWhereNull("ur_id")
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
            //dd( $checadas_empleado);
            //return response()->json(["usuarios" => $i]);
            $i = 1;
            for($i; $i<=$dias_mes; $i++)
            {
                $fecha_evaluar = new Carbon($fecha_inicio);
                $fecha_evaluar->day = $i;
              //  dd($fecha_evaluar);
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
                                    if(!array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_festivos) && !array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_contingencia))
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
        $tipo_nomina = Departamentos::where("id", "=",$tipo_trabajador)->first();
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
    function clues_users($arreglo)
    {
        $arreglo_clues = array();
        $arrprueba = [];
        foreach ($arreglo as $key => $value) {
            $arreglo_clues[] = $value->clues;
            //$arrprueba = implode(", ",$arreglo_clues);

            //$arrprueba = "'".implode("','",$arreglo_clues)."'";
           
        }
        return $arreglo_clues;//$arreglo_clues;
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

    function omisiones($arreglo, $checadas)
    {
        $arreglo_omisiones = array();
        foreach ($arreglo as $key => $value) {
            //$arreglo_omisiones[substr($value->CHECKTIME, 0,10)][] = $value;
            $checadas[substr($value->CHECKTIME, 0,10)][] = $value;
            /*if($value->CHECKTYPE == "I")
            {
                $arreglo_omisiones['entradas'][substr($value->CHECKTIME, 0,10)][] = $value;
            }else if($value->CHECKTYPE == "O")
            {
                $arreglo_omisiones['salidas'][substr($value->CHECKTIME, 0,10)][] = $value;
            }*/
        }
        return $checadas;
    }

    function dias_otorgados($arreglo)
    {
        $arreglo_dias = array();

        
        foreach ($arreglo as $key => $value) {
                    
    
            if($value->siglas != null)
            {
                switch(intval($value->siglas->Classify))
                {
                    case 1:
                        $inicio = new Carbon($value->STARTSPECDAY);
                        $fin = new Carbon($value->ENDSPECDAY);
                        $diff = $inicio->diffInDays($fin);   
                        $arreglo_dias['festivos'][substr($inicio, 0,10)][] = $value;
                        for ($i=0; $i < $diff; $i++) { 
                            $arreglo_dias['festivos'][substr($inicio->addDays(), 0,10)][] = $value;
                            
                         }  break;
                    case 2: 
                    case 3: $arreglo_dias['entradas'][substr($value->STARTSPECDAY, 0,10)][] = $value; break;
                    case 4: 
                    case 5: $arreglo_dias['salidas'][substr($value->STARTSPECDAY, 0,10)][] = $value; break;

                }
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

    public function dias_contingencia($fecha_inicio, $fecha_fin)
    {
        $contingencia   = Contingencia::where("STARTTIME", ">=", $fecha_inicio)->where("STARTTIME", "<=", $fecha_fin)->get();
        $arreglo_contingencia = array();
        if(count($contingencia) > 0){
            $arreglo_contingencia = $this->contingencia($contingencia);
        }
        return $arreglo_contingencia;
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
        $obtengoclues = CluesUser::where("user_id","=",auth()->user()['id'])->get();
        $arreglo_clues = [];
        if(count($obtengoclues) > 0)
        {
            $arreglo_clues = $this->clues_users($obtengoclues);
            
        }

        
        $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'dias_otorgados.siglas', 'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
        }, 'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("STARTDATE", "<=", $fecha_inicio);//->where("ENDDATE", ">=", $fecha_fin.'T00:00:00');
        }, 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
            $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
        }, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){
           $query->where("ENDSPECDAY","<=", $fecha_fin)                   
                   ->where("STARTSPECDAY", ">=", $fecha_inicio)
                        ->orWhere("ENDSPECDAY", ">=", $fecha_inicio);   
        }])
        ->leftjoin("empleados_sirh", "empleados_sirh.rfc", "=", "USERINFO.TITLE")
        ->whereNull("state")
        ->where("carType", '<>','700230001')
      //  ->whereIn("FPHONE", ['CSSSA017213', 'CSSSA017324'])
      ->WHEREIN("FPHONE", $arreglo_clues)
        ->where(function($query2)use($parametros){
            $query2->where('Name','LIKE','%'.$parametros['nombre'].'%')
                    ->orWhere('TITLE','LIKE','%'.$parametros['nombre'].'%')
                    ->orWhere('Badgenumber', $parametros['nombre']);
        })
        ->where("ur_id", "=", $parametros['tipo_trabajador'])

        //->where("carBrand", "=", $parametros['tipo_trabajador'])
      //  ->where("USERID", "=","28353")
        ->orderBy("carType", "DESC")
        //->limit(296)
        ->get();
        
        return $empleados;
    }

    function validaHorario($fecha_evaluar, $indice_horario_seleccionado, $horarios_periodo, $dias_habiles, $jornada_laboral)
    {
        if($indice_horario_seleccionado < count($horarios_periodo))
        {
            $fecha_inicio_periodo =  new Carbon($horarios_periodo[$indice_horario_seleccionado]->STARTDATE);
            $fecha_fin_periodo =  new Carbon(substr($horarios_periodo[$indice_horario_seleccionado]->ENDDATE, 0,10)."T23:59:59");
            if(count($dias_habiles) == 0)
            {
                $dias_habiles = $this->dias_horario($horarios_periodo[$indice_horario_seleccionado]->detalleHorario);
            }
            if($jornada_laboral == 0)
            {
                //var_dump($dias_habiles);
                $dias_jornada = $horarios_periodo[$indice_horario_seleccionado]->detalleHorario;
                $inicio_jornada = $dias_jornada[0]['STARTTIME'];
                $fin_jornada    = $dias_jornada[0]['ENDTIME'];
                $jornada_inicio =new Carbon($inicio_jornada);
                $jornada_fin    =new Carbon($fin_jornada);
                $jornada_fin->addMinutes(30);
                $jornada_laboral = $jornada_fin->diffInHours($jornada_inicio);
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

            return array("indice" => $indice_horario_seleccionado, "inicio_periodo" => $fecha_inicio_periodo, "fin_periodo" => $fecha_fin_periodo, "habiles" => $dias_habiles, "jornada"=> $jornada_laboral);
        }else{
            return 0;//Sin Horario
        }
    }

    function VerificadorAsistencia($fecha_evaluar, $validacion_horario, $checadas_empleado, $dias_otorgados, $arreglo_salidas)
    {
        $dia = $fecha_evaluar->dayOfWeekIso;
        $dia_inicio = intval($validacion_horario['habiles'][$dia]->SDAYS);
        $dia_final  = intval($validacion_horario['habiles'][$dia]->EDAYS);
        $diferencia_dias = $dia_final - $dia_inicio;
        
        $dia_seleccionado = $validacion_horario['habiles'][$fecha_evaluar->dayOfWeekIso]->reglaAsistencia;
                  
        $num_dia_jornada = floatval($dia_seleccionado->WorkDay);
        $inicio_entrada = new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime1, 11,8));
        $fin_entrada =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckInTime2, 11,8));
        if($diferencia_dias == 0)
        {
            $inicio_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime1, 11,8));
            $inicio_salida_fija =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime1, 11,8));
            $fin_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime2, 11,8));
        }

        $checada_entrada = 0;
        $checada_salida  = 0;
        
        $calcular_entrada  = 0;
        $calcular_salida  = 0;
        
        $minutos_entrada = 0;
        $minutos_salida  = 0;
        $simbolo_turno = "F";
        $contador_retardo = 0;
        $contador_faltas = 0;
        $contador_asistencia = 0;
        //dd($dias_otorgados);
        if(isset($dias_otorgados['entradas'][$fecha_evaluar->format('Y-m-d')]) && $dias_otorgados['entradas'][$fecha_evaluar->format('Y-m-d')][0]->siglas->Classify == 2){ $checada_entrada = 1; }
        if(isset($dias_otorgados['entradas'][$fecha_evaluar->format('Y-m-d')]) && $dias_otorgados['entradas'][$fecha_evaluar->format('Y-m-d')][0]->siglas->Classify == 3){ $calcular_entrada = 1; }
        if(isset($dias_otorgados['salidas'][$fecha_evaluar->format('Y-m-d')]) && $dias_otorgados['salidas'][$fecha_evaluar->format('Y-m-d')][0]->siglas->Classify == 4){ $checada_salida = 1; }
        if(isset($dias_otorgados['salidas'][$fecha_evaluar->format('Y-m-d')]) && $dias_otorgados['salidas'][$fecha_evaluar->format('Y-m-d')][0]->siglas->Classify == 5){ $calcular_salida = 1; }

        //return array("E"=>$checada_entrada, "EC" => $calcular_entrada, "S"=>$checada_salida, "SC"=>$calcular_salida);
        //omisiones falta checar
        
        
        $tolerancia = ( intval($dia_seleccionado->LateMinutes) + 1);//Se agrega regla de tolerancia 
        $fecha_hora_entrada_exacta = new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($validacion_horario['habiles'][$fecha_evaluar->dayOfWeekIso]->STARTTIME, 11, 8));
        if($calcular_entrada == 1)
        {
            $fecha_hora_entrada_exacta->addMinutes(180);
        }else{
            $fecha_hora_entrada_exacta->addMinutes($tolerancia);
        }
        if($calcular_salida == 1 && $diferencia_dias == 0)
        {
            $inicio_salida->subMinutes(120);
        }

        foreach ($checadas_empleado[$fecha_evaluar->format('Y-m-d')] as $index_checada => $dato_checada) {
                                                
            $checada = new Carbon($dato_checada->CHECKTIME);
            if($checada_entrada == 0)
            {
                if($checada->greaterThanOrEqualTo($inicio_entrada) && $checada->lessThanOrEqualTo($fin_entrada))
                {
                    if($checada->greaterThan($fecha_hora_entrada_exacta) && $calcular_entrada == 0){
                        $checada_entrada = 2;
                    }else{
                        $checada_entrada = 1; 
                    
                        if($calcular_entrada == 1)
                        {
                            $minutos_entrada = $checada->diffInMinutes($fecha_hora_entrada_exacta);
                        } 
                    }
                }
                
            }
            if($checada_salida == 0 && $diferencia_dias == 0)
            {
                if($checada->greaterThanOrEqualTo($inicio_salida) && $checada->lessThanOrEqualTo($fin_salida))
                {
                    $checada_salida = 1;
                    if($calcular_salida == 1)
                    {
                        $minutos_salida = $checada->diffInMinutes($inicio_salida_fija->addMinutes(1));
                        //return array("checada"=>$checada, "salida"=>$inicio_salida_fija);
                    }
                }
            }
        }

        if($checada_entrada == 1 && isset($arreglo_salidas[$fecha_evaluar->format('Y-m-d')]))
        {
            $checada_salida = 1;
        }
       // dd($checadas_empleado[$fecha_evaluar->format('Y-m-d')]);
        if($diferencia_dias != 0)
        {
            $fecha_evaluar->addDays($diferencia_dias);
            $inicio_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime1, 11,8));
            $inicio_salida_fija =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime1, 11,8));
            $fin_salida =  new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime2, 11,8));
            
            if(isset($dias_otorgados['salidas'][$fecha_evaluar->format('Y-m-d')]) && $dias_otorgados['salidas'][$fecha_evaluar->format('Y-m-d')][0]->siglas->Classify == 4){ $checada_salida = 1; }
            if(isset($dias_otorgados['salidas'][$fecha_evaluar->format('Y-m-d')]) && $dias_otorgados['salidas'][$fecha_evaluar->format('Y-m-d')][0]->siglas->Classify == 5){ $calcular_salida = 1; $inicio_salida->subMinutes(120); }

            if($checada_entrada == 1 && isset($arreglo_salidas[$fecha_evaluar->format('Y-m-d')]))
            {
                $checada_salida = 1;
            }
            if($checada_salida == 0)
            {
                if(isset($checadas_empleado[$fecha_evaluar->format('Y-m-d')])){
                    foreach ($checadas_empleado[$fecha_evaluar->format('Y-m-d')] as $index_checada => $dato_checada) {
                    
                        $checada = new Carbon($dato_checada->CHECKTIME);
                
                        if($checada_salida == 0 )
                        {
                            
                            if($checada->greaterThanOrEqualTo($inicio_salida) && $checada->lessThanOrEqualTo($fin_salida))
                            {
                                $checada_salida = 1;
                                if($calcular_salida == 1)
                                {
                                    $minutos_salida = $checada->diffInMinutes($inicio_salida_fija->addMinutes(1));
                                }
                            }
                        }
                    }
                 }
            }
        }

        if($checada_entrada == 1 && $checada_salida == 1)
        {
            $simbolo_turno = "A";
            $contador_asistencia++;
        }else if($checada_entrada == 2 && $checada_salida == 1)
        {
            $contador_retardo++;
            $simbolo_turno = "R1";
        }else if($checada_entrada == 0 && $checada_salida == 1)
        {
            $simbolo_turno = "FE";
            $contador_faltas++;
        }else if(($checada_entrada == 1 || $checada_entrada == 2 ) && $checada_salida == 0)
        {
            $simbolo_turno = "FS";
            $contador_faltas++;
        }else if($checada_entrada == 0 && $checada_salida == 0)
        {
            $simbolo_turno = "F";
            $contador_faltas++;
        }

        if($diferencia_dias > 1)
        {   
            $diferencia_dias--;
        }else
        {
            $diferencia_dias = 0;
        }
        return array("simbolo"=>$simbolo_turno, "asistencia"=>$contador_asistencia, "turno_dias"=> $num_dia_jornada, "retardos_menores"=>$contador_retardo, "faltas"=>$contador_faltas, 'contador_pases'=>$calcular_salida, "diferencia" => $diferencia_dias, "minutos_entrada"=>$minutos_entrada, "minutos_salida"=>$minutos_salida);
        
    }

    function claseFaltas(Request $request)
    {
        
        $parametros = Input::all();
        $fecha_limite_actual = Carbon::now();
        $anio = $fecha_limite_actual->year;
        $mes  = $fecha_limite_actual->month;
        $arreglo_resultado = Array();
        if(count($parametros) > 0)
        {
            if($parametros['anio'] != "" && $parametros['mes']!="" && $parametros['tipo_trabajador'] != "" && $parametros['documento'] != "")
            {
                $anio = $parametros['anio'];
                $mes = $parametros['mes'];
                $tipo_trabajador = $parametros['tipo_trabajador'];
                $nombre = $parametros['nombre'];
                $documento =  $parametros['documento'];
            }
        }
        
        $fecha_inicio = Carbon::create($anio, $mes, 01,0,0,0);  
        $fecha_fin = Carbon::create($anio, $mes, $fecha_inicio->daysInMonth, 23,59,59); 
        
        $arreglo_festivos = $this->dias_festivos($fecha_inicio, $fecha_fin);
        $arreglo_salidas = $this->salidas_autorizadas($fecha_inicio, $fecha_fin);
        $empleados = $this->empleados_checadas($fecha_inicio, $fecha_fin, $parametros);
        
       // dd($empleados);
        $personal = 0;
        foreach ($empleados as $index_empleado => $data_empleado) {
            $empleado_seleccionado = $empleados[$index_empleado];
            $horarios_periodo = $data_empleado->horarios;
            $indice_horario_seleccionado = 0;
            $arreglo_consulta = array();
            $dia_falta = array();
            $dia_falta_quincenas = array("Q1" => array(), "Q2" => array());
            $dia_retardos_quincenas = array("Q1" => array(), "Q2" => array());
            $dias_habiles = array();
            $banderaHorarios = false;
            $jornada_laboral = 0;

            $resumen = ["ASISTENCIA" => 0, "FALTAS" => 0, "FALTAS_TOTALES" => 0, "RETARDOS" => 0, "RETARDOS_QUINCENALES" => array("Q1"=>array(), "Q2"=>array()), "FALTAS_QUINCENALES" => array("Q1"=>array(), "Q2"=>array()), 'RETARDOS_1' =>0, 'RETARDOS_2' =>0, "OMISIONES" => 0, "JUSTIFICADOS" => 0, "MINUTOS_PASES" => 0];
            
            $checadas_empleado  = $this->checadas_empleado($data_empleado->checadas);
            //return $checadas_empleado;
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
                
                $validacion_horario = $this->validaHorario($fecha_evaluar, $indice_horario_seleccionado, $horarios_periodo, $dias_habiles, $jornada_laboral);
                if (!is_array($validacion_horario)) {
                    $personal++;
                    continue;
                }
                if($validacion_horario['jornada'] !=0)
                {
                    $jornada_laboral = $validacion_horario['jornada'];
                }

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
                    if(isset($dias_otorgados['festivos']) && array_key_exists($fecha_evaluar->format('Y-m-d'), $dias_otorgados['festivos'])){ $arreglo_consulta[$i] = $dias_otorgados['festivos'][$fecha_evaluar->format('Y-m-d')][0]->siglas->ReportSymbol; $resumen['OMISIONES']++; /**/ continue; }//Se Valida el dia completo con justificante
                    if(array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_festivos)){ $arreglo_consulta[$i] = "DF"; continue; }//Se Valida el dia completo festivo
                    if(array_key_exists($fecha_evaluar->dayOfWeekIso, $dias_habiles) && !array_key_exists($fecha_evaluar->format('Y-m-d'), $checadas_empleado)){ 
                        $dia_seleccionado = $dias_habiles[$fecha_evaluar->dayOfWeekIso]->reglaAsistencia;                  
                        $num_dia_jornada = floatval($dia_seleccionado->WorkDay);

                        $arreglo_consulta[$i] = "F";
                        $resumen['FALTAS']++;
                        $resumen['FALTAS_TOTALES'] += ( 1 * $num_dia_jornada);  
                        if($i <= 15)
                        {
                            $dia_falta_quincenas['Q1'][] = $i;
                        }else{
                            $dia_falta_quincenas['Q2'][] = $i;
                        }
                        continue; 
                    }// Tiene horario y no checadas es falta directa, habiendo justificado dias
                    
                    $resultado = $this->VerificadorAsistencia($fecha_evaluar, $validacion_horario, $checadas_empleado, $dias_otorgados, $arreglo_salidas);
                    //return $resultado;
                    $arreglo_consulta[$i] = $resultado['simbolo'];  
                    $i = $i + $resultado['diferencia'];
                    $resumen['ASISTENCIA'] += $resultado['asistencia']; 
                    $resumen['FALTAS'] += $resultado['faltas'];  
                    $resumen['FALTAS_TOTALES'] += ($resultado['faltas'] * $resultado['turno_dias']);  
                    $resumen['RETARDOS_1'] += $resultado['retardos_menores']; 
                    
                    if($resultado['retardos_menores'] == 1)
                    {
                        if($i <= 15)
                        {
                            $dia_retardos_quincenas["Q1"][] = $i;
                        }else
                        {
                            $dia_retardos_quincenas["Q2"][] = $i;
                        }
                    }

                    if($resultado['faltas'] == 1)
                    {
                        if($i <= 15)
                        {
                            $dia_falta_quincenas['Q1'][] = $i;
                        }else{
                            $dia_falta_quincenas['Q2'][] = $i;
                        }
                    }


                    $resumen['MINUTOS_PASES'] += $resultado['minutos_salida']; 
                    if($resultado['faltas']){ $dia_falta[] = $i; }

                    $resumen['FALTAS'] += intval($resumen['RETARDOS_1'] / 7);
                    $resumen['FALTAS_TOTALES'] += intval($resumen['RETARDOS_1'] / 7);
                    //Falta ver que pex con los retardos mayores
                }
            }
            $resumen['HORAS_PASES'] =  $resumen['MINUTOS_PASES'] / 60;
            
            
            $faltas_x_retardos_q1 = intval((count($dia_retardos_quincenas["Q1"])) / 7);
           // dd($faltas_x_retardos_q1);
            if($faltas_x_retardos_q1 > 0)
            {
                $dia_falta_quincenas["Q1"][] = "7R1";    
            }

            $retardos_no_utilizados_q1 = (count($dia_retardos_quincenas["Q1"]) % 7);
            $faltas_x_retardos_q2 = intval((count($dia_retardos_quincenas["Q2"]) + $retardos_no_utilizados_q1) / 7);
            if($faltas_x_retardos_q2 > 0)
            {
                $dia_falta_quincenas["Q2"][] = "7R1";    
            }
             //   dd($faltas_x_retardos_q2 );

             //$faltas_x_retardos_q2;
            $resumen['RETARDOS_QUINCENALES'] = $dia_retardos_quincenas;
            $resumen['FALTAS_QUINCENALES'] = $dia_falta_quincenas;
            
            $empleados[$index_empleado]['resumen'] = $resumen;
            $empleados[$index_empleado]['asistencia'] = $arreglo_consulta;
            $empleados[$index_empleado]['dia_falta'] = $dia_falta;
            $empleados[$index_empleado]['jornada'] = $jornada_laboral;
            if($resumen['FALTAS'] >= 1)
            {
                $arreglo_resultado[] = $empleados[$index_empleado];
            }
        }
        //dd($resumen['RETARDOS_1']);
        //echo $personal;
        $tipo_nomina = Departamentos::where("id", "=",$tipo_trabajador)->first();
        return array("datos" => $arreglo_resultado, "filtros" => $parametros, "nombre_mes"=> $this->catalogo_meses[$parametros['mes']], "tipo_trabajador" => $tipo_nomina);
    }
    
    
}