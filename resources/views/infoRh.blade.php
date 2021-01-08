
<!DOCTYPE html>
<html>
<head>
	<title>@yield('title',"SISTEMATIZACIÓN ASISTENCIA")</title>
	<!--<link rel="stylesheet" href="../css/app.css">-->
	<link rel="stylesheet" href="../libs/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../libs/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/hover-table.css">
	<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="../js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="../js/popper.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/mdb.min.js"></script>
	<!--<script type="text/javascript" src="../js/mbd.js"></script>-->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="../easyautocomplete/jquery.easy-autocomplete.js"></script>

	<link rel="stylesheet" href="../easyautocomplete/easy-autocomplete.css">  
    <link rel="stylesheet" href="../easyautocomplete/easy-autocomplete.themes.css">
	
	<script type="text/javascript" src="../js/modulos/empleados/lista.js"></script>  
	<script type="text/javascript" src="../js/rh/rh.js"></script>

	<script src="../libs/moment/moment.min.js"></script> 
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
	
	<style>
		.active a{
			color: red;
			text-decoration: none;
		}
	</style>
</head>
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
								<div class="col-md-6">
									<button type="button" onclick="filtrar_checadas()" id="buscar" class="form-control btn btn-primary">
										{{ __('Buscar') }}
									</button>
								</div>

								  <!-- <div class="col-md-6">
									<button type="button" onclick="cargar_formato()" class="form-control btn btn-primary">
											{{ __('Generar Justificante') }}
									</button>
								</div>   -->
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
						<td colspan="4"><strong>Resumen </strong></td>
						
					
					</tr>
 
					<tr>
						<td ><strong>ID: <i id="Ident"></i></strong> </td>
						
						
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
						<td colspan="3">Vacaciones 2020 Invierno</td>
						<td id="Vacaciones_2020_Invierno"></td>
					</tr>
					<tr>
						<td colspan="3">Vacaciones 2020 Primavera-Verano</td>
						<td id="Vacaciones_2020_Primavera_Verano"></td>
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
	<!--<h4 class="card-title" style="color:red">En la leyenda <strong>SIN REGISTRO</strong> probablemente <strong>no registro ó no ha comprobado una incidencia</strong></h4>-->

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
<a id="modal" data-toggle="modal" data-target="#modal_aviso"></a>
  
  <!-- Modal -->
  <div class="modal fade" id="modal_aviso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="exampleModalLabel">Aviso</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
		<p><strong>
		  Sus datos no se encuantran registrados, acudir al Depto. de sistematización y Nomina.
		  </strong>
		</p>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal">Cerrar</button>
		</div>
	  </div>
	</div>
  </div>


<a id="justificante" data-toggle="modal" data-target="#modal_justificante"></a>

<div class="modal fade bd-example-modal-xl" id="modal_justificante" tabindex="-1" role="dialog" >
<!-- aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" -->
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="exampleModalLabel">Justificante de Inasistencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombre_empleado" class="col-form-label"><strong>SE HACE DEL CONOCIMIENTO QUE  EL  ( LA )  C. :</strong></label>
						<input type="text" class="form-control" id="nombre_empleado" readonly>
						<input type="hidden" id="userid" name="userid">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="funcion" class="col-form-label"><strong>FUNCIÓN:</strong></label>
                                <input type="text" class="form-control" id="funcion" >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horario_emp" class="col-form-label"><strong>HORARIO:</strong></label>
                                <input type="text" class="form-control" id="horario_emp" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion_departamento" class="col-form-label"><strong>DIRECCIÓN O SUBDIRECCIÓN:</strong></label>
                                <input type="text" class="form-control" id="direccion_departamento">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="departamento" class="col-form-label"><strong>DEPARTAMENTO:</strong></label>
                                <input type="text" class="form-control" id="departamento">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_tarjeta" class="col-form-label"><strong>ID DE CONTROL:</strong></label>
                                <input type="text" class="form-control" id="no_tarjeta" readonly>
                            </div>
						</div> 
						<div class="col-md-6" >
							<div class="form-group">
							<label for="incidencia" class="col-form-label"><strong>TIPO DE INCIDENCIA</strong></label>
								<!-- <input id="horario">    --> 
								<input id="incidencia"  type="text" style="outline: none;">
								<input type="hidden" id="code_in" name="code_in" onchange="sel_inci(this.value)" required>  
							</div>
							<div id="divmsg" style="display:visible" class="alert-primary" role="alert">
							</div>
						</div>

                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="incidencia" class="col-form-label"><strong>PRESENTA LA SIGUIENTE INCIDENCIA:</strong></label>
                                <input type="text" class="form-control" id="incide">
                            </div>
                        </div> -->
                    </div>
					<div class="row">
						
					</div>
            
					<div class="row">
						<div class="col-md-6" >
							<div class="form-group">
								<label for="f_ini" class="col-sm-3 col-form-label"><strong>DESDE</strong></label>
								<input type="datetime-local" class="form-control" id="f_ini" name="f_ini" >
							</div>
						</div>
						<div class="col-md-6" >
							<div class="form-group">
								<label for="f_fin" class="col-sm-3 col-form-label"><strong>HASTA</strong></label>
								<input type="datetime-local" class="form-control" id="f_fin" name="f_fin" >
							</div>
						</div>
						
					</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="documentos"><strong>DOCUMENTOS QUE SE ANEXAN:</strong></label>
                                <textarea class="form-control" id="documentos" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="observaciones"><strong>OBSERVACIONES:</strong></label>
                                <textarea class="form-control" id="observaciones" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fecha" class="col-form-label"><strong>TUXTLA GUTIERREZ, CHIAPAS. A:</strong></label>
                                <input type="date" class="form-control" id="fecha">
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="att"><strong>A T E N T A M E N T E:</strong></label>
                                <textarea class="form-control" id="att" rows="4"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="autorizo"><strong>A U T O R I Z O :</strong></label>
                                <textarea class="form-control" id="autorizo" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
						<button type="button" class="btn btn-primary" id="save_in_emp" data-dismiss="modal" onclick="guardar_incidencia()">Guardar</button>
						<button type="button" class="btn btn-secondary" id="cerrar" data-dismiss="modal">Cerrar</button>
                        
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
  