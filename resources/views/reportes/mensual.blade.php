<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Mensual</title>
    <link rel="stylesheet" href="libs/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="libs/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/hover-table.css">
	<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/mdb.min.js"></script>
	<script type="text/javascript" src="js/rh/mensual.js"></script>
</head>
<body>

<table class="table">
    <thead>
        <tr>
            <th colspan='4'>Filtro</th>
        </tr>
        <tr>
            <th>Año</th>
            <th>Mes</th>
            <th>Quincena</th>
            <th>Tipo Empleado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <select class='select form-control' id = 'anio'>
                    <option value = '2020'>2020</option>''
                    <option value = '2019'>2019</option>
                </select>
            </td>
            <td>
                <select class='select form-control' id = 'mes'>
                    <option value='01'>ENERO</option>
                    <option value='02'>FEBRERO</option>
                    <option value='03'>MARZO</option>
                    <option value='04'>ABRIL</option>
                    <option value='05'>MAYO</option>
                    <option value='06'>JUNIO</option>
                    <option value='07'>JULIO</option>
                    <option value='08'>AGOSTO</option>
                    <option value='09'>SEPTIEMBRE</option>
                    <option value='10'>OCTUBRE</option>
                    <option value='11'>NOVIEMBRE</option>
                    <option value='12'>DICIEMBRE</option>
                </select>
            </td>
            <td>
            <select class='select form-control' id = 'quincena'>
                    <option value='1'>QUINCENA 1</option>
                    <option value='2'>QUINCENA 2</option>
                </select>
            </td>
            <td>
                <select id='tipo_trabajador' class='select form-control'>
                </select>
            </td>
            <td>
                <button name='filtrar' type='button' id='btn_filtrar' class='btn btn-success' onclick="btn_filtrar()" >FILTRAR</button>
                <button type='button' class='btn btn-info' onclick="generar_reporte()" ><i class='fa fa-print'></i> GENERAR REPORTE</button>
            </td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre / RFC</th>
            
            <th colspan='5' style='text-align:center'>Resumen</th>
            <th colspan='17' style='text-align:center'>Asistencias</th>
            
        </tr>
        
    </thead>
    <tbody id='lista_personal'>
    </tbody>
</table>
    
</body>
</html>