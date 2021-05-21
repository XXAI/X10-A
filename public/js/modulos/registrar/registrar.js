$(document).ready(function()
{
  
      $("#mensaje_error").hide();
});

function ingresar()
{    
    datos = "email="+$("#usuario").val()+"&password="+$("#contrasenia").val();
    //console.log(datos);
    $("#btn_ingresar").html("<i class='fa fa-spin fa-refresh'></i> CARGANDO");
    $("#btn_ingresar").prop( "disabled", true );
    jQuery.ajax({
        data: datos,//{'email': $("#usuario").val() , 'password': $("#contrasenia").val()},
        type: "POST",
        dataType: "json",
        url: "./api/login",
  }).done(function( data, textStatus, jqXHR ) {
      ingreso = data.datos;
      console.log(data);
      localStorage.setItem('sw_id', ingreso.id);  
      localStorage.setItem('sw_alias', ingreso.alias);  
      localStorage.setItem('sw_is_superuser', ingreso.is_superuser);  
      localStorage.setItem('sw_nombre', ingreso.nombre+" "+ingreso.apellido_paterno+" "+ingreso.apellido_materno);  
      window.location.replace("./dashboard");
        //console.log(data);
  }).fail(function( jqXHR, textStatus, errorThrown ) {
      $("#mensaje_error").show();
      $("#btn_ingresar").prop( "disabled", false );
      $("#btn_ingresar").html("INGRESAR");
        if ( console && console.log ) {
            console.log(jqXHR);
        }
  });
}

function register_user(){
alert("hola mundo mundial");
}