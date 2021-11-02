@extends('layouts.app')

@section('title', 'REPORTE - CARDEX')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Reporte CARDEX</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
           
        <div class="row" style='width:99%'>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Año</label>
                        <select class='select form-control' id = 'anio'>
                            <option value = '2022'>2021 - 2022</option> 
                            <option value = '2021'>2020 - 2021</option>
                            <option value = '2020'>2019 - 2020</option>
                                                       
                        </select>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="exampleInputEmail1">ID, Nombre, RFC</label>
                        <input type='text' class='form-control' name='filtro' id='filtro'>
                    </div>    
                </div>
                <div class="col-sm-2">
                    <div class="form-group">            
                        <button class="btn btn-success" type='button' onclick="btn_filtrar()" ><i class="fa fa-search" ></i></button>
                        <button class="btn btn-info" type='button' onclick="generar_reporte()"><i class="fa fa-print "></i></button>
                        
                    </div>    
                </div>
           </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>RFC</th>
                        <th>CURP</th>
                        <th>CR</th>
                        <th>NOMBRE DEL TRABAJADOR</th>
                        <th></th>
                        
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
    <script src="js/modulos/reportes/cardex.js"></script> 
@stop