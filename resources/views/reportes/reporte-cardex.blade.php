<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Cardex</title>
    <style>
        .cabecera
        {
            font-size: 12pt;
            background-color:#CFCFCF;
            border: 0px;
            
        }
        .fuente
        {
            font-family: Helvetica;
        }
        .fuente_datos
        {
            font-family: Helvetica;
            font-size: 9pt;
        }
        .datos
        {
            font-size: 8pt;
        }
        .firmantes
        {
            font-size: 9pt;
            font-family: Helvetica;
        }
        .linea
        {
            border-bottom: 1px solid #cfcfcf;
            border-right: 1px solid #cfcfcf;
        }
        .centrado
        {
            text-align: center; 
        }
        .falta
        {
            background-color: #EFEFEF;
        }
        .tamano
        {
            padding:0px 20px 0px 20px;
        }
        @page {
            margin: 100px 35px 0px 50px;
        }
        .encabezados
        {
            font-size: 9pt;
            text-align: center;
        }
        body{
            margin: 100px 0px 30px 5px;
            /*border:1px solid #000;*/
        }
        header {
            position: fixed;
            width:100%;
            top: -80px;
            left: 0px;
            right: 0px;
            height: 50px;
            
        }
        /*.footer {
            position: fixed; 
            bottom: 90px; 
            left: 0px; 
            right: 0px;
            height: 50px; 
         
        }*/
        .contenido
        {
            height:410px;
        }
        .page_break { page-break-before: always; }
    </style>
</head>
<?php

