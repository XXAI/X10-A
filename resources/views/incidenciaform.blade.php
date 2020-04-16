
<form>
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}"></input>
    <input type="hidden" id="id" name="id">
    <div class="form-group">
        <label for="nombre" class="col-sm-3 col-form-label">Nombre</label>                                    
        <input type="text" maxlength="255" class="form-control" id="nombre" name="nombre" placeholder="Nombre..." required>
    </div>
    <div class="form-group">
        <label for="nombre" class="col-sm-3 col-form-label">Cédula</label>
        <input type="text" maxlength="10" class="form-control" id="cedula" name="cedula" placeholder="Ced.Prof...." required>
    </div>
    <div class="form-group">
        <label for="incidencia_tipo" class="col-sm-3 col-form-label">Especialidad</label>
        <select class="form-control" id="incidencia_tipo">
            <option disabled selected>Elegir tipo de Incidencia</option>
            {{-- @foreach($incidencias as $tipo)
            <option value="{{$tipo->LeaveId}}">{{$tipo->LeaveName}}</option>
            @endforeach --}}
        </select>
    </div>
    <div class="form-group">
        <label for="telefono" class="col-sm-3 col-form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" pattern="[0-9]{10}" maxlength="10" placeholder="Telefono..." required>
    <div>

    <div class="form-group">    
        <label for="email" class="col-sm-3 col-form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="email...">
    <div>
  </form>