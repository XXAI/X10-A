<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Mensual</title>
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
            margin: 100px 25px 0px 25px;
        
        }

        .encabezados
        {
            font-size: 9pt;
            text-align: center;
        }

        body{
            margin: 30px 0px 120px 10px;
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
            bottom: 70px; 
            left: 0px; 
            right: 0px;
            height: 50px; 

            
        }
    </style>
</head>

<?php //print_r($empleados['datos']); ?>
<body>
    <header>
        <div class="fuente">
            <!--<div class="centrado datos">DESCUENTOS POR FALTAS INJUSTIFICADAS<br>8001</div>-->
            <table width="100%">
                <tbody>
                    <tr>
                        <td width="100px">
                            <img src='images/salud.png' width="100px">
                        </td>
                        <td>
                            <div class="centrado datos">
                            DESCUENTOS POR FALTAS INJUSTIFICADAS<br>
                            8001<br>
                            <b style='font-size:14pt'>PERSONAL: {{ strtoupper($empleados['tipo_trabajador']['DEPTNAME']) }}</b>
                            </div>
                           
                        </td>
                        <td width="100px">
                            <img src='images/chiapas.png' width="100px">
                        </td>
                    </tr>
                    <tr>
                        <td style='font-size:9pt' colspan='2'>
                            NO. LOTE:<br>
                            QNA. APLICACIÓN:<br>
                            
                        </td>
                        <td  style='font-size:9pt'>
                            MES: {{ $empleados['nombre_mes'] }}<br>
                            
                            AÑO: {{ $empleados['filtros']['anio'] }}<br>
                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </header>   
    <table width="100%" class='firmantes footer'>
        <tr>
            <td class="centrado tamano">
            DIRECTOR(A) DEL HOSPITAL O JEFE JURISDICCIIONAL
            <br><br><br>
            <HR>
            NOMBRE Y FIRMA
            </td>
            <td class="centrado tamano">
            SUBDIRECTOR DE RECURSOS HUMANOS
            <br><br><br>
            <HR>
            NOMBRE Y FIRMA
            </td>
            <td class="centrado tamano">
            DIRECTOR(A) DE ADMINISTRACIÓN Y FINANZAS
            <br><br><br>
            <HR>
            NOMBRE Y FIRMA
            </td>
        </tr>
    </table> 
    <table width="100%"  cellspacing="0" class="fuente">
        <thead class='cabecera'>
            <tr>
                <th rowspan="2" class='encabezados'># DOCUMENTO</th>
                <th rowspan="2" class='encabezados' width="300px">NOMBRE DEL EMPLEADO</th>
                <th rowspan="2" class='encabezados' width="90px">RFC</th>
                <th rowspan="2" class='encabezados' width="60px">CODIGO</th>
                
                
                <th colspan='2' class='encabezados'>RESUMEN</th>
                <th colspan='31' class='encabezados' rowspan="2">ASISTENCIA</th>
            </tr>   
            <tr>
                <!--<th class='encabezados'>A</th>-->
                <th class='encabezados'>F</th>
                <th class='encabezados'>R1</th>
                <!--<th class='encabezados'>RQ1</th>
                <th class='encabezados'>RQ2</th>-->
            </tr> 
        </thead>
        <tbody class='datos'>
            <?php $numero = 0; ?>
            @foreach ($empleados['datos'] as $index_empleado => $empleado )
                <tr>
                    <td class='linea'>{{ str_pad(($numero+1), 7, "1100000", STR_PAD_LEFT) }} </td>
                    <td class='linea'>{{ $empleado->Name}} </td>
                    <td class='linea'>{{ $empleado->TITLE}} </td>
                    <td class='linea'>{{ $empleado->PAGER }} </td>
                    <!--<td class='linea centrado'>{{ $empleado->resumen['ASISTENCIA'] }}</td>-->
                    <td class='linea centrado'>{{ $empleado->resumen['FALTAS'] }}</td>
                    <td class='linea centrado'>{{ $empleado->resumen['RETARDOS'] }}</td>
                    <!--<td class='linea centrado'>{{ $empleado->resumen['RETARDOS_1'] }}</td>
                    <td class='linea centrado'>{{ $empleado->resumen['RETARDOS_2'] }}</td>-->
                    @foreach ($empleado->asistencia as $indice => $asistencias )
                        @if ($asistencias == 'F' || $asistencias == 'FE' || $asistencias == 'FS' )
                            <td class='linea centrado' style='background-color:#993e3e; color:white;'>{{ $indice }}<br> {{ $asistencias }}</td>  
                        @elseif ($asistencias == 'R1')
                            <td class='linea centrado' style='background-color:#6a6969; color:white;'>{{ $indice }}<br> {{ $asistencias }}</td>  
                        @elseif ($asistencias == 'N/A')
                            <td class='linea centrado' style='background-color:#EFEFEF;'>{{ $indice }}<br></td>  
                        @else    
                            <td class='linea centrado'>{{ $indice }}<br> {{ $asistencias }}</td>  
                        @endif
                        
                    @endforeach
                </tr>    
                <?php $numero++; ?>
            @endforeach
        </tbody>
    </table>
    
     

    <script type="text/php">
    if (isset($pdf))
    {
        $fecha = date("Y-m-d H:i:s");
        $pdf->page_text(700, 590, " Tuxtla Gutiérrez, Chiapas, $fecha - Página {PAGE_NUM} de {PAGE_COUNT}", Null, 9, array(0, 0, 0));
    }
    </script>       
</body>
</html>