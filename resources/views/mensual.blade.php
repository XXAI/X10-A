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
           <div class="row" style='width:99%'>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Año</label>
                        <select class='select form-control' id = 'anio'>
                            <option value = '2021'>2021</option>
                            <option value = '2020'>2020</option>
                            <option value = '2019'>2019</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mes</label>
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
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">ID, Nombre, RFC</label>
                        <input type='text' class='form-control' name='nombre' id='nombre'>
                    </div>    
                </div>
                <div class="col-sm-2">
                    <div class="form-group">            
                        <label for="exampleInputEmail1">Quincena</label>
                        <select class='select form-control' id = 'quincena'>
                            <option value='1'>QUINCENA 1</option>
                            <option value='2'>QUINCENA 2</option>
                        </select>
                    </div>    
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Tipo Trabajador</label>
                        <select id='tipo_trabajador' class='select form-control'>
                        </select>
                    </div>    
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label for="exampleInputEmail1">No Documento</label>
                        <input type='text' class='form-control' name='documento' id='documento'>
                    </div>    
                </div>
                <div class="col-sm-2">
                    <div class="form-group">            
                        <button class="btn btn-success" type='button' onclick="btn_filtrar()"><i class="fa fa-search fa-3x" ></i></button>
                        <button class="btn btn-info" type='button' onclick="generar_reporte()"><i class="fa fa-print "></i> <br>8001</button>
                        <button class="btn btn-info" type='button' onclick="generar_reporte_juridico()"><i class="fa fa-print "></i> <br>8002</button>
                    </div>    
                </div>
           </div>
           
            <table class="table table-bordered table-faltas">
                <thead>
                    <tr>
                        <th>Nombre / RFC</th>
                        
                        <th colspan='4' style='text-align:center'>Resumen</th>
                        <th colspan='17' style='text-align:center'>Asistencias</th>
                        
                    </tr>
                    
                </thead>
                <tbody id='lista_personal' style="font-size:9pt">
                    <tr>
                        <td colspan='10'>No se encuentran resultados</td>
                    </tr>
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