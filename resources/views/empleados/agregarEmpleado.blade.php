<form>

    <div class="card">
        
        <div class="card-body">

        <div class="row">
                <div class="col-md-6" >
                    <div class="form-group">
                        <label for="nombre" class="col-sm-3 col-form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="180" required>
                       {{--   @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror  --}}
                    </div>
                </div>
                <div class="col-md-6" >
                    <div class="form-group">
                        <label for="rfc" class="col-sm-3 col-form-label">R.F.C.</label>
                        <input type="text" class="form-control" id="rfc" name="rfc" maxlength="13" required>
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
                        <input type="date" class="form-control" id="fechaing " name="fechaing" >
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
                        <input type="text" class="form-control" id="area" name="area" maxlength="8"required>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="card-footer text-muted">
          
        </div>
      </div>
    
    
  </form>