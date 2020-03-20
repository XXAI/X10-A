@extends('layouts.app')

@section('title', 'REPORTE - Faltas')

@section('estilos')
    @parent
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Reporte de Faltas Mensuales</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
           
            <table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th colspan='20'>Filtro</th>
                    </tr>
                    <tr>
                        <th>Año</th>
                        <th>Mes</th>
                        <th>ID, RFC o Nombre</th>
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
                        <input type='text' class='form-control' name='nombre' id='nombre'>
                        <!--<select class='select form-control' id = 'quincena'>
                                <option value='1'>QUINCENA 1</option>
                                <option value='2'>QUINCENA 2</option>
                            </select>
                        </td>-->
                        <td>
                            <select id='tipo_trabajador' class='select form-control'>
                            </select>
                        </td>
                        <td>
                            <button name='filtrar' type='button' id='btn_filtrar' class='btn btn-success' onclick="btn_filtrar()" >FILTRAR</button>
                            <button type='button' class='btn btn-info' onclick="generar_reporte()" ><i class='fa fa-print'></i>8001</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered table-faltas">
                <thead>
                    <tr>
                        <th>Nombre / RFC</th>
                        
                        <th colspan='4' style='text-align:center'>Resumen</th>
                        <th colspan='17' style='text-align:center'>Asistencias</th>
                        
                    </tr>
                    
                </thead>
                <tbody id='lista_personal' style="font-size:9pt">
                </tbody>
            </table>
        </div>
    </div>
</div>        
@endsection

@section('scripts')
    @parent
    <script src="js/modulos/reportes/quincenal.js"></script> 
@stop