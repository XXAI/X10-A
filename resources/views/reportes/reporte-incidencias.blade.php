<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Incidencias</title>
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
<?php $letras = array('', "UNO", "DOS", "TRES", "CUATRO"); ?>
<?php //print_r($empleados['datos']); ?>
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
                                        <b>Reporte de Capturista<br>                          
                        </td>
                        <td width="100px">
                            <img src='http://sistematizacion.saludchiapas.gob.mx/images/chiapas.png' width="100px">
                        </td>
                    </tr>
                    
                </tbody>
            </table>  
       
        </div>
    </header>    

   
    <table width="100%" cellspacing="0" class="fuente">
        <thead class='cabecera'>
            <tr>
            <th  class='encabezados' width="100px" >ID-Nombre Empleado</th>    
                
                <th  class='encabezados' width="80px">Incidencia</th>
                <th  class='encabezados' width="50px">Inicio</th>
                <th  class='encabezados' width="50px">Fin</th>
                <th  class='encabezados' width="80px">Referencia</th>
                <th class='encabezados' width="50px">Fecha Captura</th>
                <th  class='encabezados' width="80x">Usuario</th>
                <th  class='encabezados' width="40x">Acciones</th>
                            
            </tr>   
            
        </thead>
        <tbody class='datos'>
            <?php $numero = 0; ?>
            @foreach ($capturista['logs'] as $index_captura=> $captura )         
                   
                        <tr>
                            <td class='linea'>{{$captura->empleado['Badgenumber']." - ".$captura->empleado['Name']}} </td>                                                     
                            <td class='linea'>{{ $captura->siglas['LeaveName']}} </td>
                            <td class='linea'>{{ substr($captura->STARTSPECDAY,0,16) }} </td>
                            <td class='linea'>{{ substr($captura->ENDSPECDAY,0,16) }}</td>
                            <td class='linea'> {{ $captura->YUANYING }} </td>
                            <td class='linea'>{{ substr($captura->DATE,0,10)}} </td>
                            <td class='linea'>{{ $captura->capturista['nombre'] }} </td> 
                           
                        </tr>
            <?php $numero++; ?>
            @endforeach
        </tbody>
    </table>
    <script type="text/php">   
    </script>       
</body>
</html>