$objeto = $empleados;
print_r($objeto);
/* $clave = $objeto['ur'].$objeto['gf'].$objeto['fn'].$objeto['sf'].$objeto['pg'].$objeto['al'].$objeto['pp'].$objeto['partida'].$objeto['codigo'].$objeto['numpto']; */
$clave = $objeto->Sirh_Empleados['clave'];
//print_r($objeto->Sirh_Empleados['rfc']);
$catalogo_trabajador = ['','BASE','CONTRATO',"REGULARIZADO", 'FORMALIZADO', 'HOMOLOGADO', 'UNEMES CAPACIT', 'ESTATAL', 'PENDIENTE'];
$meses = ['', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
//print_r(($objeto['asistencia']));
//$periodo =[''];

?>
<body>
    <header>
        <div class="fuente">
            
            <table width="100%">
                <tbody>
                    <tr>
                        <td width="100px">
                            <img src='https://sistematizacion.saludchiapas.gob.mx/images/salud.png' width="100px">
                        </td>
                        <td>
                            <div class="centrado datos">
                           SECRETARÍA DE SALUD<BR>
                           INSTITUTO DE SALUD DEL ESTADO DE CHIAPAS<BR>
                            DIRECCIÓN DE ADMINISTRACIÓN Y FINANZAS<BR>
                            DEPARTAMENTO DE OPERACIÓN Y SISTEMATIZACIÓN DE NÓMINAS<BR>
                            CONTROL DE ASISTENCIA<BR>
                           
                            </div>
                           
                        </td>
                        
                        <td width="100px">
                            <img src='https://sistematizacion.saludchiapas.gob.mx/images/chiapas.png' width="100px">
                        </td>
                    </tr>
                  
                </tbody>
            </table>
        </div>
        <table width="100%"  cellspacing="0" class="fuente_datos">
        <tr>
            <td>NOMBRE</td><td> <b><?php echo $objeto->Sirh_Empleados['nombre'] ?></b></td>
            <td>DEPARTAMENTO</td><td><b><?php echo $objeto->Sirh_Empleados['cr_fisico'] ?></b></td>
        </tr>
        <tr>
            <td>FUNCIÓN</td><td> <b><?php echo $objeto->Sirh_Empleados['puesto'] ?></b></td>
            <td>RFC</td><td> <b><?php echo $objeto->Sirh_Empleados['rfc'] ?></b></td>
        </tr>
        <tr>
            <td>CLAVE</td><td> <b><?php echo $clave; ?></b></td>
            <td>FECHA INGRESO</td><td> <b><?php echo $objeto->Sirh_Empleados['fissa'] ?></b></td>
        </tr>
        <tr>
            <td>ADSCRIPCIÓN</td><td colspan='3'><b><?php echo $objeto->Sirh_Empleados['clues_adscripcion'] ?></b></td>
        </tr>
        <tr>
            <td>HORARIO <b><?php echo $objeto['jornada'];
            
            if($objeto['rfc']=="VAEA7603164Z2")
                 echo "   HORARIO DE GUARDERIA AL FINAL DE LA JORNADA"; 
         
            
            ?></td><td> 
                
             </td>
            <td>TIPO TRABAJADOR</td><td> <b><?php  echo $catalogo_trabajador[$objeto->Sirh_Empleados['tipo_trabajador_id']] ?>
                @foreach ($objeto['asistencia'] as $index => $anio)
                {{ '('. $periodo = $index.' ' .')'}}                 
                @endforeach
            </b></td>
        </tr>
    </table> 
    </header>  
    
    <br>
   
    
    <div class="contenido">
        <table width="100%"  cellspacing="0" class="fuente" style='margin-top:10px'>
            <thead class='cabecera'>
                <tr>
                    <th  class='encabezados' width="50px">AÑO</th>   
                    <th  class='encabezados' width="120px">MES</th>   
                    <th  class='encabezados'>1</th>   
                    <th  class='encabezados'>2</th>   
                    <th  class='encabezados'>3</th>   
                    <th  class='encabezados'>4</th>   
                    <th  class='encabezados'>5</th>   
                    <th  class='encabezados'>6</th>   
                    <th  class='encabezados'>7</th>   
                    <th  class='encabezados'>8</th>   
                    <th  class='encabezados'>9</th>   
                    <th  class='encabezados'>10</th>   
                    <th  class='encabezados'>11</th>   
                    <th  class='encabezados'>12</th>   
                    <th  class='encabezados'>13</th>   
                    <th  class='encabezados'>14</th>   
                    <th  class='encabezados'>15</th>   
                    <th  class='encabezados'>16</th>   
                    <th  class='encabezados'>17</th>   
                    <th  class='encabezados'>18</th>   
                    <th  class='encabezados'>19</th>   
                    <th  class='encabezados'>20</th>   
                    <th  class='encabezados'>21</th>   
                    <th  class='encabezados'>22</th>   
                    <th  class='encabezados'>23</th>   
                    <th  class='encabezados'>24</th>   
                    <th  class='encabezados'>25</th>   
                    <th  class='encabezados'>26</th>   
                    <th  class='encabezados'>27</th>   
                    <th  class='encabezados'>28</th>   
                    <th  class='encabezados'>29</th>   
                    <th  class='encabezados'>30</th>   
                    <th  class='encabezados'>31</th>   
                </tr>  
                
            </thead>
            <tbody class='datos'>
            <?php $numero = 0; ?>
                @foreach ($objeto['asistencia'] as $index_anio => $anio )
                    @foreach ($anio as $index_mes => $mes )
                        <tr>
                            <td style='border: 1px solid #efefef; height:30px; text-align:center'>{{ $index_anio }}</td>
                            <td style='border: 1px solid #efefef; height:30px; text-align:center'>{{ $meses[$index_mes] }}</td>
                            @for($i = 1; $i <= 31; $i++)
                                @if(array_key_exists($i, $mes))
                                    <td style='border: 1px solid #efefef; height:30px; text-align:center'>{{ $mes[$i] }}</td>
                                @else    
                                    <td style='border: 1px solid #efefef; text-align:center'>-</td>
                                @endif    
                            @endfor
                        </tr>    
                    @endforeach    
                @endforeach
                
            </tbody>
        </table>
    </div>         
    <table width="100%" class='firmantes' style='margin-top:7px'>
        <thead>
            <tr>
                <th>CLAVES</th>
            </tr>    
        </thead>
        <tbody>
            <tr>
                <td>F: FALTAS</td>
                <td>O: ONOMASTICO</td>
                <td>P/E: PERMISO ECONÓMICO</td>
                <td>P/A: PAGO DE GUARDIA</td>
                <td>COV: COVID</td>
            </tr>
            <tr>
                <td>P/S: LICENCIA S/GOSE DE SUELDO</td>
                <td>V: VACACIONES</td>
                <td>O/E: OMISIÓN ENTRADA</td>
                <td>M: MEMORANDUM</td>
                <td>R/D: REPOSICION</td>
            </tr>
            <tr>
                <td>P/G: LICENCIA C/GOSE DE SUELDO</td>
                <td>E: LICENCIA MÉDICA</td>
                <td>O/S: OMISIÓN SALIDA</td>
                <td>MR: MEDIANO RIESGO</td>
                
            </tr>
            <tr>
                <td>R/1:RETARDO MENOR</td>
                <td>L/S: LICENCIA SINDICAL</td>
                <td>S: SUSPENSIÓN</td>
                <td>AR: ALTO RIESGO</td>
            </tr>
            <tr>
                <td>R/2:RETARDO MAYOR</td>
                <td>C: COMISIÓN</td>
                <td>P/H: PERMISO POR HORA</td>
                <td>BR: BAJO RIESGO</td>
            </tr>
            
        </tbody>    
    </table> 
    <div class="page_break"></div>
    <br>
 <table width="100%"  class="fuente">
    <tr><td style='text-align:center'>OBSERVACIONES</td></tr>
    <tr><td style='border-bottom: 1px solid #000;height: 25px;'>&nbsp;</td></tr>
    <tr><td style='border-bottom: 1px solid #000;height: 25px;'>&nbsp;</td></tr>
    <tr><td style='border-bottom: 1px solid #000;height: 25px;'>&nbsp;</td></tr>
    <tr><td style='border-bottom: 1px solid #000;height: 25px;'>&nbsp;</td></tr>
    <tr><td style='border-bottom: 1px solid #000;height: 25px;'>&nbsp;</td></tr>
    <tr><td style='border-bottom: 1px solid #000;height: 25px;'>&nbsp;</td></tr>
    <tr><td style='border-bottom: 1px solid #000;height: 25px;'>&nbsp;</td></tr>
    <tr><td style='border-bottom: 1px solid #000;height: 25px;'>&nbsp;</td></tr>
    <tr><td style='border-bottom: 1px solid #000;height: 25px;'>&nbsp;</td></tr>
 </table>
    
   
</body>  
</html>