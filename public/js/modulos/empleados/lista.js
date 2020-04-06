var urlchecadas = "./api/consulta-asistencia";
var dato;
var inicio;
var fin;
arreglo_dias = Array("", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO", "DOMINGO")
arreglo_mes = Array("", "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "NOVIEMBRE","DICIEMBRE")

$(document).ready(function(){
  
    cargar_empleados('');
});

function cargar_empleados(dato)
{

    var table = $("#empleados").html('');
    jQuery.ajax({
          data: {'buscar': dato},
          type: "GET",
          dataType: "json",
          url:  './api/empleado',
    }).done(function( data, textStatus, jqXHR ) {
         // console.log(data);
          cargar_datos_empleado(data.usuarios.data);
          
          }).fail(function( jqXHR, textStatus, errorThrown ) {
          
    });
}

function cargar_datos_empleado(datos)
{
    var table = $("#empleados");
      $.each(datos, function(key, value)
    {
          var linea = $("<tr></tr>");
          var campo1 = $("<td>"+value.Badgenumber+"</td>");
          var campo2 = $("<td>"+value.Name+"</td>");
          var campo3 = $("<td>"+value.TITLE+"</td>");
          var campo5 = $("<td><button type='button' class='btn btn-success' onclick='kardex_empleado(\""+value.TITLE+"\")'>kardex</button></td>");
          
          var campo4 = $("<td>Sin Horario</td>");
          if(value.horarios.length > 0)
                var campo4 = $("<td>Horario Activo</td>");

          //console.log(value);
          linea.append(campo1, campo2, campo3, campo4, campo5);
          table.append(linea);
    });
}

function btn_filtrar()
{     
      var buscar = $("#buscar").val();      
      cargar_empleados(buscar);
}
function kardex_empleado(rfc)
{     
      
      cargar_kardex();
      dato=rfc;
      inicio = $("#inicio").val();
      fin = $("#fin").val();    
     //cargar_tabla(dato);
     cargar_dato(rfc)
      
}


/*  function cargar_datos_checadas(urlchecadas)
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


function cargar_blade_checadas()
{
      
      var table = $("#datos_filtros_checadas");
      table.html("");      
      $.each(datos_checadas_mes, function(index, value){
            var i = 1;
            var linea = $("<tr></tr>");
           // var tamano = Object.keys(value.data).length;
           // console.log("valor:      "+tamano);

           console.log(value.data.resumen);
            
            
      })

//      for(x=1;x<=30;x++)
     

      $('#datos_filtros_checadas tr').hover(function() {
            $(this).addClass('hover');
        }, function() {
            $(this).removeClass('hover');
        });
      
} */ 
function cargar_kardex(){

      document.getElementById('kardex').click();
}

function cargar_dato(dato)
{
   
      var lista = $("#checadas");
      lista.html("");
      var linea_cargar = $("<tr><td colspan='22'>Cargando espere un momento, por favor. <i class='fa fa-spin fa-refresh'></i></td></tr>");
      lista.append(linea_cargar);

      jQuery.ajax({
            data: {'id':dato,'mes':'02'},
            type: "GET",
            dataType: "json",
            url: './api/kardex',
      }).done(function( data, textStatus, jqXHR ) {
            lista.html("");
            console.log(data.usuarios.length);
            if(data.usuarios.length == 0)
            {
                  var linea = $("<tr ></tr>");
                  var campo1 = $("<td colspan='20'>No se encontraron resultados</td>");
                  linea.append(campo1);
                  lista.append(linea);
                    
            }
            $.each(data.usuarios, function(index, value)
            {
                console.log(data.usuarios);
                var linea = $("<tr  ></tr>");               
               

                var i = 1;
                var linea2 = $("<tr></tr>");
                var tamano = Object.keys(value.asistencia).length;
                $.each(value.asistencia, function(index_asistencia, value_asistencia)
                {
                      var stilo_linea = "";
                      if(i>=16)
                      {
                        stilo_linea = "border-bottom:1px solid black;";
                      }
                      if(value_asistencia == "F" || value_asistencia == "FE" || value_asistencia == "FS")
                      {
                        campo =  $("<td style='text-align:center; background-color:#993e3e; color:white; padding: 0rem !important;"+stilo_linea+"' >" + index_asistencia + "<br>" + value_asistencia + "</td>");
                      }else if(value_asistencia == "R1")
                      {
                        campo =  $("<td style='text-align:center; background-color:#6a6969; color:white;padding: 0rem !important;"+stilo_linea+"' >" + index_asistencia + "<br>" + value_asistencia + "</td>");
                      }
                      else if(value_asistencia == "N/A")
                      {
                        campo =  $("<td style='text-align:center;font-weight:bold; background-color: #EFEFEF; padding: 0rem !important;"+stilo_linea+"' >" + index_asistencia + "</td>");
                      }else
                      {
                        campo =  $("<td style='text-align:center;padding: 0rem !important;"+stilo_linea+"'>" + index_asistencia + "<br>" + value_asistencia + "</td>");
                      }
                      //console.log(tamano);
                      if(tamano == 31 && i == 16)
                      {
                        linea.append($("<td style='text-align:center;padding: 0rem !important;'></td>"));
                        lista.append(linea);
                      }
                     /*  if( i < 16 )
                      { */
                        linea.append(campo);
                        lista.append(linea);
                     /*  }else
                      {
                        linea2.append(campo);
                        lista.append(linea2);
                      } */
                      
                      i++;
                });
            
            });
            
      }).fail(function( jqXHR, textStatus, errorThrown ) {
            
      });
}