
<form>
   
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home">Datos Empleado</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1">Horario</a>
  </li>
  
</ul>

<!-- Tab panes -->
<div class="tab-content">
      <div class="tab-pane container active" id="home">
         <div class="card">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-6" >
                     <div class="form-group">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="180" required>
                     </div>
                  </div>
                  <div class="col-md-6" >
                     <div class="form-group">
                        <label for="rfc" class="col-sm-3 col-form-label">R.F.C.</label>
                        <input type="text" placeholder="ABCDYYMMDDHOM"class="form-control" id="rfc" name="rfc" maxlength="13" required>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6" >
                     <div class="form-group">
                        <label for="sexo"  class="col-sm-12 col-form-label">Genero</label>
                        <select class="form-control" id="sexo" required>
                           <option value="">Seleccione el Sexo</option>
                           <option value="M">Masculino</option>
                           <option value="F">Femenino</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6" >
                     <div class="form-group">
                        <label for="fechaing" class="col-sm-3 col-form-label">Ingreso </label>
                        <input type="date" class="form-control" id="fechaing" name="fechaing">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6" >
                     <div class="form-group">
                        <label for="codigo" class="col-sm-3 col-form-label">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" maxlength="7" >
                     </div>
                  </div>
                  <div class="col-md-6" >
                     <div class="form-group">
                        <label for="clues" class="col-sm-3 col-form-label">CLUES</label>
                        <input type="text" class="form-control" id="clues" name="clues" maxlength="20" required>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6" >
                     <div class="form-group">
                        <label for="tipotra"  class="col-sm-12 col-form-label">Tipo Trabajador</label>
                        <select class="form-control" id="tipotra" name="tipotra" required>                   
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6" >
                     <div class="form-group">
                        <label for="area" class="col-sm-3 col-form-label">Area</label>
                        <input type="text" class="form-control" id="area" name="area" maxlength="150"required>
                     </div>
                  </div>
                  
               </div>
            </div>
            <div class="card-footer text-muted">    
            </div>
         </div>
      </div>
      <div class="tab-pane container fade" id="menu1">
         <div class="row">
            <div class="col-md-4" >
               <div class="form-group">
                  <label for="horario" class="col-sm-3 col-form-label">Horario</label>
                  <!-- <input id="horario">    --> 
                  <input id="horario" type="text" style="outline: none;">
                  <input type="hidden" id="code" name="code" required>  
                     
               </div>
            </div> 
            <div class="col-md-4" >
               <div class="form-group">
                  <label for="inicio" class="col-sm-3 col-form-label">Inicio </label>
                  <input type="date" class="form-control" id="ini_fec" name="ini_fec" value="{{date('Y-m-d')}}">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="fin" class="col-sm-3 col-form-label">Hasta </label>
                  <input type="date" value="2020-12-31"class="form-control" id="fin_fec" name="fin_fec">
               </div>
            </div>
         
         </div> 

         <div class="row" id="tabla-horarios">
            <div class="table-responsive">           
               <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                   <thead>                  
                         
                       <tr>
                           <th scope="col">Horario</th>
                           <th scope="col">Desde</th>
                           <th scope="col">Hasta</th>
                           
                       </tr>
                   </thead>
                   <tbody id='empleado-hora'>
                   </tbody>
               </table>
                       
   
           </div>

         </div>
      </div>

</div>


    
</form>
