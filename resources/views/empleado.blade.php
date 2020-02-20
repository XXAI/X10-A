@extends('layouts.app')

@section('title', 'Lista Empleados')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Reporte de Faltas Quincenales</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
           
            <table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th colspan='20'>Filtro</th>
                        <td>
                            <input type="text" name="buscar" id="buscar">
                            <button name='filtrar' type='button' id='btn_filtrar' class='btn btn-success' onclick="btn_filtrar()" >FILTRAR</button>
                            
                        </td>
                    </tr>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>R.F.C.</th>
                        <th>Estatus</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
            <table class="table table-bordered">
               
                <tbody id='empleados'>
                </tbody>
            </table>
        </div>
    </div>
</div>   



@endsection


@section('scripts')
    @parent
    <script src="js/modulos/empleados/lista.js"></script> 
@stop