<!DOCTYPE html>
<html>
<head>
	<title>Kardex</title>
	<!--<link rel="stylesheet" href="../css/app.css">-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
	integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
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
		<div class="row">
			<div class='col-md-10'>
				<table class="table table-hover table-striped">            
					<tr>
						<th>NumEmpleado</th>
						<th>Nombre</th>
						<th>RFC</th>                    
					</tr>         
					<!--@foreach ($empleados as $empleado)
						<tr>
						<td>{{ $empleado->Badgenumber}}</td>
						<td>{{ $empleado->Name}}</td>

						<td>{{ $empleado->TITLE}}</td>

						<td>
							<form action="{{ route('repkardex.show',$empleado->USERID) }}" method="POST">
			
								
			
								@csrf
								@method('DELETE')
				
								<button type="submit" class="btn btn-danger">Delete</button>
							</form>
						</td>
						<td><input class="form-check-input" type="checkbox" id="gridCheck1"></td>
						</tr>
				
					@endforeach-->
					
				</table>
			<!--	{{ $empleados->render() }} -->
			</div>
		</div>

</body>
</html>
  
  
