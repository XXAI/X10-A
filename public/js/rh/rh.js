var datos_credencializacion;
var datos_checadas_mes;
var resumen_checadas;
var validacion;
var urlchecadas = "../api/consulta-asistencia";
var dato;
var inicio;
var fin;

$(document).ready(function(){

      dato = getParameterByName();
      inicio = $("#inicio").val();
      fin = $("#fin").val();

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
            cargar_blade_credencializacion();
            cargar_datos_checadas(urlchecadas);

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

function cargar_datos_checadas(urlchecadas)
{

      jQuery.ajax({
            data: {'rfc': dato,
                   'fecha_inicio': inicio,
                   'fecha_fin': fin
                  },
            type: "GET",
            dataType: "json",
            url: urlchecadas,
      }).done(function( data, textStatus, jqXHR ) {
            datos_checadas_mes = data.data;
            resumen_checadas = data.resumen[0];
            validacion = data.validacion;
            if(validacion != null){
                  cargar_blade_checadas();
                  cargar_blade_resumen();
            }else{

                  var resumen = $("#resumen");
                  var checadas = $("#checadas");
                  resumen.html("");
                  checadas.html("");
                  $("#resumen").append("<div class=card-body> <h1 class=card-title align=center>Acudir a Sistematización</h1> <hr> <div class=row> <div class='col-md-6 col-sm-6' style='text-align:left'><img src=../images/salud.png class=img-fluid flex alt=Responsive image width=50%></div> <div class='col-md-6 col-sm-6' style='text-align:right' ><img src=../images/chiapas.png class=img-fluid flex alt=Responsive image width=50%></div></div> </div>");

                  document.getElementById('modal').click();


            }

            

      }).fail(function( jqXHR, textStatus, errorThrown ) {
            if ( console && console.log ) {
                  alert( "No se cargo la lista de asistencia " +" "+ textStatus);
            }
      });
}

function cargar_blade_credencializacion()
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


function cargar_blade_checadas()
{

      var table = $("#datos_filtros_checadas");
      table.html("");
      $.each(datos_checadas_mes, function(index, value){
            table.append("<tr><td>" + index + "</td><td>" + value.fecha + "</td>" + "</td><td>" + value.checado_entrada + "</td>" + "</td><td>" + value.checado_salida + "</td> </tr>");
      })


      $('#datos_filtros_checadas tr').hover(function() {
            $(this).addClass('hover');
        }, function() {
            $(this).removeClass('hover');
        });
      
}

function cargar_blade_resumen()
{
      $("#Día_Económico").text(resumen_checadas.Día_Económico);
      $("#Falta").text(resumen_checadas.Falta);
      $("#Omisión_Entrada").text(resumen_checadas.Omisión_Entrada);
      $("#Omisión_Salida").text(resumen_checadas.Omisión_Salida);
      $("#Onomástico").text(resumen_checadas.Onomástico);
      $("#Pase_Salida").text(resumen_checadas.Pase_Salida);
      $("#Retardo_Mayor").text(resumen_checadas.Retardo_Mayor);
      $("#Retardo_Menor").text(resumen_checadas.Retardo_Menor);
      $("#Vacaciones_2018_Invierno").text(resumen_checadas.Vacaciones_2018_Invierno);
      $("#Vacaciones_2018_Primavera_Verano").text(resumen_checadas.Vacaciones_2018_Primavera_Verano);
      $("#Vacaciones_2019_Invierno").text(resumen_checadas.Vacaciones_2019_Invierno);
      $("#Vacaciones_2019_Primavera_Verano").text(resumen_checadas.Vacaciones_2019_Primavera_Verano);
      $("#Vacaciones_Extra_Ordinarias").text(resumen_checadas.Vacaciones_Extra_Ordinarias);
      $("#Vacaciones_Mediano_Riesgo").text(resumen_checadas.Vacaciones_Mediano_Riesgo);

}

function filtrar_checadas()
{
      inicio = $("#inicio").val();
      fin = $("#fin").val();
      cargar_datos_checadas(urlchecadas);
      //cargar_blade_checadas();
      

}

function getParameterByName() {
      var ruta_completa = location.pathname;
      var splits = ruta_completa.split("/");
      //console.log(splits);
      return splits[(splits.length - 1)];
}






// var table = $("#datosxx");
// table.html("");
// //table.find("tbody tr").remove();
// $.each(datos_checadas_mes, function(index, value){
//       table.append("<tr><td>" + index + "</td><td>" + value.fecha + "</td>" + "</td><td>" + value.checado_entrada + "</td>" + "</td><td>" + value.checado_salida + "</td> </tr>");
// })



// var table = $('#tabla_checadas');
// table.find("tbody tr").remove();
// $.each(datos_checadas_mes, function(index, value){
//       table.append("<tr><td>" + index + "</td><td>" + value.fecha + "</td>" + "</td><td>" + value.checado_entrada + "</td>" + "</td><td>" + value.checado_salida + "</td> </tr>");
// });
