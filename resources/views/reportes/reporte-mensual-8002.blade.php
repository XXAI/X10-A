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
            margin: 100px 0px 140px 10px;
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
    </style>
</head>
<?php $letras = array('', "UNO", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS"); ?>
<?php 
            
            $relleno = 1100000;
            $tipotra="";
            //echo $empleados['tipo_trabajador']['id'];
            $documento = $empleados['filtros']['documento'];
            switch ($empleados['tipo_trabajador']['id']) {
                case 1 :
                $relleno = $relleno+$documento;  
                $tipotra="GOV0018"; 
                break;
                case 2 :
                $relleno = $relleno+$documento; 
                $tipotra="GOV0018"; 
                break;
               case 3:
                   $relleno = "3300000";
                   $relleno = $relleno+$documento; 
                   $tipotra="PEV0008";
               break;

               case 4 :
               $relleno = $relleno+$documento;  
                $tipotra="CAR0018"; 
                break;
                case 5 :
               $relleno = $relleno+$documento;  
                $tipotra="CON0018"; 
                break;
               default:
                    
                
                
            }
        ?>

<body>
    <header>
        <div class="fuente">
            <!--<div class="centrado datos">DESCUENTOS POR FALTAS INJUSTIFICADAS<br>8001</div>-->
            <table width="100%">
                <tbody>
                    <tr>
                        <td width="100px">
                            <img src='http://sistematizacion.saludchiapas.gob.mx/images/salud.png' width="100px">
                        </td>
                        <td>
                            <div class="centrado datos">
                            <b>DESCUENTOS POR FALTAS INJUSTIFICADAS<br>
                            8002<br>
                            UNIDAD RESPONSABLE:INSTITUTO DE SALUD DEL ESTADO DE CHIAPAS<br> OFICINA CENTRAL</b>
                            <br><br>
                            <b>AGRADECE A USTED SE SIRVA APLICAR LOS DESCUENTOS POR INASISTENCIAS DEL PERSONAL QUE ACONTINUACION SE DETALLA.</b>
                            <!--<b style='font-size:14pt'>PERSONAL: {{ strtoupper($empleados['tipo_trabajador']['DEPTNAME']) }}</b>
                            </div>-->
                           
                        </td>
                        <td width="100px">
                            <img src='http://sistematizacion.saludchiapas.gob.mx/images/chiapas.png' width="100px">
                        </td>
                    </tr>
                    
                </tbody>

            </table>
            <table width="100%">
                <tbody>                   
                
                    <tr>
                        <td style='font-size:9pt;' colspan='2'>
                        <b style='font-size:14pt'>PERSONAL: {{ strtoupper($empleados['tipo_trabajador']['descripcion']) }}</b>
                           
                        </td>
                        <td  style='font-size:9pt;text-align:left;' width="100px" >
                        
                            NO. LOTE: {{$tipotra}}
                            
                            <br>
                            QNA. APLICACIÓN: 21/2021<br>
                            MES: {{ $empleados['nombre_mes'] }} AÑO: {{ $empleados['filtros']['anio'] }}<br>
                            {{-- <table width="100%" cellspacing="0" cellspadding="0"><tbody><tr><td>QUINCENA:</td><td style="border: 1px solid #000;text-align:center">@if($empleados['filtros']['quincena'] == 1) X @else    @endif</td><td></td><td style="border: 1px solid #000; text-align:center" width="50px">@if($empleados['filtros']['quincena'] == 2) X @else  @endif</td></tr></tbody></table> --}}
                            
                        
                        </td>
                        
                            
                    </tr>
                </tbody>
            </table>
        </div>
    </header>   
    <table width="100%" class='firmantes footer'>
        <tr>
        <td class="centrado tamano">
            JEFE DEL DEPARTAMENTO DE OPERACIÓN <br>Y SISTEMATIZACIÓN DE NÓMINA
            <br><br><br>
            ING. GABRIEL DE LA GUARDIA NAGANO
            <HR>
            NOMBRE Y FIRMA
            </td>
            <td class="centrado tamano">
            <br>SUBDIRECTORA DE RECURSOS HUMANOS
            <br><br><br>
            L.A.E. ANITA DEL CARMEN GARCÍA LEÓN
            <HR>
            NOMBRE Y FIRMA
            </td>
            <td class="centrado tamano">
            <br>DIRECTOR DE ADMINISTRACIÓN Y FINANZAS
            <br><br><br>
            L.A. SAMUEL SILVAN OLAN
            <HR>
            NOMBRE Y FIRMA
            </td>
        </tr>
    </table> 
    <table width="100%" cellspacing="0" class="fuente">
        <thead class='cabecera'>
            <tr>
                <th rowspan="2" class='encabezados'># DOCUMENTO</th>
                <th rowspan="2" class='encabezados'>RFC</th>
                <th rowspan="2" class='encabezados'>CODIGO</th>
                <th rowspan="2" class='encabezados'>JORNADA</th>
                <th rowspan="2" class='encabezados'>NO. PUESTO</th>
                <th rowspan="2" class='encabezados'>NOMBRE DEL EMPLEADO</th>
                <th colspan="3" class='encabezados'>DÍAS A DESCONTAR</th>
                
            </tr>   
            <tr>
                <th class="centrado">No. DÍAS</th>
                <tH class="centrado">LETRA</th>
                <tH class="centrado">DÍAS</th> 
            </tr> 
        </thead>
        <tbody class='datos'>
            <?php $numero = 0; ?>
            @foreach ($empleados['datos'] as $index_empleado => $empleado )
                @if($empleados['filtros']['quincena'] == 1)
                    @if(count($empleado['resumen']['FALTAS_QUINCENALES']['Q1']) > 4)
                        <tr>
                            <td class='linea'>{{ $relleno+$numero }} </td>
                            {{-- <td class='linea'>{{ str_pad(($numero+1), 7, $relleno, STR_PAD_LEFT) }} </td> --}}
                            <td class='linea'>{{ $empleado->TITLE}} </td>
                            <td class='linea'>{{ $empleado->PAGER }} </td>
                            <td class='linea' style="text-align:center">{{ $empleado->jornada }} HRS.</td>
                            <td class='linea'> {{ $empleado->num_empleado }} </td>
                            <td class='linea'>{{ $empleado->Name}} </td>
                            
                            <td class='linea centrado'>{{ count($empleado['resumen']['FALTAS_QUINCENALES']['Q1']) }}</td>
                            <td class='linea centrado'>{{ $letras[count($empleado['resumen']['FALTAS_QUINCENALES']['Q1'])] }}</td>
                            <td class='linea centrado'>
                            @for ($i = 0; $i < count($empleado['resumen']['FALTAS_QUINCENALES']['Q1']) ; $i++)
                                {{ $empleado['resumen']['FALTAS_QUINCENALES']['Q1'][$i] }},  
                            @endfor  
                            </td>
                        </tr>
                        <?php $numero++; ?>
                    @endif
                @endif    
                @if($empleados['filtros']['quincena'] == 2)
                    @if(count($empleado['resumen']['FALTAS_QUINCENALES']['Q2']) > 4)
                        <tr>
                            <td class='linea'>{{ $relleno+$numero }} </td>
                            {{-- <td class='linea'>{{ str_pad(($numero+1), 7, $relleno, STR_PAD_LEFT) }} </td> --}}
                            <td class='linea'>{{ $empleado->TITLE}} </td>
                            <td class='linea'>{{ $empleado->PAGER }} </td>
                            <td class='linea' style="text-align:center">{{ $empleado->jornada }} HRS.</td>
                            <td class='linea'> {{ $empleado->num_empleado }} </td>
                            <td class='linea'>{{ $empleado->Name}} </td>
                            
                            <td class='linea centrado'>{{ count($empleado['resumen']['FALTAS_QUINCENALES']['Q2']) }}</td>
                            <td class='linea centrado'>{{ $letras[count($empleado['resumen']['FALTAS_QUINCENALES']['Q2'])] }}</td>
                            
                            <td class='linea centrado'>
                            @for ($i = 0; $i < count($empleado['resumen']['FALTAS_QUINCENALES']['Q2']) ; $i++)
                                {{ $empleado['resumen']['FALTAS_QUINCENALES']['Q2'][$i] }},  
                            @endfor 
                            </td>
                        </tr>
                        <?php $numero++; ?>
                    @endif
                @endif            
               
            @endforeach
        </tbody>
    </table>
    
     

    <script type="text/php">
    if (isset($pdf))
    {
        $iniciales = "";
        @switch($empleados['tipo_trabajador']['id'])
            @case(1)
            @case(2)
                $iniciales = "GOV";
            @break
            @case(4)
                $iniciales = "CAR";
            @break
            @case(3)
                $iniciales = "PEV";
            @break
        @endswitch
        $pdf->page_text(50, 590, $iniciales, Null, 9, array(0, 0, 0));
        $pdf->page_text(900, 590, "  Página {PAGE_NUM} de {PAGE_COUNT}", Null, 9, array(0, 0, 0));
    }
    </script>       
</body>
</html>