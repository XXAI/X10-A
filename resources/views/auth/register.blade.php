@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Registrar Nuevo Usuario</h6>
    </div>

                <div class="card-body">
                <form>                         
                        @csrf                     
                        <div class="row">
                            <div class="col-md-4" >
                                <div class="form-group">
                                    <label for="nombre" class="col-md-4 col-form-label text-md-center">Nombre</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                     @enderror
                                </div>
                            </div>
                            <div class="col-md-4" >
                                <div class="form-group">
                                    <label for="apellido_paterno" class="col-md-8 col-form-label text-md-center">Apellido Paterno</label>
                                    <input id="apellido_paterno" type="text" class="form-control @error('apellido_paterno') is-invalid @enderror" name="apellido_paterno" value="{{ old('apellido_paterno') }}" required autocomplete="apellido_paterno" autofocus>
                                    @error('apellido_paterno')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4" >
                                <div class="form-group">
                                    <label for="apellido_materno" class="col-md-8 col-form-label text-md-center">Apellido Materno</label>
                                    <input id="apellido_materno" type="text" class="form-control @error('apellido_materno') is-invalid @enderror" name="apellido_paterno" value="{{ old('apellido_paterno') }}" required autocomplete="apellido_materno" autofocus>
                                    @error('apellido_materno')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4" >
                                <div class="form-group">
                                    <label for="email" class="col-md-4 col-form-label text-md-center">{{ __('E-Mail') }}</label>                            
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4" >
                                <div class="form-group">
                                <label for="password" class="col-md-4 col-form-label text-md-center">{{ __('Password') }}</label>                            
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>
                            <div class="col-md-4" >
                                <div class="form-group">
                                    <label for="password-confirm" class="col-md-8 col-form-label text-md-center">{{ __('Confirmar Password') }}</label>                            
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div> 
                        </div>  

                        <div class="row">
                            
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label for="base"  class="col-sm-12 col-form-label">Base</label>
                                    <select class="form-control" id="base" required>
                                    <option value="">Seleccione la Base a la cual se conectara</option>
                                    <option value="ZKAccess">Oficina Central</option>
                                    <option value="gomezmaza">Gomez Maza</option>
                                    <option value="bancodesangre">Banco de sangre</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div> 
                                                
                           <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="btnregister" onclick="register_user()">Guardar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                              </div>
                           </div>

               
                </form>  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
    @parent
    <script src="js/modulos/registrar/registrar.js"></script> 
     
   
    
@stop
