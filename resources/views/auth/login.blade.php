@extends('layouts.app')

@section('content')

<div class="main">
    <div class="container">
        <center>
            <div class="middle">
                <div id="login">
                    <form  method="post" action="./api/login">
                        <fieldset class="clearfix">
                            <p ><span class="fa fa-user"></span><input type="text" id='usuario' name='email' Placeholder="Usuario" required></p> <!-- JS because of IE support; better: placeholder="Username" -->
                            <p><span class="fa fa-lock"></span><input type="password" id='contrasenia' name='password' Placeholder="Contraseña" required></p> <!-- JS because of IE support; better: placeholder="Password" -->
                            <div>
                                <!--<span style="width:48%; text-align:left;  display: inline-block;"><a class="small-text" href="#">Olvidaste tu contraseña?</a></span>-->
                                <span style="width:50%; text-align:right;  display: inline-block;"><input type="submit" value="Ingresar"></span>
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
