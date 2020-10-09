function ingresar()
{
    datos = "email="+$("#usuario").val()+"&password="+$("#contrasenia").val();
    jQuery.ajax({
        data: datos,//{'email': $("#usuario").val() , 'password': $("#contrasenia").val()},
        type: "POST",
        dataType: "json",
        url: "./api/login",
  }).done(function( data, textStatus, jqXHR ) {
        window.location.replace("./dashboard");
        //console.log(data);
  }).fail(function( jqXHR, textStatus, errorThrown ) {
        if ( console && console.log ) {
            console.log(jqXHR);
        }
  });
}