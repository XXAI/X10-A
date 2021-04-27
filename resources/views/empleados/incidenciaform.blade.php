<form>
    
    <div class="card">
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-12" >
                    <div class="form-group">
                        <label for="incidencia" class="col-sm-3 col-form-label">Incidencia</label>
                        <!-- <input id="horario">    --> 
                        <input id="incidencia"  type="text" style="outline: none;">
                        <input type="hidden" id="code_in" name="code_in" onchange="sel_inci(this.value)" required>  
                    </div>
                    <div id="divmsg" style="display:visible" class="alert-primary" role="alert">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6" >
                    <div class="form-group">
                        <label for="f_ini" class="col-sm-3 col-form-label">Desde</label>
                        <input type="datetime-local" class="form-control" id="f_ini" name="f_ini" >
                    </div>
                </div>
                <div class="col-md-6" >
                    <div class="form-group">
                        <label for="f_fin" class="col-sm-3 col-form-label">Hasta</label>
                        <input type="datetime-local" class="form-control" id="f_fin" name="f_fin" >
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-6" >
                    <div class="form-group">
                        <label for="razon" class="col-sm-3 col-form-label">Razon</label>
                        <input type="text" class="form-control" id="razon" name="razon">
                    </div>
                </div>
                
               <!--  <div class="col-md-6" >
                    <div class="form-group">
                        <label for="razon" class="col-sm-3 col-form-label">diae</label>
                        <input type="text" class="form-control" id="diae" name="diae">
                    </div>
                </div> -->
            </div>

        </div>
        <div class="card-footer text-muted">
          
        </div>
      </div>
    
    
  </form>