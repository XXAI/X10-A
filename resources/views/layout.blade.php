<!DOCTYPE html>
<html>
<head>
	<title>@yield('title',"probando")</title>
	<link rel="stylesheet" href="../css/app.css">
	<script type="text/javascript" src="../js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="../js/popper.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/mdb.min.js"></script>
	<script type="text/javascript" src="../js/mbd.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript" src="../js/rh/rh.js"></script>

	<style>
		.active a{
			color: red;
			text-decoration: none;
		}
	</style>
</head>
<body>
	@include('partials.nav')
	@include('partials.session-status')
	@yield('content')

</body>
</html>