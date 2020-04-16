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
                        <td>
                            <div class="row">
                                <div class="col">
                                    <button name='filtrar' type='button' id='btn_filtrar' class='btn btn-success' onclick="btn_filtrar()" >FILTRAR</button>
                                </div>
                                <div class="col">
                                 <input class="form-control ds-input" type="text" name="buscar" id="buscar" >
                                </div>
                            </div>                                                     
                        </td>
                    </tr>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th >R.F.C.</th>
                        <th >Estatus</th>
                        <th >--</th>
                        <th >--</th>
                    </tr>
                </thead>
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
                <div id='kardex'>
                </div>
            </table>
            </div>
        </div>
    </div>

    <a id="checadas_modal" data-toggle="modal" data-target="#modal_checadas"></a> 
  
    <div class="modal fade bd-example-modal-lg" id="modal_checadas" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <table class="table table-bordered table-faltas">
                <thead>
                    <tr>
						<th colspan="5">
                            <br>
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12 col-md-offset-2" >
                                        <div >
                                            <div >
                                                <div class="row">
                                                    <div class="col-md-4" >
                                                        <label for="fecha_inicio">Fecha Inicio:</label>
                                                        <input type="date" class="form-control" id="inicio" min='2019-10-01' name="fecha_inicio" value="">
                                                    </div>                                             
                                                   <div class="col-md-4" >
                                                        <label for="fecha_inicio">Fecha Fin:</label>  
                                                        <input type="date" class="form-control" id="fin" name="fecha_fin"  value="" max= "{{date('Y-m-d')}}">
                                                   </div>
                                                   <div class="col-md-4" > 
                                                    <br>
                                                       <label for="fecha_inicio"><br></label>
                                                        <button onclick="filtrar_checadas()" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Filtrar"><i class="fa fa-search"></i>  Filtrar</button>
                                                   
                                                    </div>
                                                </div>
                                                <br>
                                                
                                            </div>    
                                        </div>   
                                    </div>
                                </div>
                                
                                                       
                                            
                                            
							<br>
							
						</th>
						
					</tr>	
                    
                </thead>
                <section id="checadas" class="card">
                    <!--<h4 class="card-title" style="color:red">En la leyenda <strong>SIN REGISTRO</strong> probablemente <strong>no registro ó no ha comprobado una incidencia</strong></h4>-->
                
                    <table id="tabla_checadas" class="table table-striped">
                        <thead class="black white-text">
                            <tr>
                                <th>Día</th>
                                <th>Fecha</th>
                                <th>Hora Entrada</th>
                                <th>Hora Salida</th>
                                <th>Justificado</th>
                            </tr>
                        </thead>
                        <tbody id="datos_filtros_checadas">
                            
                        </tbody>
                    </table>
                </section>
            </table>
            </div>
        </div>
    </div>
    <div class="modal" id="agregar_incidencia" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Agregar Incidencia</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                @include('incidenciaform')
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="guardar_incidencia()">Guardar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

@endsection


@section('scripts')
    @parent
    <script src="js/modulos/empleados/lista.js"></script> 
   
    
@stop