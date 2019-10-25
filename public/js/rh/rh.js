var datos_credencializacion;
var datos_checadas;
var urlchecadas = "../api/consulta-mensual";
var dato;
$(document).ready(function(){

      dato = getParameterByName();
      var urlrh = "http://credencializacion.saludchiapas.gob.mx/ConsultaRhPersonal.php";
      
      cargar_dato(dato, urlrh);
      
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
            cargar_blade();
            cargar_datos_checadas(urlchecadas);

      }).fail(function( jqXHR, textStatus, errorThrown ) {
            if ( console && console.log ) {
            alert( "Error en la carga de Datos, asista a Sistematización: " +  textStatus);
            }
      });

      for(var i=2019; i < 2030; i++)
      {
            $("select[name=anio]").append(new Option(i,i));
      }
}

function cargar_datos_checadas(urlchecadas)
{

      jQuery.ajax({
            data: {'rfc': dato},
            type: "GET",
            dataType: "json",
            url: urlchecadas,
      }).done(function( data, textStatus, jqXHR ) {
            datos_checadas = data;
            console.log("checadas", datos_checadas);

      }).fail(function( jqXHR, textStatus, errorThrown ) {
            if ( console && console.log ) {
            alert( "No se cargo la lista de asistencia" +  textStatus);
            }
      });
}

function cargar_blade()
{ 
      $("#Nombre").text(datos_credencializacion.Nombre);
      $("#Adscripcion_Area").text(datos_credencializacion.DesPuesto);  
      $("#nombre").text(datos_credencializacion.nombre);
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
      console.log(splits);
      return splits[2];
}

