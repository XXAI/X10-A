<form>
    
    <div class="card">
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-12" >
                    <div class="form-group">
                        <label for="tipo_registro"  class="col-sm-12 col-form-label">Elegir Tipo de Checada</label>                                         
                            <select class="form-control" id="tipo_es" name="tipo_es" onchange="sel_tiporeg(this.value)" required>
                                <option value="">Seleccione el tipo de Registro</option>
                                <option value="I">Entrada</option>
                                <option value="O">Salida</option>                                
                            </select>
                    </div>
                    <div id="divmsg2" style="display:visible" class="alert-primary" role="alert">
                    </div>
                </div>
            </div>
            <div class="row">                   
                    <div class="col-md-6" >
                        <div class="form-group">
                            <label for="f_ini" class="col-sm-12 col-form-label">Entrada o Salida</label>
                            <input type="datetime-local" class="form-control"   id="fecha_reg" name="fecha_reg" readonly >
                            
                        </div>
                        
                    </div>           
                        
                   
            </div>
            
            <div class="row">
                <div class="col-md-6" >
                    <div class="form-group">
                        <label for="razon" class="col-sm-12 col-form-label">Referencia</label>
                        <input type="text" class="form-control" id="refe" name="refe" required>
                    </div>
                </div>
                
                
            </div>
        </div>
        <div class="card-footer text-muted">
          
        </div>
      </div>
    
    
  </form>