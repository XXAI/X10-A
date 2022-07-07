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

class CardexController extends Controller
{
    public function index(Request $request)
    {   
        unset($empleados);
        $parametros = Input::all();

        $obtengoclues = CluesUser::where("user_id","=",auth()->user()['id'])->get();
        $arreglo_clues = [];
        if(count($obtengoclues) > 0)
        {
            $arreglo_clues = $this->clues_users($obtengoclues);
            
        }
       // dd($arreglo_clues );
        $empleados = Usuarios::with("Sirh_Empleados")
        //leftJoin("empleados_sirh", "empleados_sirh.rfc", "=", "USERINFO.TITLE")
                                //->whereNull("USERINFO.state")
                                ->whereIn("USERINFO.FPHONE", $arreglo_clues)
                                ->Where(function($query2)use($parametros){
                                    $query2->where('Name','LIKE','%'.$parametros['filtro'].'%')
                                            ->orWhere('TITLE','LIKE','%'.$parametros['filtro'].'%')
                                            ->orWhere('Badgenumber', $parametros['filtro']);
                                })
                                ->get();
        //dd($empleados);
        
        return response()->json(["usuarios" => $empleados]);
    }

    function clues_users($arreglo)
    {
        $arreglo_clues = array();
       
        foreach ($arreglo as $key => $value) {
            $arreglo_clues[] = $value->clues;           
        }
        return $arreglo_clues;//$arreglo_clues;
    }

