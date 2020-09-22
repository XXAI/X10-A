<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pase de Salida</title>
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

        .fuente_datos
        {
            font-family: Helvetica;
            font-size: 9pt;
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
    </style>
</head>
<?php
$objeto = $pase_salida;

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
    <table width="100%"  cellspacing="0" class="fuente_datos">
        <tr>
            <td>ID</td><td> <b><?php echo $objeto['id'] ?></b></td>
        </tr>
    </table> 
    <br>       
    <table width="100%" class='firmantes footer'>
	<tr>
            <td class="centrado tamano">
            AREA DE INFORMATICA
            <br><br><br>
            <?php echo $objeto['autoriza'] ?>
            <HR>
            NOMBRE DEL JEFE
            </td>
            <td class="centrado tamano">
            <br>EL TRABAJADOR
            <br><br><br>
            <?php echo $objeto['USERID'] ?>
            <HR>
			NOMBRE DEL EMPLEADO
        </tr> 
    </table> 

    <table width="100%" class="fuente">
    	<tr>
        	<td style="text-align: center;">PASE  DE  SALIDA</td>
    	</tr>
		<td>
			<p>EN ATENCIÓN A LA SOLICITUD DE (EL) (LA) C. <strong><?php echo $objeto['id'] ?></strong><br>
			Y DE CONFORMIDAD CON LO DISPUESTO POR EL ARTÍCULO 96 DE LAS CONDICIONES GENERALES DE TRABAJO<br>
			DE LA SECRETARÍA DE SALUD, SE LE AUTORIZA LA SALIDA DE LAS <?php echo $objeto['fecha_ini'] ?> 
			</p>
		</td>
	</table>


    
</body>
</html>