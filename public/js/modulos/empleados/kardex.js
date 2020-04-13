var urlchecadas = "./api/kardex";
$(document).ready(function(){
  
    
});


function kardex_empleado(rfc)
{     
      dato=rfc;
      inicio = $("#inicio").val();
      fin = $("#fin").val();    
     
     cargar_datos_checadas(urlchecadas);
      
}

function cargar_datos_checadas(urlchecadas)
{
      
      $("#datos_filtros_checadas").html("<tr><td colspan='5'><i class='fa fa-refresh fa-spin'></i> Cargando, Espere un momento por favor</td></tr>");
      
      jQuery.ajax({
            data: {'id': dato,
                   'fecha_inicio': inicio,
                   'fecha_fin': fin
                  },
            type: "GET",
            dataType: "json",
            url: urlchecadas,
           
      }).done(function( data, textStatus, jqXHR ) {
            
            $("#inicio").val(data.fecha_inicial);
            $("#fin").val(data.fecha_final);
            console.log(data);
            datos_checadas_mes = data.data;
            
            validacion = data.validacion;
            if(validacion != null){
                  cargar_blade_checadas();
                 
            }else{

                  
                  var checadas = $("#checadas");
                  
                  checadas.html("");
                  $("#resumen").append("<div class=card-body> <h1 class=card-title align=center>Acudir a Sistematización</h1> <hr> <div class=row> <div class='col-md-6 col-sm-6' style='text-align:left'><img src=../images/salud.png class=img-fluid flex alt=Responsive image width=50%></div> <div class='col-md-6 col-sm-6' style='text-align:right' ><img src=../images/chiapas.png class=img-fluid flex alt=Responsive image width=50%></div></div> </div>");

                  document.getElementById('modal').click();


            }

            

      }).fail(function( jqXHR, textStatus, errorThrown ) {
            if ( console && console.log ) {
                  
                  alert( "No se cargo la lista de asistencia "+ dato +" "+ textStatus);
            }
      });
}
