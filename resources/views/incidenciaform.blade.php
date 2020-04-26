<form>
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}"></input>
    <input type="hidden" id="id" name="id">
    <input type="hidden" id="id_user" name="id_user" value="{{ auth()->user()->id }}">
    <div class="card">
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-12" >
                    <div class="form-group">
                        <label for="incidencia_tipo"  class="col-sm-12 col-form-label">Elegir Tipo de Incidencia</label>
                        <select class="form-control" onchange="sel_inci(this.value)" id="incidencia_tipo" required>                   
                        
                        </select>
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
                        <input type="text" class="form-control" id="razon" name="razon" required>
                    </div>
                </div>
                
                
            </div>
        </div>
        <div class="card-footer text-muted">
          
        </div>
      </div>
    
    
  </form>