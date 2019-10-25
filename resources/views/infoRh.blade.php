@extends('layout')
@section('title','Home')
@section('content')@section('content')

<div id="contenido" class="card-body">

	<div class="row">
		<div class="card testimonial-card col-md-4">
			<br>
			<!--Avatar-->
			<div class="avatar mx-auto white" style='text-align:center'><img id="foto" alt="trabajador" class="rounded-circle img-fluid" width="70%"></div>
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
		<table id="resumen" class="table table-bordered">
			<thead>
				<tr>
					<th colspan="2"><img src="../images/salud.png" class="img-fluid flex" alt="Responsive image" width="20%"></th>
					<th colspan="2" style="text-align:right"><img src="../images/chiapas.png" class="img-fluid flex" alt="Responsive image" width="20%"></th>
				</tr>
				<tr>
					<th colspan="3">FILTRAR:
						<br>
						<div class="row">
							<div class="col-md-5 col-5">
								<input type="datetime-local" class="form-control" id="inicio" name="fecha_inicio" value="">
							</div>

							<div class="col-md-5 col-5">
								<input type="datetime-local" class="form-control" id="fin" name="fecha_fin"  value="">
							</div>

						</div>
					</th>
					<th colspan="2">
						<div class="col-md-2 col-2">
							<button type="button" onclick="filtrar_checadas()" class="btn btn-primary">
								{{ __('Buscar') }}
							</button>
						</div>
					</th>
				</tr>	
				<tr>
					{{-- <th>FECHA</th>
					<th>HORA ENTRADA</th>
					<th>HORA SALIDA</th>
					<th>JUSTIFICANTE</th> --}}
					<td colspan="4" style="color:red">
						<strong style="align=center">
							Algunas Reglas del resumen aún no estan aplicadas.
						</strong>
					</td>
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4"><strong>Resumen</strong></td>
				</tr>
				<tr>
					<td colspan="3">Día Economico</td>
					<td id="diaE"></td>
				</tr>
				<tr>
					<td colspan="3">Faltas Totales</td>
					<td id="falta"></td>
				</tr>
				<tr>
					<td colspan="3">Omision Entrada</td>
					<td id="oE"></td>
				</tr>
				<tr>
					<td colspan="3">Omision Salida</td>
					<td id="oS"></td>
				</tr>
				<tr>
					<td colspan="3">Onomastico</td>
					<td id="ono"></td>
				</tr>
				<tr>
					<td colspan="3">Pase de Salida</td>
					<td id="ps"></td>
				</tr>
				<tr>
					<td colspan="3">Retardo Menor</td>
					<td id="rm"></td>
				</tr>
				<tr>
					<td colspan="3">Retardo Mayor</td>
					<td id="rme"></td>
				</tr>
			</tfoot>
		</table>
		</div>	
	</div>
	</section>

	<div><br></div>

	<section id="checadas" class="card">
		<h4 class="card-title" style="color:red">En la leyenda <strong>SIN REGISTRO</strong> probablemente <strong>no registro ó no ha comprobado una incidencia</strong></h4>

		<table id="tabla_checadas" class="table table-dark">
			<thead class="black white-text">
				<tr>
					<th>N°</th>
					<th>Fecha</th>
					<th>Hora Entrada</th>
					<th>Hora Salida</th>
				</tr>
			</thead>
			<tbody id="datos_filtros_checadas">
			</tbody>
		</table>
	</section>

</div>
@endsection