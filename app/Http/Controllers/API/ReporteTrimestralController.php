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
use App\Models\ConfiguracionUnidad;
use App\Models\CluesUser;
use Illuminate\Support\Facades\Input;

class ReporteTrimestralController extends Controller
{
    public function index(Request $request)
    {
        //return Input::all();
        $empleados = $this->claseAsistencia($request);
        
        return response()->json(["usuarios" => $empleados['datos']]);
       // return response()->json(["usuarios" => $empleados]);
    }

    public function reporteTrimestral(Request $request)
    {
        $parametros = Input::all();
        $usuario = Auth::user();
        
      //dd($usuario);
       
        $datos_configuracion = ConfiguracionTrimestral::where("trimestre", $parametros['trimestre'])
                                                    ->where("anio", $parametros['anio'])
                                                    ->where("tipo_trabajador", $parametros['tipo_trabajador'])
                                                    ->first();
        $datos_unidad = ConfiguracionUnidad::first();
        //return $usuario;
        $asistencia = $this->claseAsistencia($request);
       //dd($asistencia);
        //return $asistencia;
        $pdf = PDF::loadView('reportes//reporte-trimestral', ['empleados' => $asistencia, 'usuario' => $usuario, "config" => $datos_configuracion,"unidad" => $datos_unidad]);
        $pdf->setPaper('LEGAL', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true ,'isRemoteEnabled' => true]);
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
            /* $fecha_ejercicio='2021-12-01';
            $fecha_ejercicio= new Carbon($fecha_ejercicio);*/

          
            $fecha_ejercicio = Carbon::now();
            $fecha_ejercicio->year =  $anio;

            //dd($data_trimestre."trim".$anio);
            $fecha_ejercicio->day = 1; 
            $fecha_ejercicio->month = $data_trimestre;
         //   dd($fecha_ejercicio);
           
           
            $fecha_inicio = $fecha_ejercicio->format('Y-m-d');
            $fecha_fin = $fecha_ejercicio->format('Y-m-').$fecha_ejercicio->daysInMonth;
            $dias_mes = $fecha_ejercicio->daysInMonth;

            //dd( $fecha_ejercicio);
            //$dias_mes = $dias_mes;
           //dd($fecha_fin);
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


