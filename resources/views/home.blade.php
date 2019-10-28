<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">



	<div class="card-body">
		<form method="GET" action="">
	        @csrf

		        <div class="form-group row">
	                <label for="id" class="col-md-4 col-form-label text-md-right">Id</label>
	                <div class="col-md-6">
	                    <input id="id" type="text"  name="id" value="{{ old('id')}}" required autocomplete="id" autofocus>
	                </div>
	            </div>
	            <div class="form-group row">
	                <label for="rfc" class="col-md-4 col-form-label text-md-right">RFC</label>
	                <div class="col-md-6">
	                    <input id="rfc" type="text"  name="rfc" value="{{ old('rfc')}}" required autocomplete="rfc" autofocus>
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
	                    <input type="datetime-local" id="fin" name="trip-fin" >

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
	</div>

	<table class="table table-dark" >
		<!--thead>
			@forelse($resumen as $resumenx)
			<h1>{{$resumenx['vac2019_1']}}<h1>
			@empty

		@endforelse
   		 <tr>
			<th>Fecha</th>
		    <th>Hora Entrada</th>
		    <th>Hora Salida</th>

		 </tr>
  		</thead-->
		
			@forelse($resumen as $resumenx)
			<h4>{{"Faltas: ".$resumenx['Falta']}}</h4>
			<h4>{{"Retardos Mayores: ".$resumenx['Retardo Mayor']}}</h4>
			<h4>{{"Retardos Menores: ".$resumenx['Retardo Menor']}}</h4>
			<h4>{{"Pases de Salida(Horas): ".$resumenx['Pase de Salida']}}</h4>
			<h4>{{"Dia(s) Economico(s): ".$resumenx['Dia Economico']}}</h4>
			<h4>{{"Vacaciones Primavera 2018 : ".$resumenx['Vacaciones 2018 Primavera-Verano']}}</h4>
			<h4>{{"Vacaciones Invierno 2018 : ".$resumenx['Vacaciones 2018 Invierno']}}</h4>
			<h4>{{"Vacaciones Primavera 2019 : ".$resumenx['Vacaciones 2019 Primavera-Verano']}}</h4>
			<h4>{{"Vacaciones Invierno 2019 : ".$resumenx['Vacaciones 2019 Invierno']}}</h4>
			@empty

			@endforelse


		@forelse ($asistencia as $asistenciax)
			<tr>
				<td>{{$asistenciax['fecha']}}</td>
				<td>{{$asistenciax['checado_entrada']}}</td>
				<td>{{$asistenciax['checado_salida']}}</td>

			</tr>
			@empty

		@endforelse

	</table>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
