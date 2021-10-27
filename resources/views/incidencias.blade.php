@extends('layouts.app')

@section('title', 'Reporte de Capturista')

@section('estilos')
    @parent
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="card shadow mb-3">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Reporte de Incidencias</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
           <div class="row" style='width:99%'>
                
               
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1">ID o Nombre del Empleado</label>
                        <input type='text' class='form-control' name='nombre' id='nombre' style="outline: none;">
                        <input type='hidden' class='form-control' name='user' id='user' value="0">
                        
                    </div>    
                </div>               
              
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha Inicio:</label>
                        <input type="date" class="form-control" id="inicio" min='2019-07-01' name="inicio" value="">
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha Fin:</label>
                        <input type="date" class="form-control" id="fin" name="fin"  value="" max= "{{date('Y-m-d')}}">
                    </div>
                </div>


                
                <div class="col-sm-2">
                    <div class="form-group">            
                        <button class="btn btn-success" id="filtro_check" type='button' onclick="btn_filtrar()"><i class="fa fa-search " ></i></button>
                        <!-- <button class="btn btn-info" type='button' onclick="generar_reporte()"><i class="fa fa-print "></i> <br></button> -->
                       
                        
                    </div>    
                </div>
           </div>
           
            <table class="table table-bordered table-faltas">
                <thead>
                    <tr>
                        <th>Num</th>
                         
                        <th style='text-align:center'>ID-Nombre Empleado</th>                     
                        <th style='text-align:center'>Incidencia</th>
                        <th style='text-align:center'>Inicio</th>
                        <th style='text-align:center'>Fin</th>
                        <th style='text-align:center'>Referencia</th>
                        <th style='text-align:center'>Fecha Captura</th>                       
                        <th>Usuario</th>  
                        <th style='text-align:center'>Acciones</th>
                        
                        
                    </tr>
                    
                </thead>
                <tbody id='lista_incidencias' style="font-size:9pt">
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
    <script src="js/modulos/reportes/incidencias.js"></script> 
@stop