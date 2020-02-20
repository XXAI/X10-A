$(document).ready(function()
{
    cargar_dato("");
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
                  select.append("<option value='"+valor.DEPTID+"'>"+valor.DEPTNAME+"</option>");
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
      var quincena = $("#quincena").val();

      obj_filtro = { 'anio': anio, 'mes': mes, 'tipo_trabajador': tipo_trabajador, 'quincena': quincena };
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
            $.each(data.usuarios, function(index, value)
            {
                
                var linea = $("<tr ></tr>");
                var campo1 = $("<td>"+value.Name+"<br>"+value.TITLE+"</td>");
                //linea.append(campo1);
                //lista.append(linea);
                
                campo2 =  $("<td style='text-align:center'>A<br>" + value.resumen.ASISTENCIA + "</td>");
                campo3 =  $("<td  style='text-align:center'>RM<br>" + value.resumen.RETARDOS + "</td>");
                campo4 =  $("<td style='text-align:center'>F<br>" + value.resumen.FALTAS + "</td>");
                campo5 =  $("<td style='text-align:center'>RQ1<br>" + value.resumen.RETARDOS_1 + "</td>");
                campo6 =  $("<td style='text-align:center'>RQ2<br>" + value.resumen.RETARDOS_2 + "</td>");
                linea.append(campo1, campo2, campo3, campo4, campo5, campo6);
                lista.append(linea);

                //var i = 1;
                //var linea2 = $("<tr></tr>");
                //var tamano = Object.keys(value.asistencia).length;
                $.each(value.asistencia, function(index_asistencia, value_asistencia)
                {
                      if(value_asistencia == "F" || value_asistencia == "FE" || value_asistencia == "FS")
                      {
                        campo =  $("<td style='text-align:center; background-color:#EFEFEF' >" + index_asistencia + "<br>" + value_asistencia + "</td>");
                      }else
                      {
                        campo =  $("<td style='text-align:center'>" + index_asistencia + "<br>" + value_asistencia + "</td>");
                      }
                      //console.log(tamano);
                      /*if(tamano == 31 && i == 16)
                      {
                        linea.append($("<td style='text-align:center'></td>"));
                        lista.append(linea);
                      }
                      if( i < 16 )
                      {*/
                        linea.append(campo);
                        lista.append(linea);
                      /*}else
                      {
                        linea2.append(campo);
                        lista.append(linea2);
                      }
                      
                      i++;*/
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
      var quincena = $("#quincena").val();

      /*obj_filtro = { 'anio': anio, 'mes': mes, 'tipo_trabajador': tipo_trabajador, 'quincena': quincena };*/

      
      win = window.open( './api/reporte-mensual?anio='+anio+"&mes="+mes+"&tipo_trabajador="+tipo_trabajador+"&quincena="+quincena, '_blank');
}