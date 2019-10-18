@extends('layout')
@section('title','Home')
@section('content')@section('content')

<div class="card-body">

    <form class="text-center border border-light p-5" method="GET" action="">
		@csrf

        <div class="container">
            <div class="row">
                <div class="col-md-3 col-4">
                    <br>
                    <img src="../images/salud.png" class="img-fluid flex" alt="Responsive image">
                </div>
                <div class="col-md-6 col-4">

                    <i class="fa fa-clock-o fa-5x" aria-hidden="true"></i>
                    <p><strong>Reloj Checador</strong></p>

                </div>

                <div class="col-md-3 col-4">
                    <img src="../images/chiapas.png" class="img-fluid flex" alt="Responsive image">
                </div>
            </div>
        </div>
        <br>
        <!-- ID -->
        <input id="id" class="form-control mb-4" type="text" name="id" value="{{ old('id') }}" required autocomplete="id" autofocus placeholder="ID">

        <!-- Rfc -->
        <input id="rfc" type="text" class="form-control" name="rfc" value="{{ $rfc }}" required autocomplete="rfc" placeholder="RFC">

        <br>
        <input type="datetime-local" placeholder="Fecha de Inicio" id="inicio" class="form-control datepicker" value="2019-10-01T00:01">
        <br>
        <input type="datetime-local" placeholder="Fecha de Inicio" id="fin" class="form-control datepicker" value="2019-10-01T23:59">

        <small id="defaultRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
			Consulta tu horario de entrada y salida
		</small>

        {{-- <div class="form-group row mb-0">
            <button class="btn btn-unique my-4 btn-block" type="button" class="btn btn-primary">
                {{ __('Buscar') }}
            </button>
            @if (Route::has('rfc.request'))
            <a class="btn btn-link" href="{{ route('home.index') }}">
				{{ __('Forgot Your rfc?') }}
			</a> @endif
        </div> --}}
	</form>


	{{-- @isset($asistencia[0])
    <div class="container-fluid">
        <h3>ID : {{$asistencia[0]->Badgenumber}} Nombre : {{$asistencia[0]->Name}} RFC : {{$asistencia[0]->TITLE}}  </h3>
        <h3>Horario de {{$asistencia[0]->hentrada}} A {{$asistencia[0]->hsalida}} </h3>

    </div>
    @endisset --}}

</div>

<div class="card testimonial-card">
	<br>
	<!--Avatar-->
	<div class="avatar mx-auto white"><img id="foto"
		alt="img" class="rounded-circle img-fluid">
	</div>

	<div class="card-body">

		<div class="row">
			<div class="col-12 text-center">
				<h1 id="Nombre" class="card-title mt-3"></h1>
				<h4 id="Adscripcion_Area" class="card-title mt-3"></h4>
				<h4 id="nombre" class="card-title mt-3"></h4>
				<h4 id="Direccion" class="card-title mt-3"></h4>
				<h4 id="Adscripcion_Area" class="card-title mt-3"></h4>
			</div>
		</div>
		<hr>
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-4">
					<p><strong>Tipo de Sangre: </strong><i id="TipoSangre"></i></p>
				</div>

				<div class="col-md-6 col-4">
					<p><strong>CURP: </strong><i id="Curp"></i></p>
				</div>

				<div class="col-md-3 col-4">
					<p><strong>RFC: </strong><i id="Rfc"></i></p>					
				</div>
			</div>
		</div>

	</div>

</div>

</section>

	{{-- <table class="table table-dark" >
		<thead>
   		 <tr>
			<th>Fecha</th>
		    <th>Hora Entrada</th>
		    <th>CalEnt</th>
		    <th>Hora Salida</th>
		    <th>CalSal</th>
		    <th>dif</th>
		 </tr>
  		</thead>

			@forelse ($asistencia as $asistenciaItem)
			<tr>
			<!--$fecha=$asistenciaItem->fecha;-->
			<td>{{ $asistenciaItem->fecha}}</td>
			<td>{{ $asistenciaItem->centrada}}</td>
			@if($asistenciaItem->difent >= -30 and $asistenciaItem->difent <= 15 )

			<td> bien </td>
			@else
				<td> mal </td>
			@endif
			<td>{{ $asistenciaItem->csalida}}</td>

			@if($asistenciaItem->difsal >= 0 and $asistenciaItem->difsal <= 30 )

			<td> bien </td>
			@else
				<td> mal </td>
			@endif
			<td>{{$asistenciaItem->difsal}}</td>

			</tr>
			@empty

			@endforelse
	</table> --}}


@endsection