
<form>
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}"></input>
    <input type="hidden" id="id" name="id">
    <div class="card">
        <div class="card-header">
          Rango de Fecha
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="f_ini" class="col-sm-3 col-form-label">Desde</label>
                <input type="datetime-local" class="form-control" id="f_ini" name="f_ini" >
            </div>
            <div class="form-group">
                <label for="f_fin" class="col-sm-3 col-form-label">Hasta</label>
                <input type="datetime-local" class="form-control" id="f_fin" name="f_fin" >
            </div>
            <div class="form-group">
                <label for="incidencia_tipo" class="col-sm-12 col-form-label">Elegir Tipo de Incidencia</label>
                <select class="form-control" id="incidencia_tipo">
                    
                   
                </select>
            </div>
        </div>
        <div class="card-footer text-muted">
          
        </div>
      </div>
    
    
  </form>