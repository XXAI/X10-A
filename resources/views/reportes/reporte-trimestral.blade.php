<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Trimestral</title>
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
            margin: 100px 35px 0px 50px;
        
        }

        .encabezados
        {
            font-size: 9pt;
            text-align: center;
        }

        body{
            margin: 50px 0px 140px 5px;
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

<?php //print_r($empleados); ?>
<?php $letras = array('', "UNO", "DOS", "TRES"); ?>
<?php 
$anio = $empleados['filtros']['anio'];
$fecha_inicio ="";
$fecha_fin ="";
switch($empleados['trimestre'])
{
    case 1:
        $fecha_inicio = "01/01/";
        $fecha_fin = "31/03/";
    break;
    case 2:
        $fecha_inicio = "01/04/";
        $fecha_fin = "30/06/";
    break;
    case 3:
        $fecha_inicio = "01/07/";
        $fecha_fin = "30/09/";
    break;
    case 4:
        $fecha_inicio = "01/10/";
        $fecha_fin = "31/12/";
    break;
} ?>
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
                            <BR>
                            CONSTANCIA GLOBAL DE MOVIMIENTOS
                            </div>
                           
                        </td>
                        <td>
                            <div class="datos">
                            LOTE: 
                            @switch($empleados['tipo_trabajador']['DEPTID'])
                                @case(6)
                                @case(11)
                                    GOV0021
                                @break
                                @case(13)
                                    CAR0011
                                @break
                                @case(12)
                                    PEV0105
                                @break
                            @endswitch
                            <br>
                            CÓDIGO MOVIMIENTO: 9204<br>
                            VIGENCIA: {{ $fecha_inicio.$anio }} AL {{ $fecha_fin.$anio }}<br>
                            QNA. DE CAPTURA: 20/21<br>
                            <br>
                            ESTIMULO TRIMESTRAL  
                            </div>
                        </td>
                        <td width="100px">
                            <img src='images/chiapas.png' width="100px">
                        </td>
                    </tr>
                   <tr>
                    <td colspan='2' class='datos'>UNIDAD EXPEDIDORA: OFICINA CENTRAL</td>
                    <td colspan='2' class='datos'>TIPO DE TRABAJADOR: {{ strtoupper($empleados['tipo_trabajador']['DEPTNAME']) }}</td>
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
            ING. JAVIER MORALES SOLÍS
            <HR>
            NOMBRE Y FIRMA
            </td>
            <td class="centrado tamano">
            <br>SUBDIRECTOR DE RECURSOS HUMANOS
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
        <?php 
            $numero = 0;
            $relleno = "1100000";
            switch ($empleados['tipo_trabajador']['DEPTID']) {
                case 6:
                    $numero = 273;
                    $relleno = "1100000";
                break;
                case 11:
                    $numero = 259;
                    $relleno = "1100000";
                break;
                case 13:
                    $numero = 0;
                    $relleno = "1100000";
                break;
                case 12:
                    $numero = 352;
                    $relleno = "3300000";
                break;
                
            }
        ?>
            
        
    <table width="100%"  cellspacing="0" class="fuente">
        <thead class='cabecera'>
            <tr>
                <th  class='encabezados' width="120px"># DOCUMENTO</th>
                <th  class='encabezados' width="120px">RFC</th>
                <th  class='encabezados' width="80px">CÓDIGO</th>
                <th  class='encabezados' width="120px">CR</th>
                <th  class='encabezados' width="90px">JORNADA LABORAL</th>
                <th  class='encabezados' width="90px">ID</th>
                <th  class='encabezados' width="300px">NOMBRE DEL TRABAJADOR</th>
                <th  class='encabezados' width="90px">NÚM. DE DÍAS</th>
                <th  class='encabezados' width="90px">LETRAS</th>   
            </tr>   
        </thead>
        <tbody class='datos'>
        
            @foreach ($empleados['datos'] as $index_empleado => $empleado )
                <tr>
                    <td class='linea'>{{ str_pad(($numero+1), 7, $relleno, STR_PAD_LEFT) }} </td>
                    <td class='linea'>{{ $empleado->TITLE}} </td>
                    <td class='linea'>{{ $empleado->PAGER}} </td>
                    <td class='linea'> {{ $empleado->carType}}</td>
                    <td class='linea'> {{ $empleado->jornada_laboral}} HRS.</td>
                    
                    <td class='linea'>{{ $empleado->Badgenumber}} </td>
                    <td class='linea'>{{ $empleado->Name}} </td>
                    
                    <td class='linea'>{{ $empleado->TRIMESTRAL }} </td>
                    <td class='linea'>{{ $letras[$empleado->TRIMESTRAL] }}</td>
                   
                </tr>    
                <?php $numero++; ?>
            @endforeach
            
        </tbody>
    </table>


    <script type="text/php">
    if (isset($pdf))
    {
        $iniciales = "";
        @switch($empleados['tipo_trabajador']['DEPTID'])
            @case(6)
            @case(11)
                $iniciales = "GOV";
            @break
            @case(13)
                $iniciales = "CAR";
            @break
            @case(12)
                $iniciales = "PEV";
            @break
        @endswitch
        $pdf->page_text(50, 590, $iniciales, Null, 9, array(0, 0, 0));
        $pdf->page_text(900, 590, "  Página {PAGE_NUM} de {PAGE_COUNT}", Null, 9, array(0, 0, 0));
    }
    </script>       
</body>
</html>