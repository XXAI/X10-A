@extends('layouts.app')

@section('title', 'REPORTE - TRIMESTRAL')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Reporte de Trimestral</h6>
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
                        <select class='select form-control' id = 'trimestre'>
                                <option value='1'>1 - ENERO - MARZO</option>
                                <option value='1'>2 - ABRIL - JUNIO</option>
                                <option value='1'>3 - JULIO - SEPTIEMBRE</option>
                                <option value='1'>4 - OCTUBRE - DICIEMBRE</option>
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
                        
                        <th colspan='3' style='text-align:center'>Resumen</th>
                        <th colspan='17' style='text-align:center'>Asistencias</th>
                        
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