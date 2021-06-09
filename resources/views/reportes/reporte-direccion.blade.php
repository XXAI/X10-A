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
<?php $letras = array('', "UNO", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS"); 
switch ($empleados['filtros']['direccion']) {
    case '070020':
        $direccion='DIRECCION GENERAL';
        break;
    case '070025':
        $direccion='DIRECCION ADMINISTRACION Y FINANZAS';
        break;
    case '070022':
        $direccion='DIRECCION ATENCION MEDICA';
        break;
    case '070026':
        $direccion='DIRECCION INFRAESTRUCTURA EN SALUD';
        break;
    case '070024':
        $direccion='DIRECCION PLANEACION Y DESARROLLO';
        break;
    case '070021':
        $direccion='DIRECCION SALUD PUBLICA';
        break;
    case '070029':
        $direccion='DIRECCION REGIMEN ESTATAL DE PROTECCION SOCIAL EN SALUD';
        break;
}
?>
<?php //print_r($empleados['datos']); ?>
<body>
    <header>
        <div class="fuente">
            <!--<div class="centrado datos">REPORTE DE FALTAS POR DIRECCIONES<br></div>-->
            <table width="100%">
                <tbody>
                    <tr>
                        <td width="100px">
                            <img src='images/salud.png' width="100px">
                        </td>
                        <td>
                            <div class="centrado datos">
                            <b>REPORTE DE FALTAS POR DIRECCIONES<br>                            
                            UNIDAD RESPONSABLE:INSTITUTO DE SALUD DEL ESTADO DE CHIAPAS<br> 
                            OFICINA CENTRAL<br>
                            {{ $direccion }}<br>
                            MES: {{ $empleados['nombre_mes'] }}<br>   
                        </b>
                           
                           
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
                        <td style='font-size:9pt;' colspan='2'>
                       
                      
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
                
                <th rowspan="2" class='encabezados' width="100px">RFC</th>
                <th rowspan="2" class='encabezados' width="70px">CODIGO</th>
                <th rowspan="2" class='encabezados' width="100px">JORNADA</th>
                <th rowspan="2" class='encabezados' width="100px">NO. PUESTO</th>
                <th rowspan="2" class='encabezados'>NOMBRE DEL EMPLEADO</th>
                
                
            </tr>   
            <tr>
                <th width="80px" class="centrado">No. DÍAS</th>
                <tH width="80px" class="centrado">LETRA</th>
                <tH width="160px" class="centrado">DÍAS</th>
               
                
            </tr> 
        </thead>
        <tbody class='datos'>
            <?php $numero = 0; ?>
            @foreach ($empleados['datos'] as $index_empleado => $empleado )
                 @if($empleados['filtros']['quincena'] == 1)
                    @if(count($empleado['resumen']['FALTAS_QUINCENALES']['Q1']) > 0)
                        <tr>
                            
                            <td class='linea'>{{ $empleado->TITLE}} </td>
                            <td class='linea'>{{ $empleado->PAGER }} </td>
                            <td class='linea' style="text-align:center">{{ $empleado->jornada }} HRS.</td>
                            <td class='linea'> {{ $empleado->num_empleado }} </td>
                            <td class='linea'>{{ $empleado->Name}} </td>
                            
                            <td class='linea centrado'>{{ count($empleado['resumen']['FALTAS_QUINCENALES']['Q1']) }}</td>
                            <td class='linea centrado'>{{ $letras[count($empleado['resumen']['FALTAS_QUINCENALES']['Q1'])] }}</td>
                            <td class='linea centrado'>
                            @for ($i = 0; $i < count($empleado['resumen']['FALTAS_QUINCENALES']['Q1']) ; $i++)
                                      {{ $empleado['resumen']['FALTAS_QUINCENALES']['Q1'][$i] }} -
                                
                            @endfor  
                            </td>
                        </tr>
                        <?php $numero++; ?>
                    @endif
                @endif    
                @if($empleados['filtros']['quincena'] == 2)
                @if(count($empleado['resumen']['FALTAS_QUINCENALES']['Q2']) > 0)
                        <tr>
                         
                            <td class='linea'>{{ $empleado->TITLE}} </td>
                            <td class='linea'>{{ $empleado->PAGER }} </td>
                            <td class='linea' style="text-align:center">{{ $empleado->jornada }} HRS.</td>
                            <td class='linea'> {{ $empleado->num_empleado }} </td>
                            <td class='linea'>{{ $empleado->Name}} </td>
                            
                            <td class='linea centrado'>{{ count($empleado['resumen']['FALTAS_QUINCENALES']['Q2']) }}</td>
                            <td class='linea centrado'>{{ $letras[count($empleado['resumen']['FALTAS_QUINCENALES']['Q2'])] }}</td>
                            <td class='linea centrado'>
                            @for ($i = 0; $i < count($empleado['resumen']['FALTAS_QUINCENALES']['Q2']) ; $i++)
                                      {{ $empleado['resumen']['FALTAS_QUINCENALES']['Q2'][$i] }} -
                                
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
        $iniciales = "Rubi";
         
        $pdf->page_text(50, 590, $iniciales, Null, 9, array(0, 0, 0));
        $pdf->page_text(900, 590, "  Página {PAGE_NUM} de {PAGE_COUNT}", Null, 9, array(0, 0, 0));
    }
    </script>       
</body>
</html>