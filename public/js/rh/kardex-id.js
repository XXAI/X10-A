var dato;
var inicio;
var fin;

$(document).ready(function(){

    dato = getParameterByName();
    inicio = $("#inicio").val();
    fin = $("#fin").val();

    var urlrh = "http://credencializacion.saludchiapas.gob.mx/ConsultaRhPersonalRFC.php";
    
    cargar_dato(dato, urlrh);
    $("#datos_filtros_checadas").html("<tr><td colspan='5'><i class='fa fa-refresh fa-spin'></i> Cargando, Espere un momento por favor</td></tr>");
    
});

function cargar_dato(dato, urlrh)
{
      jQuery.ajax({
            data: {'buscar': dato},
            type: "GET",
            dataType: "json",
            url: urlrh,
      }).done(function( data, textStatus, jqXHR ) {
            datos_credencializacion = data[0];
            cargar_blade_credencializacion();
            //cargar_datos_checadas(urlchecadas);

      }).fail(function( jqXHR, textStatus, errorThrown ) {
            if ( console && console.log ) {

               alert( "Error en la carga de Datos, acuda a Sistematización y Credencialización: " + " " +  textStatus);
               window.location.replace("http://induccion.saludchiapas.gob.mx/");
            }
      });

      for(var i=2019; i < 2030; i++)
      {
            $("select[name=anio]").append(new Option(i,i));
      }
}

function cargar_blade_credencializacion()
{ 

      $("#Nombre").text(datos_credencializacion.Nombre);
      $("#Adscripcion_Area").text(datos_credencializacion.DesPuesto);  
      $("#codigo").text(datos_credencializacion.codTab);
      $("#Direccion").text(datos_credencializacion.Direccion);
      $("#Adscripcion_Area").text(datos_credencializacion.Adscripcion_Area);
      $("#TipoSangre").text(datos_credencializacion.TipoSangre);
      $("#Curp").text(datos_credencializacion.Curp);
      $("#Rfc").text(datos_credencializacion.Rfc);
      $("#Clue").text(datos_credencializacion.Clue);

      if(datos_credencializacion.tieneFoto == 0){

            $("#foto").attr('src', '../images/usuarios.jpg');      
      }
      else{
            $("#foto").attr("src","http://credencializacion.saludchiapas.gob.mx/images/credenciales/"+datos_credencializacion.id+"."+datos_credencializacion.tipoFoto);
            
      }
}

function getParameterByName() {
    var ruta_completa = location.pathname;
    var splits = ruta_completa.split("/");
    return splits[(splits.length - 1)];
}