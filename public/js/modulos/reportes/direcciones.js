$(document).ready(function()
{
    //cargar_dato("");
   // cargar_catalogo();
});



function btn_filtrar_dir()
{
      //console.log("entro");
      var anio = $("#anio").val();
      var mes = $("#mes").val();
      var direccion = $("#direccion").val();
      //var quincena = $("#quincena").val();
      var nombre = $("#nombre").val();

      obj_filtro = { 'anio': anio, 'mes': mes, 'direccion': direccion, 'nombre': nombre };
      cargar_dato(obj_filtro);
}

function cargar_dato(dato)
{
   
      var lista = $("#lista_personal");
      lista.html("");
      var linea_cargar = $("<tr><td colspan='22'>Cargando espere un momento, por favor. <i class='fa fa-spin fa-refresh'></i></td></tr>");
      lista.append(linea_cargar);

      jQuery.ajax({
            data: dato,
            type: "GET",
            dataType: "json",
            url: './api/direccion',
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
                
                var campo1 = $("<td style='border-bottom:1px solid black;'>"+ value.Badgenumber +' - '+value.TITLE+" - "+value.Name+"</td>");
                //linea.append(campo1);
                //lista.append(linea);
                
                campo2 =  $("<td style='text-align:center;border-bottom:1px solid black;'>A<br>" + value.resumen.ASISTENCIA + "</td>");
                campo3 =  $("<td style='text-align:center;border-bottom:1px solid black;'>R1<br>" + value.resumen.RETARDOS_1 + "</td>");
                //campo4 =  $("<td style='text-align:center;border-bottom:1px solid black;'>R1Q2<br>" + value.resumen.RETARDOS_2 + "</td>");
                
                campo5 =  $("<td style='text-align:center;border-bottom:1px solid black;;'>F<br>" + value.resumen.FALTAS + "</td>");
                campo6 =  $("<td style='text-align:center;border-bottom:1px solid black; border-right:2px solid black'>FT<br>" + value.resumen.FALTAS_TOTALES + "</td>");
                //campo5 =  $("<td style='text-align:center'>RQ1<br>" + value.resumen.RETARDOS_1 + "</td>");
                //campo6 =  $("<td style='text-align:center'>RQ2<br>" + value.resumen.RETARDOS_2 + "</td>");
                linea.append(campo1, campo2, campo3, campo5, campo6);
                lista.append(linea);

                var i = 1;
                //var linea2 = $("<tr></tr>");
                //var tamano = Object.keys(value.asistencia).length;
                $.each(value.asistencia, function(index_asistencia, value_asistencia)
                {
                      var stilo_linea = "";
                      if(i>=16)
                      {
                        //stilo_linea = "border-bottom:1px solid black;";
                      }
                      if(value_asistencia == "F" || value_asistencia == "FE" || value_asistencia == "FS")
                      {
                        campo =  $("<td style='"+stilo_linea+"' class='faltas color_rojo'>" + index_asistencia + "<br>" + value_asistencia + "</td>");
                      }else if(value_asistencia == "R1")
                      {
                        campo =  $("<td style='"+stilo_linea+"'  class='faltas color_gris'>" + index_asistencia + "<br>" + value_asistencia + "</td>");
                      }
                      else if(value_asistencia == "N/A")
                      {
                        campo =  $("<td style='background-color: #EFEFEF;"+stilo_linea+"'  class='faltas'>" + index_asistencia + "</td>");
                      }else
                      {
                        campo =  $("<td style='"+stilo_linea+"' class='faltas_default color_verde'>" + index_asistencia + "<br>" + value_asistencia + "</td>");
                      }
                      //console.log(tamano);
                      /*if(tamano == 31 && i == 16)
                      {
                        linea.append($("<td style='text-align:center;padding: 0rem !important;'></td>"));
                        lista.append(linea);
                      }
                      if( i < 16 )
                      {
                        linea.append(campo);
                        lista.append(linea);
                      }else
                      {
                        linea2.append(campo);
                        lista.append(linea2);
                      }*/
                      linea.append(campo);
                  lista.append(linea);
                      
                      i++;
                });
            
            });
            
      }).fail(function( jqXHR, textStatus, errorThrown ) {
            
      });
}

function generar_reporte_dir()
{
      var anio = $("#anio").val();
      var mes = $("#mes").val();
      var direccion = $("#direccion").val();
      var nombre = $("#nombre").val();
      var quincena = $("#quincena").val();

      /*obj_filtro = { 'anio': anio, 'mes': mes, 'tipo_trabajador': tipo_trabajador, 'quincena': quincena };*/

      
      win = window.open( './api/reporte-direccion?anio='+anio+"&mes="+mes+"&direccion="+direccion+"&nombre="+nombre+"&quincena="+quincena, '_blank');
}

