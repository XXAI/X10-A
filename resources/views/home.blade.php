@extends('layout')

@section('title','Home')


@section('content')@section('content')


	<div class="card-body">
		<form method="GET" action="">
	        @csrf

		        <div class="form-group row">
	                <label for="id" class="col-md-4 col-form-label text-md-right">Id</label>
	                <div class="col-md-6">
	                    <input id="id" type="text"  name="id" value="{{ old('id') }}" required autocomplete="id" autofocus>
	                </div>
	            </div>
	            <div class="form-group row">
	                <label for="rfc" class="col-md-4 col-form-label text-md-right">RFC</label>
	                <div class="col-md-6">
	                    <input id="rfc" type="text"  name="rfc" value="{{ old('rfc') }}" required autocomplete="rfc" autofocus>
	                </div>
	            </div>

	            <div class="form-group row">
	                 <label for="start" class="col-md-4 col-form-label text-md-right">Fecha Inicio:</label>
	                 <div class="col-md-6">
	                        <input type="datetime-local" id="start" name="trip-start" value="2019-10-01T00:01">
	                  </div>
	            </div>
	           <div class="form-group row">
	             <label for="fin" class="col-md-4 col-form-label text-md-right">Fecha Fin:</label>
	             <div class="col-md-6">
	                    <input type="datetime-local" id="fin" name="trip-fin" value="2019-10-01T23:59">

	             </div>
	           </div>
	           <div class="form-group row mb-0">
	                    <div class="col-md-8 offset-md-4">
	                        <button type="submit" class="btn btn-primary">
	                            {{ __('Buscar') }}
	                        </button>
	                        @if (Route::has('rfc.request'))
	                            <a class="btn btn-link" href="{{ route('home.index') }}">
	                                {{ __('Forgot Your rfc?') }}
	                            </a>
	                        @endif
	                    </div>
	           </div>


		</form>
	@isset($asistencia[0])
	<div class="container-fluid">
	<h3>ID : {{$asistencia[0]->Badgenumber}} Nombre : {{$asistencia[0]->Name}} RFC : {{$asistencia[0]->TITLE}}  </h3>
	<h3>Horario de {{$asistencia[0]->hentrada}} A {{$asistencia[0]->hsalida}} </h3>

	</div>
	@endisset


	</div>
	<table class="table table-dark" >
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
	</table>





@endsection