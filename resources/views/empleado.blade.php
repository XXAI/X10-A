@extends('layouts.app')

@section('title', 'Lista Empleados')

@section('content')
<div class="card shadow mb-4">
    
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Empleados</h6>
    </div>
    
    <div class="card-body">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" id="id" name="id">
    <input type="hidden" id="id_user" name="id_user" value="{{ auth()->user()->id }}">
        <div class="table-responsive">           
            <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>                  
                     <div class="col-md-12" >
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="ID,RFC,NOMBRE"  name="buscar" id="buscar"aria-label="BUSCAR" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                            <button name='filtrar' type='button' data-toggle='tooltip' data-placement='top' title='Buscar Empleado' id='btn_filtrar' class='btn btn-success' onclick="btn_filtrar()"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <button class="btn btn-primary" type="button" data-toggle='modal' data-target='#agregar_empleado' id="nuevo_empleado" onclick="cargar_departamentos()" ><i class="fa fa-plus-circle" aria-hidden="true" data-toggle='tooltip' data-placement='top' title='Agregar Empleado' ></i></button>
                        </div>
                    </div>         
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">R.F.C.</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">--</th>
                        <!-- <th scope="col">--</th> -->
                    </tr>
                </thead>
                <tbody id='empleados'>
                </tbody>
            </table>
        </table>                 

        </div>
    </div>
</div>   

 

     <a id="checadas_modal" data-toggle="modal" data-target="#modal_checadas"></a>  
  
    <div class="modal fade" id="modal_checadas" style="overflow-y: scroll;" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      
          <div class="modal-dialog modal-xl">
              <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Lista de Checadas</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                  <table class="table table-bordered table-faltas">
                  <thead>
                      <tr>
              <th colspan="5">
                              <br>
                              <div class="container">
                                  <div class="row">
                                      <div class="col-sm-12 col-md-offset-2" >
                                              <div class="row">
                                                  <div class="col-md-6" >
                                                      <label for="id">ID:</label> 
                                                      
                                                      <span id="iduser" name="iduser"></span>
                                                    
                                                  </div>
                                                  <div class="col-md-6" >
                                                      <label for="hentra">Hora Entrada:</label>  
                                                      <span id="hentra" name="hentra"></span>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-6" >
                                                      
                                                      <label for="nombre">Nombre:</label>  
                                                      <span id="nombre" name="nombre"></span>
                                                  </div>
                                                  <div class="col-md-6" >
                                                      <label for="hsal">Hora Salida:</label>  
                                                      <span id="hsal" name="hsal"></span>
                                                  </div>
                                              </div>
                                              <br>
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
                                                      <button onclick="filtrar_checadas()" id="filtro_check" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Filtrar"><i class="fa fa-search"></i>  Filtrar</button>
                                                  
                                                  </div>
                                              </div>
                                              
                                                
                                              <br>
                                                  
                                                
                                      </div>
                                  </div>
                                  
                                                        
                                              
                                              
                <br>
                
              </th>
              
            </tr>	
                      
                  </thead>
                  <section id="checadas" class="card">                  
                      <table id="tabla_checadas" class="table table-striped">
                          <thead >
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
              <div class="modal-footer">
                  
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                
                </div>
             
          </div>
      </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="agregar_incidencia"  tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Agregar Incidencia</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                @include('empleados/incidenciaform')
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="guardar_incidencia()">Guardar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
    </div>



      <div class="modal fade bd-example-modal-lg" id="agregar_entrasal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Agregar Entrada o Salida</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                @include('empleados/entrasalform')
            </div>
            <div class="modal-footer">
           
              <button type="button" class="btn btn-primary"   onclick="guardar_entrasal()">Guardar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>


      <div class="modal fade bd-example-modal-xl" id="agregar_empleado" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Agregar Empleado</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                @include('empleados/agregarEmpleado')
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="guardar_empleado()">Guardar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

      
      <div class="modal fade bd-example-modal-xl" id="editar_emp" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Editar Empleado</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                @include('empleados/editarEmpleado')
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="guardar_empleado()">Guardar</button>
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