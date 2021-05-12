<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tarjeta de Asistencia</title>
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

        .parrafo
        {
            font-family: Helvetica;
            font-size: 10pt;
            text-align: justify;
        }
        .encabezado
        {
            font-family: Helvetica;
            font-size: 10pt;
            text-align: left;
        }
        .fecha
        {
            font-family: Helvetica;
            font-size: 10pt;
            text-align: right;
        }
        .datos
        {
            font-size: 9pt;
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

    </style>
</head>
<?php
 $datos   = $asistencia;
// var_dump($arr);
 //header('Content-Type: text/html; charset=utf-8');
 
 
    //echo 'Resultado vale: ', $datos , PHP_EOL;
 //echo($asistencia);
/*$empleado   = $pase['usuarios'];
$incidencia = $pase['tipos_incidencia'];
 */

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
    <!-- <table width="100%"  cellspacing="0" class="fuente_datos">
        <tr>
            <td>ID</td><td> <b>echo $objeto['id']</b></td>
        </tr>
    </table>  -->
<div class="marco">
<h3 style="text-align: center;">P&nbsp;&nbsp;A&nbsp;&nbsp;S&nbsp;&nbsp;E&nbsp;&nbsp;&nbsp;&nbsp;D&nbsp;E&nbsp;&nbsp;&nbsp;&nbsp;S&nbsp;&nbsp;A&nbsp;&nbsp;L&nbsp;&nbsp;I&nbsp;&nbsp;D&nbsp;&nbsp;A</h3>
    <table width="100%" class="fuente">
    	<tr>
            <td class="encabezado">
                <strong>C. ENCARGADO DEL CONTROL DE ASISTENCIA.<br>
                O F I C I N A    C E N T R A L.<br>
                EDIFICIO.</strong>
            </td>

        	
    	</tr>
		<tr>
			<p class="parrafo">EN ATENCIÓN A LA SOLICITUD DE (EL) (LA) C. <strong><?php ?></strong>Y DE CONFORMIDAD<br>
                CON LO DISPUESTO POR EL ARTÍCULO 96 DE LAS CONDICIONES GENERALES DE TRABAJO
                DE LA SECRETARÍA DE SALUD, SE LE AUTORIZA LA SALIDA DE LAS
               
			</p>
		</tr>

	</table>
    <br>
    <table width="100%" class='firmantes'>
            <tbody class='datos'>
                    <?php $numero = 0; ?>


                   <!--  @foreach($datos as $obj) -->
                    

                    <tr>
                            <td >{{ print_r($datos) }}</td>
                           
                           
                    </tr>    
                    <!-- @endforeach -->
                                        
                        
                      
             
               
                
            </tbody>
    </table>
    <br>
    

</div>
<br>
<div>
       
</div>




    
</body>
</html>