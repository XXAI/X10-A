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
            margin: 100px 25px 0px 25px;
        
        }

        .encabezados
        {
            font-size: 9pt;
            text-align: center;
        }

        body{
            margin: 50px 0px;
        }

        header {
            position: fixed;
            width:100%;
            top: -80px;
            left: 0px;
            right: 0px;
            height: 50px;

            
        }

        footer {
            position: fixed; 
            bottom: -60px; 
            left: 0px; 
            right: 0px;
            height: 50px; 

            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px;
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
                           SECRETARÁIA DE SALUD<BR>
                           INSTITUTO DE SALUD DEL ESTADO DE CHIAPAS<BR>
                            DIRECCIÓN DE ADMINISTRACIÓN Y FINANZAS<BR>
                            DEPARTAMENTO DE OPERACIÓN Y SISTEMATIZACIÓN DE NÓMINAS<BR>
                            <BR>
                            CONSTANCIA GLOBAL DE MOVIMIENTOS
                            </div>
                           
                        </td>
                        <td>
                            <div class="datos">
                            LOTE:<br>
                            CÓDIGO MOVIMIENTO:<br>
                            VIGENCIA:<br>
                            QNA. DE CAPTURA:<br>
                            <br>
                            ESTIMULO TRIMESTRAL  {{ $fecha_inicio.$anio }} AL {{ $fecha_fin.$anio }}
                            </div>
                        </td>
                        <td width="100px">
                            <img src='images/chiapas.png' width="100px">
                        </td>
                    </tr>
                   <tr>
                    <td colspan='2' class='datos'>UNIDAD EXPEDIDORA: </td>
                    <td colspan='2' class='datos'>TIPO DE TRABAJADOR: {{ strtoupper($empleados['tipo_trabajador']['DEPTNAME']) }}</td>
                   </tr>
                </tbody>
            </table>
        </div>
    </header>    
    <table width="100%"  cellspacing="0" class="fuente">
        <thead class='cabecera'>
            <tr>
                <th  class='encabezados' width="90px"># DOCUMENTO</th>
                <th  class='encabezados' width="90px">RFC</th>
                <th  class='encabezados' width="60px">CÓDIGO</th>
                <th  class='encabezados' width="120px">CR</th>
                <th  class='encabezados' width="90px">JORNADA LABORAL</th>
                <th  class='encabezados' width="90px">ID</th>
                <th  class='encabezados' width="300px">NOMBRE DEL TRABAJADOR</th>
                <th  class='encabezados' width="90px">NÚMERO DE DÍAS</th>
                <th  class='encabezados' width="90px">LETRAS</th>   
            </tr>   
        </thead>
        <tbody class='datos'>
        <?php $numero = 0; ?>
            @foreach ($empleados['datos'] as $index_empleado => $empleado )
                <tr>
                    <td class='linea'>{{ str_pad(($numero+1), 7, "1100000", STR_PAD_LEFT) }} </td>
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
    <br><br>
    <table width="100%" class='firmantes'>
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

    <script type="text/php">
    if (isset($pdf))
    {
        $fecha = date("Y-m-d H:i:s");
        $pdf->page_text(700, 590, " Tuxtla Gutiérrez, Chiapas, $fecha - Página {PAGE_NUM} de {PAGE_COUNT}", Null, 9, array(0, 0, 0));
    }
    </script>       
</body>
</html>