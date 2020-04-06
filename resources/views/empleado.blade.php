@extends('layouts.app')

@section('title', 'Lista Empleados')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Kardex de Empleado</h6>
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
                        <th width="5%">Id</th>
                        <th width="25%">Nombre</th>
                        <th width="25%">R.F.C.</th>
                        <th width="20%">Estatus</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
            <table class="table table-bordered">
               
                <tbody id='empleados'>
                </tbody>
            </table>
        
	</table>

	

        </div>
    </div>
</div>   

<a id="kardex" data-toggle="modal" data-target="#modal_kardex"></a> 
  
    <div class="modal fade bd-example-modal-lg" id="modal_kardex" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <table class="table table-bordered table-faltas">
                <thead>
                    <tr>
                        
                        <th colspan='17' style='text-align:center'>Asistencias</th>
                        
                    </tr>
                    
                </thead>
                <tbody id='checadas'>
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