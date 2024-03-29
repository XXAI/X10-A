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
            font-size: 12pt;
            background-color:#CFCFCF;
            border: 0px;
            
        }

        .fuente
        {
            font-family: Helvetica;
        }
        .datos
        {
            font-size: 10pt;
        }

        .firmantes
        {
            font-size: 11pt;
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
            margin: 60px 0px 140px 5px;
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
                            <img src='https://sistematizacion.saludchiapas.gob.mx/images/salud.png' width="100px">
                        </td>
                        <td>
                            <div class="centrado datos">
                           SECRETARÍA DE SALUD<BR>
                           INSTITUTO DE SALUD DEL ESTADO DE CHIAPAS<BR>
                           {{$unidad['unidad']}}<BR>
                           {{$unidad['departamento']}}<BR>
                            <BR>
                            CONSTANCIA GLOBAL DE MOVIMIENTOS
                            </div>
                           
                        </td>
                        <td>
                        <?php 
                            
                            $alias="";
                             switch ($empleados['tipo_trabajador']['id']) {
                                /*case 6:
                                case 11:*/
                                case 3:
                                     $alias = "PEV";
                                break;
                                case 4:
                                    $alias = "CAR";
                                break;
                                default:
                                    $alias = "GOV";
                                break;
                                
                            }  
                        ?>
                        
                            <div class="datos">
                            LOTE: {{ $alias }}{{ str_pad($config['lote'], 4, "0", STR_PAD_LEFT)}}
                            
                            <br>
                            CÓDIGO MOVIMIENTO: 9204<br>
                            VIGENCIA: {{ $fecha_inicio.$anio }} AL {{ $fecha_fin.$anio }}<br>
                            QNA. DE CAPTURA: {{ $config['quincena'] }}<br>
                            <br>
                            ESTIMULO TRIMESTRAL  
                            </div>
                        </td>
                        <td width="100px">
                            <img src='https://sistematizacion.saludchiapas.gob.mx/images/chiapas.png' width="100px">
                        </td>
                    </tr>
                   <tr>
                    <td colspan='2' class='datos'>UNIDAD EXPEDIDORA: @if($usuario['base_id']==1) OFICINA CENTRAL @else {{$unidad['unidad']}}  @endif </td>
                    <td colspan='2' class='datos'>TIPO DE TRABAJADOR: {{ strtoupper($empleados['tipo_trabajador']['descripcion']) }}</td>
                   </tr>
                </tbody>
            </table>
        </div>
    </header>  
    <table width="100%" class='firmantes footer'>
        <tr>
            <td class="centrado tamano">
            {{$unidad['puesto1']}}
            <br><br><br>
            {{$unidad['responsable1']}}
            <HR>
            NOMBRE Y FIRMA
            </td>
            <td class="centrado tamano">
            {{$unidad['puesto2']}}
            <br><br><br>
            {{$unidad['responsable2']}}
            <HR>
            NOMBRE Y FIRMA
            </td>
            <td class="centrado tamano">
            {{$unidad['puesto3']}}
            <br><br><br>
            {{$unidad['responsable3']}}
            <HR>
            NOMBRE Y FIRMA
            </td>
        </tr>
    </table>   
        <?php 
            if($usuario['base_id']==1){
                $relleno = "1100000";
                $numero = $config['no_documento'];
                switch ($empleados['tipo_trabajador']['id']) {
                    /*case 6:
                    case 11:
                    case 13:
                    break;*/
                    case 3:
                        $relleno = "3300000";
                    break;
                    default:
                        $relleno = "1100000";
                    break;
                    
                }
            }else{
                $relleno = "0000000";
                $numero = $config['no_documento'];
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
                    <td class='linea'>{{ str_pad(($numero++), 7, $relleno, STR_PAD_LEFT) }} </td>
                    <td class='linea'>{{ $empleado->TITLE}} </td>
                    <td class='linea'>{{ $empleado->PAGER}} </td>
                    <td class='linea'> {{ $empleado->carType}}</td>
                    <td class='linea'> {{ $empleado->jornada_laboral}} HRS.</td>
                    
                    <td class='linea'>{{ $empleado->Badgenumber}} </td>
                    <td class='linea'>{{ $empleado->Name}} </td>
                    
                    <td class='linea'>{{ $empleado->TRIMESTRAL }} </td>
                    <td class='linea'>{{ $letras[$empleado->TRIMESTRAL] }}</td>
                   
                </tr>    
                
            @endforeach
            
        </tbody>
    </table>


    <script type="text/php">
    if (isset($pdf))
    {
        $iniciales = '{{ $alias }}';
        
        $pdf->page_text(50, 590, $iniciales, Null, 9, array(0, 0, 0));
        $pdf->page_text(900, 590, "  Página {PAGE_NUM} de {PAGE_COUNT}", Null, 9, array(0, 0, 0));
    }
    </script>       
</body>
</html>