    public function reporteCardex(Request $request)
    {
        unset($empleados);
        $parametros = Input::all();
        $obtengoclues = CluesUser::where("user_id","=",auth()->user()['id'])->get();
        $arreglo_clues = [];
        if(count($obtengoclues) > 0)
        {
            $arreglo_clues = $this->clues_users($obtengoclues);
            
        }
       // dd($parametros['anio']);
        $datos = $this->claseAsistencia($parametros['empleado'],$parametros['anio']);
        //return response()->json(["usuarios" => $datos]);
        $empleados = Usuarios::with("Sirh_Empleados")
        //leftJoin("empleados_sirh", "empleados_sirh.rfc", "=", "USERINFO.TITLE")
                                //->whereNull("USERINFO.state")
                                ->whereIn("USERINFO.FPHONE", $arreglo_clues )
                                ->Where('Badgenumber', $parametros['empleado'])
                                ->first();

        $empleados->asistencia = $datos['datos'];    
        $empleados->jornada = $datos['horario'];    

    // dd($empleados);
        //return response()->json(["usuarios" => $datos]);                    
        $pdf = PDF::loadView('reportes//reporte-cardex', ['empleados' => $empleados]);
        $pdf->setPaper('LETTER', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true ,'isRemoteEnabled' => true]);
        return $pdf->stream('Reporte-Cardex.pdf');
    }

    function claseAsistencia($empleado,$anio)
    {
       
        $reglas     = ReglasHorarios::where("CheckIn", "=", 1)->get();
        $resultado = [];
        $arreglo_reglas = array();
        foreach ($reglas as $key => $value) { $arreglo_reglas[$value->schClassid] = $value;  }
        //unset($empleados);
        //dd("asd");
        //dd("a");
       $fecha_limite_inicio=new Carbon($anio.'-11-01');
        //$fecha_limite_inicio=new Carbon('2022-01-01');
        $fecha_limite_fin=new Carbon($anio.'-10-01');
        if($anio=='2022'){
            $fecha_limite_fin = Carbon::now();
        }/* else{
            $fecha_limite_fin = Carbon::now();
            $fecha_limite_fin->subYear();
        } */
      //  dd($fecha_limite_fin);
        //$fecha_limite_inicio=new Carbon('2021-10-01');
        /* if($anio==Carbon::now()->year){
            $fecha_limite_fin = Carbon::now();           
            $fecha_limite_inicio=$fecha_limite_inicio->addMonth();
        }
        else{          
           
           $fecha_limite_fin=new Carbon($anio.'-09-01');
         // $fecha_limite_fin=new Carbon('2021-11-01');
          
        } */
      //dd($fecha_limite_inicio. 'fin: '.$fecha_limite_fin);
        $anio_reporte =$anio; //$fecha_limite_fin->year;

       // dd($anio_reporte);
        /* Calculamos el periodo que nos dijeron en sistematizacion, pero yo lo calculare hacia un año atras, haber como nos va*/
        $dias_mes = $fecha_limite_inicio->daysInMonth;
        //$mes_fin = 0;
        $parametro_inicial;
        $parametro_final;
        if($dias_mes == $fecha_limite_fin->day) /* Esto es para saber si calculamos el mes actual o el anterior */
        {
            //$mes_fin = $fecha_limite_actual->month;
            $fecha_limite_inicio->subYear();
            $fecha_limite_inicio->day = 1;
            $fecha_limite_inicio->hour = 0;
            $fecha_limite_inicio->minute = 0;
            $fecha_limite_inicio->second = 0;
            
            $parametro_inicial = $fecha_limite_inicio;
            
            $fecha_limite_fin->hour = 23;
            $fecha_limite_fin->minute = 59;
            $fecha_limite_fin->second = 59;

            $parametro_final = $fecha_limite_fin;

        }else{ /* Con este procedimiento obtenemos el ultimo dia y mes completo para el calculo de la fecha final */
            
            $fecha_limite_fin = $fecha_limite_fin->subMonth();
            $fecha_limite_inicio = $fecha_limite_inicio->subMonths(2);
            
            $dias_mes = $fecha_limite_fin->daysInMonth;
            
            $fecha_limite_fin->day = $dias_mes;
            $fecha_limite_fin->hour = 23;
            $fecha_limite_fin->minute = 59;
            $fecha_limite_fin->second = 59;
            
            $parametro_final = $fecha_limite_fin;

            $fecha_limite_inicio = $fecha_limite_inicio->subYear();
            
            $fecha_limite_inicio = $fecha_limite_inicio->addMonth();
            
            $fecha_limite_inicio->day = 1;
            $fecha_limite_inicio->hour = 0;
            $fecha_limite_inicio->minute = 0;
            $fecha_limite_inicio->second = 0;

            $parametro_inicial = $fecha_limite_inicio;
            
            //$fecha_limite_actual->day = $dias_mes; //Fecha Final
        }


            $fecha_inicio = $fecha_limite_inicio->format('Y-m-d')."T00:00:00";
            
            //$parametro_final = Carbon::now();
            //$fecha_fin = $parametro_final->format('Y-m-d').'T23:59:59';
            $fecha_fin = $fecha_limite_fin->format('Y-m-d').'T23:59:59';


           //dd($fecha_inicio."  ". $fecha_fin);
            #Obtenemos los dias Festivos
            $festivos   = Festivos::where("STARTTIME", ">=", $fecha_inicio)->where("STARTTIME", "<=", $fecha_fin)->get();
            $arreglo_festivos = array();
            if(count($festivos) > 0){ $arreglo_festivos = $this->festivos($festivos); }

                 //obtener dias contingencia

            $contingencia  = Contingencia::where("STARTTIME", ">=", $fecha_inicio)->where("STARTTIME", "<=", $fecha_fin)->get();
            $arreglo_contingencia = array();
            if(count($contingencia) > 0)
            {
                $arreglo_contingencia = $this->contingencia($contingencia);
            }

            #Obtenemos salidas autorizadas
            $salidas   = SalidaAutorizada::where("STARTTIME", ">=", $fecha_inicio)->where("STARTTIME", "<=", $fecha_fin)->get();
            $arreglo_salidas = array();
            if(count($salidas) > 0){ $arreglo_salidas = $this->salidas($salidas); }
            
            #Obtenemos datos del empleado
         //  $fecha_inicio = new Carbon('2021-12-01T00:00:01');
           // $fecha_fin = new Carbon('2022-02-04T23:59:59');
            $empleados = Usuarios::with(['horarios.detalleHorario.reglaAsistencia', 'dias_otorgados.siglas', 
                'checadas'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
            }, 'horarios'=>function($query)use($fecha_inicio, $fecha_fin){
               // $query->where("STARTDATE", ">=", $fecha_inicio)->where("ENDDATE", "<=", '2022-12-31T23:59:59'); 
               $query->whereRaw("( ENDDATE >= '". $fecha_inicio."' and  STARTDATE <= '".$fecha_fin."')");
               
               //$query->whereRaw("( ENDDATE between '". $fecha_inicio."' and '" .$fecha_fin."' or STARTDATE between '". $fecha_inicio."' and '". $fecha_fin."')");
              
            }, 'omisiones'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("CHECKTIME", ">=", $fecha_inicio)->where("CHECKTIME", "<=", $fecha_fin);
            }, 'dias_otorgados'=>function($query)use($fecha_inicio, $fecha_fin){
                $query->where("ENDSPECDAY","<=", $fecha_fin)                   
                       ->where("STARTSPECDAY", ">=", $fecha_inicio)
                            ->orWhere("ENDSPECDAY", ">=", $fecha_inicio); 
              // $query->where("ENDSPECDAY","<=", $fecha_fin )->where("STARTSPECDAY", ">=", $fecha_inicio );
            }])
            ->Where('Badgenumber', $empleado)->first(); 
           
            $horarios_periodo = $this->ordernarHorarios($empleados->horarios);        
           //dd($horarios_periodo);  

            $indice_horario_seleccionado = 0;
            $dias_habiles = array();
            $jornada = "";
            $checadas_empleado  = $this->checadas_empleado($empleados->checadas);
            //print_r($checadas_empleado);
            //dd($horarios_periodo[0]['detalleHorario'][0]->SDAYS);
            $omisiones          = $this->omisiones($empleados->omisiones);
            $dias_otorgados     = $this->dias_otorgados($empleados->dias_otorgados);
          //  $diferencia_dias_nocturnos = $horarios_periodo[0]['detalleHorario'][0]->SDAYS-$horarios_periodo[0]['detalleHorario'][0]->EDAYS;

            
            #Empieza lo bueno, revision de checadas
            #por default ponemos los dias del pimer periodo, ya que sale de la consulta, pero validamos
            
            
            $contador_horario = 0;
            $bandera = 0;
            $diferencia_dias_sin_horario = 0;
            $dias_habiles = [];
            //for($dia_periodo = 1; $dia_periodo <= $dias_totales; $dia_periodo++)
           $fecha_x = new Carbon("2022-02-02");
           // $fecha_x ='2022-04-01T00:00:00';
          //  dd($fecha_x);
           // print_r("hol2");
         
            while($parametro_final->greaterThanOrEqualTo($parametro_inicial))
            {
                //var_dump($parametro_inicial);
                
                //echo $parametro_inicial."--";
                
                #Verificamos la vigencia de los horarios
                if($contador_horario == 0 && $diferencia_dias_sin_horario == 0)
                {
                    
                   //dd($parametro_inicial);
                 //   $indice_horario_seleccionado=3;
                
                   
                    $horario_evaluar = $this->validar_horario($horarios_periodo, $indice_horario_seleccionado, $parametro_inicial, $bandera );
                   // dd($horario_evaluar['dias_sin_horario']);
                    if($horario_evaluar['dias_sin_horario'] > 0)
                    {
                        $diferencia_dias_sin_horario = $horario_evaluar['dias_sin_horario'];
                        $contador_horario = 0;
                       // $bandera++;
                    }else{
                        $dias_habiles = $horario_evaluar['dias_habiles'];
                        $dias_habiles_siguiente = $horario_evaluar['dias_habiles_siguiente'];
                        $contador_horario = $horario_evaluar['dias_restantes'];                       
                        $indice_horario_seleccionado = $horario_evaluar['indice'];
                        $jornada = $horario_evaluar['horario'];
                        $bandera++;
                        
                    }
                  // 
                    //dd($horario_evaluar['dias_sin_horario']);
                  //  dd("bande".$bandera);
                  //dd($horario_evaluar);
                }
              //  dd($bandera);
               //unset($empleados);
             
                if($diferencia_dias_sin_horario == 0)
                {
                    
                    if(array_key_exists($parametro_inicial->dayOfWeekIso, $dias_habiles))
                    {
                        
                        if(!array_key_exists($parametro_inicial->format('Y-m-d'), $dias_otorgados))
                        {
                           // $diferencia_dias_nocturnos = $dia_ingreso->EDAYS - $dia_ingreso->SDAYS;
                          
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
                            
                            if(!array_key_exists($parametro_inicial->format('Y-m-d'), $arreglo_festivos) && !array_key_exists($parametro_inicial->format('Y-m-d'), $arreglo_contingencia))
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
                                        {    //dd($bandera);
                                           
                                            foreach($horario_evaluar['dias_habiles'] as $dias_index => $dia_ingreso){
                                                    
                                            }

                                            $diferencia_dias_nocturnos = $dia_ingreso->EDAYS - $dia_ingreso->SDAYS;
                                           
                                            if($parametro_inicial->equalTo($fecha_x) && $checada_entrada!=0)
                                            {
                                               
                                                //dd($indice_horario_seleccionado);
                                            } 
                                           
                                            if($diferencia_dias_nocturnos!=0){                                               
                                                $parametro_inicial->addDay();                                               
                                                //$dia_seleccionado = $dias_habiles[$parametro_inicial->dayOfWeekIso]->reglaAsistencia;
                                              
                                                if(array_key_exists($parametro_inicial->format('Y-m-d'), $checadas_empleado)){
                                                        $horario_evaluar = $this->validar_horario($horarios_periodo, $indice_horario_seleccionado, $parametro_inicial, $bandera );
                                                        $dias_habiles_siguiente = $horario_evaluar['dias_habiles_siguiente'];
                                                       // if($parametro_inicial->equalTo($fecha_x))   dd($dias_habiles_siguiente);
                                                        $dia_seleccionado = $dias_habiles_siguiente [$parametro_inicial->dayOfWeekIso]->reglaAsistencia;
                                                        foreach ($checadas_empleado[$parametro_inicial->format('Y-m-d')] as $index_checada => $dato_checada) {
                                                                 $checada = new Carbon($dato_checada->CHECKTIME);   
                                                                 $inicio_salida_x =  new Carbon($parametro_inicial->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime1, 11,8));
                                                                 $fin_salida_x =  new Carbon($parametro_inicial->format('Y-m-d')."T".substr($dia_seleccionado->CheckOutTime2, 11,8));
                                                                                    
                                                                /* $inicio_salida= $inicio_salida->addDays(1);
                                                                $fin_salida= $fin_salida->addDay(1);
                                                                */
                                                                if($checada->greaterThanOrEqualTo($inicio_salida_x) && $checada->lessThanOrEqualTo($fin_salida_x))
                                                                {
                                                                    $checada_salida = 1;
                                                                }  
                                                                if($parametro_inicial->equalTo($fecha_x))
                                                                {
                                                                 //dd($horario_evaluar);
                                                                  // dd("entrada:".$checada_entrada."salida: ".$checada_salida."checado:".$checada." inicio_salida: ".$inicio_salida_x ." fin_salida: ".$fin_salida_x);
                                                                  //  dd($checada_salida);
                                                                }   
                                                                 
                                                        }
                                                    
                                                    //("hol: ".$checada);
                                                       
                                                       
                                                 }
                                                 
                                              // $checada= $checada->addDay(1);
                                             
                                            }                                       
                                         
                
                                            if($checada->greaterThanOrEqualTo($inicio_salida) && $checada->lessThanOrEqualTo($fin_salida))
                                            {
                                                $checada_salida = 1;
                                            }

                                            if(array_key_exists($parametro_inicial->format('Y-m-d'), $dias_otorgados)){
                                                foreach ($dias_otorgados[$parametro_inicial->format('Y-m-d')] as $index_otorgado => $dato_otorgado) {
                                                    if($dato_otorgado->DATEID == 1 && $checada->lessThanOrEqualTo($inicio_salida) && $checada->greaterThanOrEqualTo($inicio_salida->subHours(2))){
                                                        $checada_salida = 3;
                                                    }
                                                } 
                                            }
                                            
                                            
                                           

                                          //  dd($checada_salida);
                                        }
                                    
                                    }

                                   

                                    if(array_key_exists($parametro_inicial->format('Y-m-d'), $omisiones))
                                    {
                                        foreach ($omisiones[$parametro_inicial->format('Y-m-d')] as $index_omision => $dato_omision) {
                                            if($dato_omision->CHECKTYPE == "I" || $dato_omision->CHECKTYPE == "E" || $dato_omision->CHECKTYPE == "R"){ $checada_entrada = 1; }
                                            if($dato_omision->CHECKTYPE == "O" || $dato_omision->CHECKTYPE == "S"){ $checada_salida = 1;  }
                                        }
                                    }
                                   // dd($checada_entrada. "sal".$checada_salida );
                                     
                                    if($checada_entrada == 1 and $checada_salida == 1){
                                        $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] = "" ; #Revisar
                                    }else if($checada_entrada == 2 and $checada_salida == 1){
                                        $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  "R/1"; #Revisa
                                    }else if(($checada_entrada == 1 or $checada_entrada == 2) and $checada_salida == 0)
                                    { 
                                        if(array_key_exists($parametro_inicial->format('Y-m-d'), $arreglo_salidas))
                                        {
                                            if($checada_entrada == 1 )
                                            {
                                                $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  ""; #Revisa
                                            }else if($checada_entrada == 2 ){
                                                $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  "R/1"; #Revisa
                                            }
                                        }else{
                                            
                                            $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  "F"; #Revisa
                                        } 
                                        
                                    }else if($checada_entrada == 0 and $checada_salida == 1)  
                                    {
                                        $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  "F"; 
                                    }else if($checada_entrada == 0 and $checada_salida == 0)  
                                    {
                                        $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  "F";
                                    }
                                    else if($checada_entrada == 1 and $checada_salida == 3)  
                                    {
                                        $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  $dias_otorgados[$parametro_inicial->format('Y-m-d')][0]['siglas']['ReportSymbol'];
                                    }
                                    
                                }else
                                {
                                    
                                    $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  "F";
                                }
                            }else{
                                $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  "S";
                            }
                        }else{
                            # En caso de ser dia otorgado se pone las letras
                            $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  $dias_otorgados[$parametro_inicial->format('Y-m-d')][0]['siglas']['ReportSymbol'];
                        }  
                        
                    }else
                    {   
                        # No hacemos nada en los dias no habiles
                        $resultado[$parametro_inicial->year][$parametro_inicial->month][$parametro_inicial->day] =  "";
                    }
                    $contador_horario--;
                }else{
                    $diferencia_dias_sin_horario--;
                }
                ############## Hasta aqui
                #reseteamos contadores
                
                $parametro_inicial->addDay();
              
                
                //echo $parametro_inicial." -- ";
            }
            
        /*}*/
        return array("datos" => $resultado, "horario" => $jornada);
        unset($resultado);
    }

    function ordernarHorarios($horarios)
    {
        $index_principal    =   0;
        $numero_registros = count($horarios);
       // dd($numero_registros);
        while($index_principal < $numero_registros)
        {
            $index_pivote       =   $index_principal + 1;
            //dd($index_pivote);
            while($index_pivote < $numero_registros)
            {
                $fecha_indice = new Carbon($horarios[$index_principal]->STARTDATE);
                $fecha_pivote = new Carbon($horarios[$index_pivote]->STARTDATE);
                //dd( $fecha_indice);
                if($fecha_indice->greaterThan($fecha_pivote))
                {
                    $puente = $horarios[$index_pivote];
                    $horarios[$index_pivote] = $horarios[$index_principal];
                    $horarios[$index_principal] = $puente;
                    
                }
              
                $index_pivote++;
            }   
            $index_principal++;
           // dd($puente);
        }
        //dd($fecha_pivote);
        return $horarios;
    }

    function validar_horario($horarios, $indice, $fecha_validacion, $bandera)
    {
        $numero_horarios = count($horarios);
        //dd($horarios);
        $dias_habiles = array();
        $diferencia_dias = 0;
        $diferencia_dias_sin_horario = 0;
        $horario = "";
        $actualizacion = false;

        //dd($horarios);
        $fecha_validacion_x = new Carbon("2022-10-11");
        $horario = "";
        foreach ($horarios as $key => $value) {
          
          $indice_pivote=$key;
            $inicio_x=new Carbon($horarios[$key]->STARTDATE);
            $fin_x= new Carbon(substr($horarios[$key]->ENDDATE,0,10)."T23:59:59");
          while($inicio_x<=$fecha_validacion && $fin_x>=$fecha_validacion){
            $fecha_inicio_periodo =  new Carbon($horarios[$indice_pivote]->STARTDATE);
            $fecha_fin_periodo =  new Carbon(substr($horarios[$indice_pivote]->ENDDATE, 0,10)."T23:59:59");   
            $dias_habiles = $this->dias_horario($horarios[$indice_pivote]->detalleHorario);
            $dias_habiles_siguiente = $this->dias_horario_siguiente($horarios[$indice_pivote]->detalleHorario);
            //
            foreach ($dias_habiles as $key => $value) {
                
                $horario = "De ".substr($value->STARTTIME, 11,5)." a ".substr($value->ENDTIME,11,5);
              // dd($horario);
            }
           // dd($horario);
            return array("validacion"=>$fecha_validacion,"inicio"=>$fecha_inicio_periodo,"fin"=>$fecha_fin_periodo,"dias_restantes"=> $diferencia_dias, "indice" => $indice, "dias_habiles" => $dias_habiles,"dias_habiles_siguiente" => $dias_habiles_siguiente, "horario"=>$horario, "actualizacion" => $actualizacion, "dias_sin_horario" => $diferencia_dias_sin_horario);
            
            }
        }
       
    }
   
     function dias_horario($arreglo)
    {
        $arreglo_nuevo = array();

        foreach ($arreglo as $key => $value) {
            $arreglo_nuevo[$value->SDAYS] = $value;
        }
        //dd($arreglo_nuevo);
        return $arreglo_nuevo;
    } 

    function dias_horario_siguiente($arreglo)
    {
        $arreglo_nuevo = array();

        foreach ($arreglo as $key => $value) {
            $arreglo_nuevo[$value->EDAYS] = $value;
        }
        //dd($arreglo_nuevo);
        return $arreglo_nuevo;
    } 

    function checadas_empleado($arreglo)
    {
        $arreglo_checadas = array();
        foreach ($arreglo as $key => $value) {
            $arreglo_checadas[substr($value->CHECKTIME, 0,10)][] = $value;
        }
       // dd($arreglo_checadas);
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
        //dd($arreglo_salidas);
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
        unset($arreglo_dias);
        $arreglo_dias = array();     

        foreach ($arreglo as $key => $value) {
           //  $arreglo_dias[substr($value->STARTSPECDAY, 0,10)][] = $value;
           
            $inicio = new Carbon($value->STARTSPECDAY);
             $fin = new Carbon($value->ENDSPECDAY);
             $diff = $inicio->diffInDays($fin);            
             $arreglo_dias[substr($inicio, 0,10)][] = $value;
             for ($i=0; $i < $diff; $i++) { 
                $arreglo_dias[substr($inicio->addDays(), 0,10)][] = $value;
                
             } 

         }
       //dd($arreglo_dias);
         return $arreglo_dias;
       
    }

}  