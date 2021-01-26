@extends('layouts.app')

@section('title', 'Login Asistencia')

@section('estilos')
    @parent
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@stop
@section('content')
<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="js/modulos/login/login.js"></script> 
<div class="main">
    <div class="container">
        <center>
            <div class="middle">
                <div id="login">
                    <form  method="post">
                    <div class="alert alert-danger" role="alert" id="mensaje_error">
                        <b>ERROR</b>
                        <br>
                        Ingrese nuevamente los datos de acceso, por favor.
                    </div>
                    @csrf <!-- {{ csrf_field() }} -->
                        <fieldset class="clearfix">
                            <p ><span class="fa fa-user"></span><input type="text" id='usuario' name='email' Placeholder="Usuario" required></p> <!-- JS because of IE support; better: placeholder="Username" -->
                            <p><span class="fa fa-lock"></span><input type="password" id='contrasenia' name='password' Placeholder="Contraseña" required></p> <!-- JS because of IE support; better: placeholder="Password" -->
                            <div>
                                <!--<span style="width:48%; text-align:left;  display: inline-block;"><a class="small-text" href="#">Olvidaste tu contraseña?</a></span>-->
                                <span style="width:50%; text-align:right;  display: inline-block;">
                                <!--<input type="submit" value="Ingresar"></span>-->
                                <button type="button" class="btn btn-dark" id="btn_ingresar" onclick="ingresar()">INGRESAR</button>
                            </div>
                        </fieldset>
                        <div class="clearfix"></div>
                    </form>
                <div class="clearfix"></div>

                </div> <!-- end login -->
                <div class="logo"><i class="fa fa-male fa-2x"></i>
                    <div class="clearfix"></div>
                </div>
                    
            </div>
        </center>
    </div>

</div>
@endsection