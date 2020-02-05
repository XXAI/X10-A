<!DOCTYPE html>
<html>
<head>
	<title>Kardex</title>
	<link rel="stylesheet" href="../libs/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../libs/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/hover-table.css">
	<script type="text/javascript" src="../js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="../js/popper.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/mdb.min.js"></script>
	<script type="text/javascript" src="../js/rh/kardex-id.js"></script>

	
</head>
<body>
<body>
	<div class="logo_cabecera">
		<div class="row">
			<div class="col-6">
				<img src="../images/salud.png" alt="Responsive image">
			</div>
			<div class="col-6" style="text-align:right">
				<img src="../images/chiapas.png" alt="Responsive image">
			</div>
		</div>
	</div>	
	<div id="contenido" class="card-body contenido_blade">
		<div class="row">
			<div class="card testimonial-card col-xl-4 col-lg-5 col-md-12 ">
				<br>
				<div class="avatar mx-auto white" style='text-align:center'><img id="foto" alt="trabajador" class="rounded-circle img-fluid" width="70%"></div>
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
							<div class="col-md-5 col-5">
								<p><strong>CLUES: </strong></p>
							</div>
							<div class="col-md-7 col-7">
								<p><i id="Clue"></i></p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-5 col-5">
								<p><strong>Tipo de Sangre: </strong></p>
							</div>
							<div class="col-md-7 col-7">
								<p><i id="TipoSangre"></i></p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-5 col-5">
								<p><strong>CURP: </strong></p>
							</div>
							<div class="col-md-7 col-7">
								<p><i id="Curp"></i></p>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-5 col-5">
								<p><strong>RFC: </strong></p>					
							</div>
							<div class="col-md-7 col-7">
								<p><i id="Rfc"></i></p>					
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-8 col-lg-7 col-md-12">
				<table id="resumen" class="table table-striped">
					<thead>
						<!--<tr>
							<th colspan="2"><img src="../images/salud.png" class="img-fluid flex" alt="Responsive image" width="20%"></th>
							<th colspan="2" style="text-align:right"><img src="../images/chiapas.png" class="img-fluid flex" alt="Responsive image" width="20%"></th>
						</tr>-->
						<tr>
							<th colspan="5">
								<br>
								<div class="row">
									<div class="col-sm-6">
										<label for="fecha_inicio">Fecha Inicio:</label>
										<input type="date" class="form-control" id="inicio" min='2019-10-01' name="fecha_inicio" value="">
									</div>

									<div class="col-sm-6">
										<label for="fecha_inicio">Fecha Fin:</label>
										<input type="date" class="form-control" id="fin" name="fecha_fin"  value="" max= "{{date('Y-m-d')}}">
									</div>

								</div>
								<br>
								<div class="row">
									<div class="col-md-12">
										<button type="button" onclick="filtrar_checadas()" class="form-control btn btn-primary">
											{{ __('Buscar') }}
										</button>
									</div>
								</div>
							</th>
							
						</tr>	
						<!--<tr>
							{{-- <th>FECHA</th>
							<th>HORA ENTRADA</th>
							<th>HORA SALIDA</th>
							<th>JUSTIFICANTE</th> --}}
							<td colspan="4" style="color:red">
								<strong style="align=center">
									Algunas Reglas del resumen aún no estan aplicadas.
								</strong>
							</td>
						</tr>-->
					</thead>
					<tbody>
						<tr>
							<td colspan="4"><strong>Resumen</strong></td>
						</tr>
						<tr>
							<td colspan="3">Día Economico</td>
							<td id="Día_Económico"></td>
						</tr>
						<tr>
							<td colspan="3">Faltas Totales</td>
							<td id="Falta"></td>
						</tr>
						<tr>
							<td colspan="3">Omision Entrada</td>
							<td id="Omisión_Entrada"></td>
						</tr>
						<tr>
							<td colspan="3">Omision Salida</td>
							<td id="Omisión_Salida"></td>
						</tr>
						<tr>
							<td colspan="3">Onomastico</td>
							<td id="Onomástico"></td>
						</tr>
						<tr>
							<td colspan="3">Pase de Salida (Hrs.)</td>
							<td id="Pase_Salida"></td>
						</tr>
						<tr>
							<td colspan="3">Retardo Mayor</td>
							<td id="Retardo_Mayor"></td>
						</tr>
						<tr>
							<td colspan="3">Retardo Menor</td>
							<td id="Retardo_Menor"></td>
						</tr>
						<tr>
							<td colspan="3">Vacaciones 2018 Invierno</td>
							<td id="Vacaciones_2018_Invierno"></td>
						</tr>
						<tr>
							<td colspan="3">Vacaciones 2018 Primavera-Verano</td>
							<td id="Vacaciones_2018_Primavera_Verano"></td>
						</tr>
						<tr>
							<td colspan="3">Vacaciones 2019 Invierno</td>
							<td id="Vacaciones_2019_Invierno"></td>
						</tr>
						<tr>
							<td colspan="3">Vacaciones 2019 Primavera-Verano</td>
							<td id="Vacaciones_2019_Primavera_Verano"></td>
						</tr>
						<tr>
							<td colspan="3">Vacaciones Extra Ordinarias</td>
							<td id="Vacaciones_Extra_Ordinarias"></td>
						</tr>
						<tr>
							<td colspan="3">Vacaciones Mediano Riesgo</td>
							<td id="Vacaciones_Mediano_Riesgo"></td>
						</tr>
					</tbody>
					
				</table>
			</div>
		</div>
	</div>

	<section id="checadas" class="card">
	
		<table id="tabla_checadas" class="table table-striped">
			<thead class="black white-text">
				<tr>
					<th>Día</th>
					<th>Fecha</th>
					<th>Hora Entrada</th>
					<th>Hora Salida</th>
					<th>Justificado</th>
				</tr>
			</thead>
			<tbody id="datos_filtros_checadas">
				
			</tbody>
		</table>
	</section>

</body>
		

</body>
