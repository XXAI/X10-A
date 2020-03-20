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
            margin: 100px 0px 120px 10px;
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
                            <b>DESCUENTOS POR FALTAS INJUSTIFICADAS<br>
                            8001<br>
                            UNIDAD RESPONSABLE:INSTITUTO DE SALUD DEL ESTADO DE CHIAPAS<br> OFICINA CENTRAL</b>
                            <br><br>
                            <b>AGRADECE A USTED SE SIRVA APLICAR LOS DESCUENTOS POR INASISTENCIAS DEL PERSONAL QUE ACONTINUACION SE DETALLA.</b>
                            <!--<b style='font-size:14pt'>PERSONAL: {{ strtoupper($empleados['tipo_trabajador']['DEPTNAME']) }}</b>
                            </div>-->
                           
                        </td>
                        <td width="100px">
                            <img src='images/chiapas.png' width="100px">
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            <table width="100%">
                <tbody>
                    
                    <tr>
                        <td style='font-size:9pt; width: 1000px;' colspan='2'>
                        <b style='font-size:14pt'>PERSONAL: {{ strtoupper($empleados['tipo_trabajador']['DEPTNAME']) }}</b>
                           
                        </td>
                        <td  style='font-size:9pt;text-align:left'>
                            NO. LOTE:
                            @switch($empleados['tipo_trabajador']['DEPTID'])
                                @case(6)
                                @case(11)
                                    GOV0006
                                @break
                                @case(13)
                                    CAR0006
                                @break
                                @case(12)
                                    PEV0025
                                @break
                            @endswitch
                            <br>
                            QNA. APLICACIÓN:<br>
                            MES: {{ $empleados['nombre_mes'] }}<br>
                            <table width="100%"><tbody><tr><td>QUINCENA:</td><td style="border: 1px solid #000;text-align:center"></td><td></td><td style="border: 1px solid #000; text-align:center" width="50px">x</td></tr></tbody></table>
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
    <table width="100%" cellspacing="0" class="fuente">
        <thead class='cabecera'>
            <tr>
                <th rowspan="2" class='encabezados' width="100px"># DOCUMENTO</th>
                <th rowspan="2" class='encabezados' width="100px">RFC</th>
                <th rowspan="2" class='encabezados' width="70px">CODIGO</th>
                <th rowspan="2" class='encabezados' width="100px">JORNADA</th>
                <th rowspan="2" class='encabezados' width="100px">NO. PUESTO</th>
                <th rowspan="2" class='encabezados'>NOMBRE DEL EMPLEADO</th>
                <th colspan="6" class='encabezados' >DÍAS A DESCONTAR</th>
                
            </tr>   
            <tr>
                <th width="80px">No. DÍAS</th>
                <tH width="80px">LETRA</th>
                <tH width="80px">DÍA</th>
                <tH width="80px">DÍA</th>
                <tH width="80px">DÍA</th>
                <tH width="80px">DÍA</th>
                
            </tr> 
        </thead>
        <tbody class='datos'>
            <?php $numero = 0; ?>
            @foreach ($empleados['datos'] as $index_empleado => $empleado )
                <tr>
                    <td class='linea'>{{ str_pad(($numero+1), 7, "1100000", STR_PAD_LEFT) }} </td>
                    <td class='linea'>{{ $empleado->TITLE}} </td>
                    <td class='linea'>{{ $empleado->PAGER }} </td>
                    <td class='linea'> </td>
                    <td class='linea'> </td>
                    <td class='linea'>{{ $empleado->Name}} </td>
                    
                    
                    <!--<td class='linea centrado'>{{ $empleado->resumen['ASISTENCIA'] }}</td>-->
                    <td class='linea centrado'>{{ $empleado->resumen['FALTAS_TOTALES'] }}</td>
                    <td class='linea centrado'>{{ $empleado->resumen['FALTAS_TOTALES'] }}</td>
                    <td class='linea centrado'>{{ $empleado->resumen['FALTAS_TOTALES'] }}</td>
                    <td class='linea centrado'>{{ $empleado->resumen['FALTAS_TOTALES'] }}</td>
                    <td class='linea centrado'>{{ $empleado->resumen['FALTAS_TOTALES'] }}</td>
                    <td class='linea centrado'>{{ $empleado->resumen['FALTAS_TOTALES'] }}</td>
                    
                    <!--<td class='linea centrado'>{{ $empleado->resumen['RETARDOS_1'] }}</td>
                    <td class='linea centrado'>{{ $empleado->resumen['RETARDOS_2'] }}</td>-->
                    
                    
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