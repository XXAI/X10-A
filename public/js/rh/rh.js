$(document).ready(function(){

      // jQuery('#rfc').change(function() {

      var rfc = $("#rfc").val();
      var urlrh = "http://credencializacion.saludchiapas.gob.mx/ConsultaRhPersonal.php?buscar="+rfc;

      if(rfc !=''){

            jQuery.ajax({
                  data: {},
                  type: "GET",
                  dataType: "json",
                  url: urlrh,
            }).done(function( data, textStatus, jqXHR ) {
                  console.log(data); http://credencializacion.saludchiapas.gob.mx/images/credenciales/1858.jpeg

                  $("#Nombre").text(data[0].Nombre);
                  $("#Adscripcion_Area").text(data[0].DesPuesto);  
                  $("#nombre").text(data[0].nombre);
                  $("#Direccion").text(data[0].Direccion);
                  $("#Adscripcion_Area").text(data[0].Adscripcion_Area);
                  $("#TipoSangre").text(data[0].TipoSangre);
                  $("#Curp").text(data[0].Curp);
                  $("#Rfc").text(data[0].Rfc);


                  $("#foto").attr("src","http://credencializacion.saludchiapas.gob.mx/images/credenciales/"+data[0].id+".jpeg");

                  //console.log("http://credencializacion.saludchiapas.gob.mx/images/credenciales/"+data[0].id+".jpeg");

            }).fail(function( jqXHR, textStatus, errorThrown ) {
                  if ( console && console.log ) {
                  alert( "Error en la carga de Datos, asista a Sistematización: " +  textStatus);
                  }
            });

      }

});

