<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href=".././libs/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href=".././libs/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href=".././css/hover-table.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Asistencia</title>
    <style>
        .cabecera
        {
            font-size: 10pt;
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
            font-size: 7pt;
        }
        .parrafo
        {
            font-family: Helvetica;
            font-size: 7pt;
            text-align: justify;
        }
        .encabezado
        {
            font-family: Helvetica;
            font-size: 7pt;
            text-align: left;
        }
        .fecha
        {
            font-family: Helvetica;
            font-size: 7pt;
            text-align: right;
        }
        .datos
        {
            font-size: 7pt;
        }

        .firmantes
        {
            font-size: 7pt;
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
            font-size: 7pt;
            text-align: center;
        }

        body{
            margin: 10px 0px 140px 5px;
        }

        header {
            position: fixed;
            width:100%;
            top: -80px;
            left: 0px;
            right: 0px;
            height: 50px;

            
        }

        .footer {
            position: fixed; 
            bottom: 90px; 
            left: 0px; 
            right: 0px;
            height: 50px; 
        }

        .marco {
            border: black 5px double;
            border-radius: 5px;
            padding: 2px 5px;
        }
        .datos_api { 
            text-decoration-line: underline;
            text-decoration-style: wavy;
            text-decoration-color: black;
        }

        .tabla_checadas {
            font-size: 7pt;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .tabla_checadas td, .tabla_checadas th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .tabla_checadas tr:nth-child(even){background-color: #f2f2f2;}

        .tabla_checadas tr:hover {background-color: #ddd;}

        .tabla_checadas th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #b8b8b8;
            color: black;
        }
        .espacio { 
            white-space: normal;
        }
        .text-justify {
            text-align: justify;
        }

        .text-center {
            text-align: center;
        }
        .mayuscula{
            text-transform: uppercase;
        }

    </style>
</head>
<?php

    $datos_asistencia     = $asistencia['data'];   
    $datos_empleado       = $asistencia['validacion'];   
    $fecha_inicio         = $asistencia['fecha_inicial'];   
    $fecha_fin            = $asistencia['fecha_final'];  
    $dias           = ["", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO", "DOMINGO"];
    $longitud= key($datos_asistencia);
    $inluye_leyenda = $leyenda;
    $fecha_actual = $hoy;
  // print_r($longitud);exit;
?>
<body>
    <header>
        <div class="fuente">
            
            <table width="100%">
                <tbody>
                    <tr>
                        <td width="100px">
                            <img src='images/salud.png' width="100px">
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
                            <img src='images/chiapas.png' width="100px">
                        </td>
                    </tr>
                   <!--<tr>
                    <td colspan='2' class='datos'>UNIDAD EXPEDIDORA: OFICINA CENTRAL</td>
                    <td colspan='2' class='datos'>TIPO DE TRABAJADOR: </td>
                   </tr>-->
                </tbody>
            </table>
        </div>
    </header>
    <br>
    <br>
    
<table width="100%"  cellspacing="0" class="fuente_datos">
    <tr>
        <td>NOMBRE:</td><td colspan='3'> <b><?php echo $datos_empleado->Name ?></b></td>
        <td>RFC:</td><td colspan='3'><b><?php echo $datos_empleado->TITLE ?></b></td>
        <td>ID:</td><td colspan='2'><b><?php echo $datos_empleado->Badgenumber ?></b></td>
    </tr>
    <tr>
        <td>TIPO:</td><td><b><?php echo $datos_empleado->street ?></b></td>
    </tr>
    <tr>
        <td>HORA DE ENTRADA:</td><td  colspan='2'><b><?php echo substr($datos_asistencia[$longitud]['jorini'], 11, 5) ?></b></td>
        <td colspan='2'>HORA DE SALIDA:</td><td  colspan='2'><b><?php echo substr($datos_asistencia[$longitud]['jorfin'], 11, 5) ?></b></td>
    </tr>

    <tr>
        <td>FECHA INICIO(DESDE):</td><td colspan='2'><b><?php echo $fecha_inicio ?></b></td>
        <td colspan='2'>FECHA FINAL(HASTA):</td><td colspan='2'><b><?php echo $fecha_fin ?></b></td>
    </tr>
    <tr>
        
    </tr>


</table>
<br>
<section class="card">
    <br>

    <table class="tabla_checadas">
        <thead>
            <tr>
                <th>Día</th>
                <th>Fecha</th>
                <th>Hora Entrada</th>
                <th>Hora Salida</th>
            </tr>
        </thead>
        @foreach($datos_asistencia as $key => $value)
            <tbody>
                <tr>
                    <td>{{ $dias[$datos_asistencia[$key]['numero_dia']]  }}</td>
                    <td>{{ $datos_asistencia[$key]['fecha'] }}</td>
                    <td>
                            @if(strpos($datos_asistencia[$key]['checado_entrada'],'Retardo') !== false) 
                                {{substr($datos_asistencia[$key]['checado_entrada'],0,5)}}

                            
                            @else
                                {{$datos_asistencia[$key]['checado_entrada']}}
                             
                            @endif    
                          

                          
                    </td>
                    <td>{{ $datos_asistencia[$key]['checado_salida']  }}</td>
                </tr>
            </tbody>
        @endforeach
    </table>
    <div class="espacio"></div>
</section>
<br>
@if($inluye_leyenda == 1)
<p class="text-justify fuente_datos">
    <strong>C E R T I F I C A C I Ó N &nbsp; D E &nbsp; D O C U M E N T O:</strong> INSTITUTO DE SALUD, TUXTLA GUTIÉRREZ CHIAPAS, AL DÍA <strong class="mayuscula">{{ $fecha_actual }}</strong>, LA SUSCRITA L.A.E. ANITA DEL CARMEN GARCÍA LEÓN, SUBDIRECTORA DE RECURSOS HUMANOS
    <br><a class="text-center fuente_datos">------------------------------------------------------------------------------------------------<strong>H A C E   C O N S T A R</strong>-----------------------------------------------------------------------------------------------</a><br>
    <a class="text-justify fuente_datos">QUE LA PRESENTE COPIA FOTOSTÁTICA QUE CONSTAN DE UNA (01) FOJA UTIL, ES COPIA FIEL Y EXACTA DEL DOCUMENTO QUE SE TIENE A LA VISTA, QUE OBRA Y FORMA PARTE INTEGRAL DEL SISTEMA DE REGISTRO  ELECTRONICO DE ASISTENCIA DEL C. JULIO PAREDES SOLIS  CON ID: 1167 DE LA SUBDIRECCIÓN DE RECURSOS HUMANOS, DEPENDIENTE DE LA DIRECCIÓN DE ADMINISTRACIÓN Y FINANZAS DE ESTE INSTITUTO DE SALUD, LA CUAL SE COMPULSA Y CERTIFICA PARA TODOS LOS EFECTOS LEGALES A QUE HAYA LUGAR; MISMA QUE FIRMO Y SELLO CON FUNDAMENTO EN EL ARTÍCULO 40 FRACCION VII DEL REGLAMENTO INTERIOR DEL INSTITUTO DE SALUD.</a><br>
    <br><a class="text-center fuente_datos">--------------------------------------------------------------------------------------------------------<strong>C O N S T E</strong>-------------------------------------------------------------------------------------------------------</a><br>
</p>
<br>
<br>
<p class="centrado datos fuente_datos">
    <strong>LAE. ANITA DEL CARMEN GARCÍA LEÓN
    <br>
    SUBDIRECTORA DE RECURSOS HUMANOS.</strong>
</p>
<br>
<br>
<p class="text-center fuente_datos">
    <strong>REVISO:<br>ING. GABRIEL DE LA GUARDIA NAGANO.<br>
    -Jefe del Departamento de Operación y Sistematización de Nomina-</strong>
</p>
@else
   <div>
   </div>
@endif

</body>
</html>