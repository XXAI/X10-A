$(document).ready(function()
{
    //cargar_dato("");
    cargar_catalogo();
});

function cargar_catalogo()
{
      var select = $("#tipo_trabajador");
      select.html("");
      jQuery.ajax({
            data: {'buscar': ""},
            type: "GET",
            dataType: "json",
            url: './api/catalogo',
      }).done(function( data, textStatus, jqXHR ) {
            $.each(data.catalogo, function(index, valor)
           {
                 if(valor.DEPTID!=1)
                 {
                        select.append("<option value='"+valor.DEPTID+"'>"+valor.DEPTNAME+"</option>");
                 }
           });
            
      }).fail(function( jqXHR, textStatus, errorThrown ) {
            
      });
}

function btn_filtrar()
{
      //console.log("entro");
      var anio = $("#anio").val();
      var mes = $("#mes").val();
      var tipo_trabajador = $("#tipo_trabajador").val();
      //var quincena = $("#quincena").val();
      var nombre = $("#nombre").val();

      obj_filtro = { 'anio': anio, 'mes': mes, 'tipo_trabajador': tipo_trabajador, 'nombre': nombre };
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
            url: './api/mensual',
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
                
                var campo1 = $("<td rowspan='2' style='border-bottom:1px solid black;'>"+ value.Badgenumber +' - '+value.Name+"<br>"+value.TITLE+"</td>");
                //linea.append(campo1);
                //lista.append(linea);
                
                campo2 =  $("<td rowspan='2' style='text-align:center;border-bottom:1px solid black;'>A<br>" + value.resumen.ASISTENCIA + "</td>");
                campo3 =  $("<td rowspan='2' style='text-align:center;border-bottom:1px solid black;'>R1Q1<br>" + value.resumen.RETARDOS_1 + "</td>");
                campo4 =  $("<td rowspan='2' style='text-align:center;border-bottom:1px solid black;'>R1Q2<br>" + value.resumen.RETARDOS_2 + "</td>");
                campo5 =  $("<td rowspan='2' style='text-align:center;border-bottom:1px solid black;; border-right:2px solid black;'>F<br>" + value.resumen.FALTAS + "</td>");
                //campo5 =  $("<td style='text-align:center'>RQ1<br>" + value.resumen.RETARDOS_1 + "</td>");
                //campo6 =  $("<td style='text-align:center'>RQ2<br>" + value.resumen.RETARDOS_2 + "</td>");
                linea.append(campo1, campo2, campo3, campo4, campo5);
                lista.append(linea);

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
                      if( i < 16 )
                      {
                        linea.append(campo);
                        lista.append(linea);
                      }else
                      {
                        linea2.append(campo);
                        lista.append(linea2);
                      }
                      
                      i++;
                });
            
            });
            
      }).fail(function( jqXHR, textStatus, errorThrown ) {
            
      });
}

function generar_reporte()
{
      var anio = $("#anio").val();
      var mes = $("#mes").val();
      var tipo_trabajador = $("#tipo_trabajador").val();
      var nombre = $("#nombre").val();

      /*obj_filtro = { 'anio': anio, 'mes': mes, 'tipo_trabajador': tipo_trabajador, 'quincena': quincena };*/

      
      win = window.open( './api/reporte-mensual?anio='+anio+"&mes="+mes+"&tipo_trabajador="+tipo_trabajador+"&nombre="+nombre, '_blank');
}