             $obtengoclues = CluesUser::where("user_id","=",auth()->user()['id'])->get();
             $arreglo_clues = [];
             if(count($obtengoclues) > 0)
             {
                 $arreglo_clues = $this->clues_users($obtengoclues);
                 
             }  
       //  dd($arreglo_clues);
           //dd("inicio: ".$fecha_inicio. "  fin: ".$fecha_fin);
            // print_r($obtengoclues);
            //Obtenemos las checadas de todoos los trabajadores
            $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("CHECKTIME", ">=", $fecha_inicio.'T00:00:00')->where("CHECKTIME", "<=", $fecha_fin.'T23:59:59');
            }, 'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
               /*  $query->where("STARTDATE", "<=", $fecha_inicio.'T00:00:00')
                ->orWhere("STARTDATE", "<=", $fecha_fin.'T00:00:00')
                ->orderBy('STARTDATE'); */
                $query->whereRaw("( ENDDATE >= '". $fecha_inicio."T00:00:00' and  STARTDATE <= '".$fecha_fin."T23:59:59')");
            }, 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("CHECKTIME", ">=", $fecha_inicio.'T00:00:00')->where("CHECKTIME", "<=", $fecha_fin.'T23:59:59');
            }, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("ENDSPECDAY","<=", $fecha_fin.'T23:59:59')                   
                   ->where("STARTSPECDAY", ">=", $fecha_inicio.'T00:00:00')
                        ->orWhere("ENDSPECDAY", ">=", $fecha_inicio.'T00:00:00');   
                               
                        
                      
            
            
            }])
             ->whereNull("state")
            ->where("ATT","=","1")
            ->WHERE("PAGER", "NOT LIKE", 'CF%') 
          //  ->where("carType", '<>','700230001')
          //->WHEREIN("FPHONE", ['CSSSA017213','CSSSA017324'])
          //  ->WHEREIN("FPHONE",[$arreglo_clues])
          ->WHEREIN("FPHONE", $arreglo_clues)
            ->WHERE("OPHONE", "!=", 3)
            ->Where(function($query2)use($parametros){
                $query2->where('Name','LIKE','%'.$parametros['nombre'].'%')
                        ->orWhere('TITLE','LIKE','%'.$parametros['nombre'].'%')
                        ->orWhere('Badgenumber', $parametros['nombre']);
            })
            //->where("TITLE","=", 'AESS770416PJ4')
            ->where("ur_id", "=", $tipo_trabajador)
            //->limit(200) 
            ->orderBy("carType", "DESC")
            ->get();
            //dd($empleados);
           //return $empleados;
            foreach ($empleados as $index_empleado => $data_empleado) {
                $empleado_seleccionado = $empleados[$index_empleado];
                
                if(!array_key_exists($empleados[$index_empleado]->TITLE, $empleados_trimestral))
                {
                    $empleados_trimestral[$empleados[$index_empleado]->TITLE] = $empleados[$index_empleado];
                    $empleados_trimestral[$empleados[$index_empleado]->TITLE]['TRIMESTRAL'] = 0;
                    $empleados_trimestral[$empleados[$index_empleado]->TITLE]['jornada_laboral'] = 0;
                }
                
               // dd($empleados[$index_empleado]->TITLE);
                $horarios_periodo = $data_empleado->horarios;
               // dd($horarios_periodo);
                $indice_horario_seleccionado = 0;
                $arreglo_consulta = array();
                $dias_habiles = array();
    
                $checadas_empleado  = $this->checadas_empleado($data_empleado->checadas);
                $omisiones          = $this->omisiones($data_empleado->omisiones);
                $dias_otorgados     = $this->dias_otorgados($data_empleado->dias_otorgados);
                //return array("datos" => $dias_otorgados);
                $verificador = 0;
              
                $jornada_laboral = 0;
                
                //$checada_fuera=0;
                $dia_economico = 0;
                $checada_fuera = false;
                //dd($dias_otorgados);
                //dd($indice_horario_seleccionado." cuantos ". count($horarios_periodo));
                if($dias_otorgados != -1)
                {
                    
                  
                    for($i = 1; $i<=$dias_mes; $i++)
                    {
                       
                        
                        $fecha_evaluar = new Carbon($fecha_inicio);
                        $fecha_evaluar->day = $i;
                       // dd($fecha_evaluar);
                        if($fecha_evaluar->lessThan($fecha_limite_actual))
                        {
                           
                            if($indice_horario_seleccionado < count($horarios_periodo))
                            {
                                 //verificador de horas de jornada
               
                                if($jornada_laboral == 0)
                                {
                                   if(count($horarios_periodo[$indice_horario_seleccionado]['detalleHorario'])>0){
                                        $inicio_jornada = $horarios_periodo[$indice_horario_seleccionado]['detalleHorario'][0]['STARTTIME'];                                       
                                        $fin_jornada    = $horarios_periodo[$indice_horario_seleccionado]['detalleHorario'][0]['ENDTIME'];
                                        $jornada_inicio =new Carbon($inicio_jornada);
                                        $jornada_fin    =new Carbon($fin_jornada);
                                        $jornada_fin->addMinutes(30);
                                        $jornada_laboral = $jornada_fin->diffInHours($jornada_inicio);
                                        //$jornada_laboral =1;
                                        $empleados_trimestral[$empleados[$index_empleado]->TITLE]['jornada_laboral'] = $jornada_fin->diffInHours($jornada_inicio);
                                   }else{
                                    continue;
                                   }
                                        //return array("datos" => $jornada_laboral);
                                }
                                //fin veririficador
                                
                                $fecha_inicio_periodo =  new Carbon($horarios_periodo[$indice_horario_seleccionado]->STARTDATE);
                                $fecha_fin_periodo =  new Carbon(substr($horarios_periodo[$indice_horario_seleccionado]->ENDDATE, 0,10)."T23:59:59");
                                //dd( $fecha_fin_periodo);
                                
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
                                        //dd($fecha_fin_periodo);
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
                                          
                                            
                                            //$fecha_hora_entrada_exacta->addMinutes(1);
                                            $fecha_hora_entrada_exacta->addSeconds(59); 

                                          // dd( $fecha_hora_entrada_exacta);
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
                                                        //dd($checada_entrada);
                                                        if($checada_salida == 0)
                                                        {
                                                            if($checada->greaterThanOrEqualTo($inicio_salida) && $checada->lessThanOrEqualTo($fin_salida))
                                                            {
                                                                $checada_salida = 1;
                                                            }
                                                        }
                                                    }
                                                   // dd("entrada ".$checada_entrada. "salida ".$checada_salida);

                                                    if(array_key_exists($fecha_evaluar->format('Y-m-d'), $omisiones))
                                                    {
                                                        foreach ($omisiones[$fecha_evaluar->format('Y-m-d')] as $index_omision => $dato_omision) {
                                                            if($dato_omision->CHECKTYPE == "I" || $dato_omision->CHECKTYPE == "E" || $dato_omision->CHECKTYPE == "R")
                                                            {
                                                                $checada_entrada = 1;
                                                            }
                                                            
                                                            if($dato_omision->CHECKTYPE == "O" || $dato_omision->CHECKTYPE == "S"    )
                                                            {
                                                                $checada_salida = 1;
                                                            }
                                                        }
                                                        
                                                    }
                                                    
                                                    
                                                    if($checada_entrada == 1 and $checada_salida == 1){
                                                        
                                                    $verificador++;
                                                    
                                                    }else if($checada_entrada == 2 and $checada_salida == 1){
                                                       // dd("1 ".$fecha_evaluar->format('Y-m-d'));exit();
                                                        break;
                                                       
                                                    }else if(($checada_entrada == 1 or $checada_entrada == 2) and $checada_salida == 0)
                                                    { 
                                                        if(array_key_exists($fecha_evaluar->format('Y-m-d'), $arreglo_salidas) || array_key_exists($fecha_evaluar->format('Y-m-d'), $dias_otorgados))
                                                        {
                                                            if($checada_entrada == 1 )
                                                            {
                                                                $verificador++;
                                                            }else if($checada_entrada == 2 ){
                                                               // dd("2 ".$fecha_evaluar->format('Y-m-d'));exit();
                                                                break;
                                                            }
                                                        }else{
                                                            //dd("3 ".$fecha_evaluar->format('Y-m-d'));exit();
                                                            break;
                                                        } 
                                                        
                                                    }else if($checada_entrada == 0 and $checada_salida == 1)  
                                                    {
                                                        if(array_key_exists($fecha_evaluar->format('Y-m-d'), $dias_otorgados)){
                                                            $verificador++;
                                                        }else{
                                                         //   dd("4 ".$fecha_evaluar->format('Y-m-d'));exit();
                                                            break;
                                                        }
                                                        //break;
                                                    }else if($checada_entrada == 0 and $checada_salida == 0)  
                                                    {
                                                       // dd("5 ".$fecha_evaluar->format('Y-m-d'));exit();
                                                        break;
                                                    }
                                                    
                                                }else
                                                {
                                                  //  dd("6 ".$fecha_evaluar->format('Y-m-d'));exit();
                                                    break;
                                                }
                                            }else{
                                                //Dias festivos
                                                $verificador++;
                                                
                                            }
                                            //unset($dias_otorgados[$fecha_evaluar->format('Y-m-d')]);
                                        }


                                    else{
                                            //return array("datos" =>intval($dias_habiles[$fecha_evaluar->dayOfWeekIso]['reglaAsistencia']['WorkDay']));
                                            /* if($dias_otorgados[$fecha_evaluar->format('Y-m-d')][0]['DATEID'] == 6)
                                            {
                                                $dia_laboral = intval($dias_habiles[$fecha_evaluar->dayOfWeekIso]['reglaAsistencia']['WorkDay']);//Revisar urgente
                                                $dia_economico = $dia_economico + $dia_laboral;
                                            }
                                            if($dia_economico == 2)
                                            {
                                              //  dd("7 ".$fecha_evaluar->format('Y-m-d'));exit();
                                                break;
                                            } */
                                          //  dd("holaaaa" .$fecha_evaluar);
                                             switch($dias_otorgados[$fecha_evaluar->format('Y-m-d')][0]['DATEID']){
                                                case 1:
                                                  //dd("fecha: ".$fecha_evaluar->format('Y-m-d'));
                                                    $fecha_hora_entrada_exacta = new Carbon($fecha_evaluar->format('Y-m-d')."T".substr($dias_habiles[$fecha_evaluar->dayOfWeekIso]->STARTTIME, 11, 8));
                                                    $fecha_hora_entrada_exacta->addMinutes(1);
                                                    
                                                   
                                                    //
                                                       if(array_key_exists($fecha_evaluar->format('Y-m-d'), $checadas_empleado))
                                                        { 
                                                           
                                                           
                                                            foreach ($checadas_empleado[$fecha_evaluar->format('Y-m-d')] as $index_checada => $dato_checada) {
                                                                 
                                                                $checada_entrada_nueva = new Carbon($dato_checada->CHECKTIME);  
                                                                     
                                                                        if($checada_entrada_nueva->lessThanOrEqualTo($fecha_hora_entrada_exacta))
                                                                        {            
                                                                            
                                                                            $checada_fuera=true; 
                                                                           // break;                                                                           //break;                                                                          
                                                                            
                                                                        }
                                                                        
                                                                   
                                                                } 
                                                                                                                
                                                                   
                                                                    
                                                        }   
                                                     
                                                           
                                                break;
    
                                                case 6:
                                                    $dia_laboral = intval($dias_habiles[$fecha_evaluar->dayOfWeekIso]['reglaAsistencia']['WorkDay']);//Revisar urgente
                                                    $dia_economico = $dia_economico + $dia_laboral;  
                                                   
                                                   // dd($dia_economico);

                                                break;
                                                

                                           
                                             }
                                              
                                            // 77dd($dia_economico);
                                             if($dia_economico == 2)
                                             {
                                             //  dd("7 ".$fecha_evaluar->format('Y-m-d'));exit();
                                                 break;
                                             } 
                                           // dd($checada_fuera);
                                            
                                             if($checada_fuera == false && $dias_otorgados[$fecha_evaluar->format('Y-m-d')][0]['DATEID']==1){
                                              //  echo("      var= ".$checada_fuera."fecha".$fecha_evaluar."tipo".$dias_otorgados[$fecha_evaluar->format('Y-m-d')][0]['DATEID']."checo".$dato_checada->CHECKTIME);
                                             //  dd($fecha_evaluar);
                                               // $checada_fuera=0;
                                                break;
                                            }     
                                                     
                                            $checada_fuera=false;                                          
                                            $verificador++;
                                        }     
                                    }else
                                    {   
                                        //dias no habiles para su horario
                                        $verificador++;
                                    }
                                }else{
                                   // dd("8 ".$fecha_evaluar->format('Y-m-d'));exit();
                                    break;        
                                }
                            
                               
                            }else{
                              //  dd("9 ".$fecha_evaluar->format('Y-m-d'));exit();
                                break;  
                            }
                        }else
                        {
                           // dd("10 ".$fecha_evaluar->format('Y-m-d'));exit();
                            break; 
                        }    
                       
                        //echo $fecha_evaluar->format("Y-m-d")."--";
                    }
                   
                   
                }
              //  dd($pase);
            // print_r($empleados_trimestral);
        //  dd("VERi=".$verificador."dias= ".$dias_mes);
                /*if($trimestre == 1)
                {
                    $empleados_trimestral[$empleados[$index_empleado]->TITLE]['TRIMESTRAL'] += 1;
                }else*/ if($verificador == $dias_mes)
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
        $tipo_nomina = Departamentos::where("id", "=",$tipo_trabajador)->first();
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
            if($value->DATEID === 21 || $value->DATEID === 22 ){ $bandera = 1; }
            
 
           // $arreglo_dias[substr($value->STARTSPECDAY, 0,10)][] = $value;
            $inicio = new Carbon($value->STARTSPECDAY);
            $fin = new Carbon($value->ENDSPECDAY);
            $diff = $inicio->diffInDays($fin);            
            $arreglo_dias[substr($inicio, 0,10)][] = $value;
            for ($i=0; $i < $diff; $i++) { 
               $arreglo_dias[substr($inicio->addDays(), 0,10)][] = $value;
               if($value->DATEID == 8){ $licencia_medica++; }
               
            } 
            


        }
       // dd($licencia_medica);
        if($bandera == 1 || $licencia_medica >= 2)
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
