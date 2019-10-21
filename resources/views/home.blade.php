@extends('layout')
@section('title','Home')
@section('content')@section('content')
<div class="card-body">

    


</div>
<div class="row">
	<div class="card testimonial-card col-md-4">
		<br>
		<!--Avatar-->
		<div class="avatar mx-auto white" style='text-align:center'><img id="foto" alt="trabajador" class="rounded-circle img-fluid" width="50%"></div>
		<div class="card-body">
			<div class="row">
				<div class="col-12 text-center">
					<h1 id="Nombre" class="card-title mt-3"></h1>
					<h4 id="Adscripcion_Area" class="card-title mt-3"></h4>
					<h4 id="nombre" class="card-title mt-3"></h4>
					<h4 id="Direccion" class="card-title mt-3"></h4>
					<h4 id="Adscripcion_Area" class="card-title mt-3"></h4>
					<div class="col-md-5 col-5">
						<p><strong>CLUES: </strong><!--<i id="Rfc"></i>--></p>					
					</div>
					<div class="col-md-7 col-7">
						<p><!--<strong>RFC: </strong>--><i id="Clue"></i></p>					
					</div>
				</div>
			</div>
			<hr>
			<div class="container">
				<div class="row">
					<div class="col-md-5 col-5">
						<p><strong>Tipo de Sangre: </strong><!--<i id="TipoSangre"></i>--></p>
					</div>
					<div class="col-md-7 col-7">
						<p><!--<strong>Tipo de Sangre: </strong>--><i id="TipoSangre"></i></p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5 col-5">
						<p><strong>CURP: </strong><!--<i id="Curp"></i>--></p>
					</div>
					<div class="col-md-7 col-7">
						<p><!--<strong>CURP: </strong>--><i id="Curp"></i></p>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-5 col-5">
						<p><strong>RFC: </strong><!--<i id="Rfc"></i>--></p>					
					</div>
					<div class="col-md-7 col-7">
						<p><!--<strong>RFC: </strong>--><i id="Rfc"></i></p>					
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		
		<table class="table table-bordered">
			<thead>
				<tr>
					<th colspan="2"><img src="../images/salud.png" class="img-fluid flex" alt="Responsive image" width="20%"></th>
					<th colspan="2" style="text-align:right"><img src="../images/chiapas.png" class="img-fluid flex" alt="Responsive image" width="20%"></th>
				</tr>
				<tr>
					<th colspan="2">FILTRAR:
						<br>
						<div class="row">
							<div class="col-md-5 col-5">
								<select class="browser-default custom-select">
									<option selected>Mes</option>
									<option value="1">Enero</option>
									<option value="2">Febrero</option>
									<option value="3">Marzo</option>
									<option value="4">Abril</option>
									<option value="5">Mayo</option>
									<option value="6">Junio</option>
									<option value="7">Julio</option>
									<option value="8">Agosto</option>
									<option value="9">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
							<div class="col-md-7 col-7">
								<select class="browser-default custom-select" name="anio">
								</select>
							</div>
						</div>
					</th>
					<th colspan="2">
					</th>
				</tr>	
				<tr>
					<th>FECHA</th>
					<th>HORA ENTRADA</th>
					<th>HORA SALIDA</th>
					<th>JUSTIFICANTE</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4">RESUMEN</td>
				</tr>
				<tr>
					<td colspan="3">OMISIONES DE ENTRADA</td>
					<td>0</td>
				</tr>
				<tr>
					<td colspan="3">OMISIONES DE SALIDA</td>
					<td>0</td>
				</tr>
				<tr>
					<td colspan="3">SALIDA TEMPRANO</td>
					<td>0 (TIEMPO)</td>
				</tr>
				<tr>
					<td colspan="3">FALTAS ACUMULADAS</td>
					<td>0</td>
				</tr>
				<tr>
					<td colspan="3">FALTAS TOTALES</td>
					<td>0</td>
				</tr>
				<tr>
					<td colspan="3">DIAS ECONÓMICOS</td>
					<td>0</td>
				</tr>
				<tr>
					<td colspan="3">DÍAS FESTIVOS</td>
					<td>0</td>
				</tr>
				<tr>
					<td colspan="3">DÍAS LABORABLES /DÍAS LABORADOS</td>
					<td>0</td>
				</tr>
			</tfoot>
		</table>
	</div>	
</div>
</section>
<div><br></div>
<section class="card">
	<table class="table table-striped">
		<thead>
			<tr>
			<th scope="col">Fecha</th>
			<th scope="col">Hora Entrada</th>
			<th scope="col">Hora Salida</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th scope="row">1</th>
				<td>Mark</td>
				<td>Otto</td>
			</tr>
			<tr>
				<th scope="row">2</th>
				<td>Jacob</td>
				<td>Thornton</td>
			</tr>
		</tbody>
	</table>
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