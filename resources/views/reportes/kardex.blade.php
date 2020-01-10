<!DOCTYPE html>
<html>
<head>
	<title>Kardex</title>
	<!--<link rel="stylesheet" href="../css/app.css">-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
	integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	
	<script type="text/javascript" src="../js/rh/kardex.js"></script>

    
	<!-- <link rel="stylesheet" href="../libs/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../libs/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/hover-table.css">
	<script type="text/javascript" src="../js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="../js/popper.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/mdb.min.js"></script>-->

	
</head>
<body>
		<hr>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="page-header">
						{{ Form::open (['route' => 'kardex', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
							<div class="form-group">
								{{ Form::text ('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre...'])}}
							</div>
							<div class="form-group">
								<button type="submit" class ="btn btn-default">
									<span class="input-group-addon"id="search">
									<span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
								</button>
							</div>
						{{ Form::close() }}
					</div>
				</div>
			
			</div>
		
		</div>
        

	<hr>
		<div class="row">
			<div class='col-md-10'>
				<table class="table table-hover table-striped">            
					<tr>
						<th>NumEmpleado</th>
						<th>Nombre</th>
						<th>RFC</th> 
						<th>RC</th>                    
					</tr>         
					@foreach ($empleados as $empleado)
						<tr>
						<td>{{ $empleado->Badgenumber}}</td>
						<td>{{ $empleado->Name}}</td>

						<td>{{ $empleado->TITLE}}</td>

						<td>
							<button type="button" onclick="filtrar_checadas('{{ $empleado->TITLE}}')" class="form-control btn btn-primary">
								{{ __('Kardex') }}
							</button>
						</td>
						
						</tr>
				
					@endforeach
					
				</table>
				{{ $empleados->render() }}
			</div>
		</div>

</body>
</html>
  
  
