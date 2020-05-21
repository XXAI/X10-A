<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @section('estilos')
        <!-- Fonts -->
       
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="{{ asset('libs/theme/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link href="{{ asset('libs/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
       
       <link rel="stylesheet" href="{{ asset('libs/theme/css/sb-admin-2.css') }}" > 
        <link rel="stylesheet" href="easyautocomplete/easy-autocomplete.css">  
        <link rel="stylesheet" href="easyautocomplete/easy-autocomplete.themes.css">
        <!-- <link rel="stylesheet" href="bootstrap4/css/bootstrap.min.css"> -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

      
        

        
    @show
    
    
</head>
<body id="page-top">
        @auth
            @include('menu.menu')
            
            @section('scripts')
                <!-- Scripts -->
               <!--  <script src="{{ asset('js/app.js') }}" defer></script>  -->
               
                <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
                
                <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
                
                <script src="easyautocomplete/jquery.easy-autocomplete.js"></script>
               
               <!--  <script src="bootstrap4/js/bootstrap.min.js"></script> -->
                <script src="js/modulos/login/login.js"></script> 
                <script src="libs/theme/vendor/chart.js/Chart.min.js"></script>  
                <script src="libs/theme/vendor/bootstrap/js/bootstrap.bundle.min.js"></script> 
                <script src="libs/theme/vendor/jquery-easing/jquery.easing.min.js"></script> 
                <script src="libs/theme/js/sb-admin-2.min.js"></script> 
                <script src="libs/moment/moment.min.js"></script> 
                <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            
                
                
                

                <!--<script src="libs/theme/js/demo/chart-area-demo.js"></script> 
                <script src="libs/theme/js/demo/chart-bar-demo.js"></script> 
                <script src="libs/theme/js/demo/chart-pie-demo.js"></script> --> 
                
                
                
            @show

        @else
            @yield('content')
            
        @endguest

        

</body>
</html>

