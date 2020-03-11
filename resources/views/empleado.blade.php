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
                            <button name='agregar' type='button' id='btn_agregar' class='btn btn-success' onclick="btn_agregar()" >AGREGAR</button>
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
<a id="empleado" data-toggle="modal" data-target="#modal_empleado"></a>

<div class="modal fade bd-example-modal-xl" id="modal_empleado" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="exampleModalLabel">Alta Empleados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre(s)" maxlength="100"required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="apaterno">Apellido Paterno</label>
                            <input type="text" class="form-control" id="apaterno" placeholder="Apellido Paterno" maxlength="50" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="amaterno">Apellido Materno</label>
                            <input type="text" class="form-control" id="amaterno" placeholder="Apellido Materno" maxlength="50" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="rfc">R.F.C.</label>
                            <input type="text" class="form-control" id="rfc" placeholder="R.F.C." maxlength="13" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="codigo">Codigo</label>
                            <input type="text" class="form-control" id="codigo" placeholder="codigo" required>
                        </div>
                        <div class="col-md-3 mb-3">
                           
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select id="tipo" class="custom-select" name="">
                                    
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <div class="col-md-3 mb-3">
                            <label for="cr">Tipo</label>
                            <input type="text" class="form-control" id="cr" placeholder="cr" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="rojo" id="checa" checked >
                            <label class="form-check-label" for="checa">
                                Checa?
                            </label>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit" onclick="btn_guardar()">Guardar</button>
                    </form>
            </div>
        </div>
    </div>
</div>


@endsection


@section('scripts')
    @parent
    <script src="js/modulos/empleados/lista.js"></script> 
@stop