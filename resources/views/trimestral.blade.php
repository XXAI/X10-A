@extends('layouts.app')

@section('title', 'REPORTE - TRIMESTRAL')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Reporte de Trimestral</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
           
        <div class="row" style='width:99%'>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Año</label>
                        <select class='select form-control' id = 'anio'>
                            <option value = '2020'>2020</option>
                            <option value = '2019'>2019</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Trimestre</label>
                        <select class='select form-control' id = 'trimestre'>
                            <option value='1'>1 - ENERO - MARZO</option>
                            <option value='2'>2 - ABRIL - JUNIO</option>
                            <option value='3'>3 - JULIO - SEPTIEMBRE</option>
                            <option value='4'>4 - OCTUBRE - DICIEMBRE</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="exampleInputEmail1">ID, Nombre, RFC</label>
                        <input type='text' class='form-control' name='nombre' id='nombre'>
                    </div>    
                </div>
                
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Tipo Trabajador</label>
                        <select id='tipo_trabajador' class='select form-control'>
                        </select>
                    </div>    
                </div>
                <div class="col-sm-2">
                    <div class="form-group">            
                        <button class="btn btn-success" type='button' onclick="btn_filtrar()" ><i class="fa fa-search fa-3x" ></i></button>
                        <button class="btn btn-info" type='button' onclick="generar_reporte()"><i class="fa fa-print "></i> <br>REPORTE</button>
                        
                    </div>    
                </div>
           </div>
            <!--<table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th colspan='20'>Filtro</th>
                    </tr>
                    <tr>
                        <th>Año</th>
                        <th>Trimestre</th>
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
            </table>-->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>RFC</th>
                        <th>CÓDIGO</th>
                        <th>CR</th>
                        <th>JORNADA LABORAL</th>
                        <th>ID</th>
                        <th>NOMBRE DEL TRABAJADOR</th>
                        <th>DÍAS</th>
                        <th>LETRAS</th>
                    </tr>
                    
                </thead>
                <tbody id='lista_personal'>
                </tbody>
            </table>
        </div>
    </div>
</div>        
@endsection

@section('scripts')
    @parent
    <script src="js/modulos/reportes/trimestral.js"></script> 
@stop