$(document).ready(function()
{
    //cargar_dato("");
   // cargar_catalogo();
   cargar_usuarios();
});

function cargar_usuarios() {

  var options = {

      url: function(bi) {
          return 'api/buscacapturista';
      },

      getValue: function(element) {
          return element.nombre;

      },
      list: {
          onSelectItemEvent: function() {
              var selectedItemValue = $("#nombre").getSelectedItemData().id;
              $("#user").val(selectedItemValue).trigger("change");
              
          }
      },
      ajaxSettings: {
          dataType: "json",
          method: "POST",
          data: {
              dataType: "json"
          }
      },

      preparePostData: function(data) {
          data.bi = $("#nombre").val();          
          return data;
      },


      requestDelay: 400,
      theme: "plate-dark"
  };

  $("#nombre").easyAutocomplete(options);


}

function btn_filtrar()
{
      //console.log("entro");
      var anio = $("#anio").val();
      var user = parseInt($("#user").val());
      var inicio = $("#inicio").val();     
      var fin = $("#fin").val();
  console.log(inicio);
      obj_filtro = { 'inicio': inicio, 'fin': fin,'user': user};
      cargar_dato(obj_filtro);
}

function cargar_dato(dato)
{
   
      var lista = $("#lista_incidencias");
      lista.html("");
      var linea_cargar = $("<tr><td colspan='22'>Cargando espere un momento, por favor. <i class='fa fa-spin fa-refresh'></i></td></tr>");
      lista.append(linea_cargar);

      jQuery.ajax({
            data: dato,
            type: "GET",
            dataType: "json",
            url: './api/logs',
      }).done(function( data, textStatus, jqXHR ) {
            lista.html("");
            console.log(data.logs.length);
            if(data.logs.length == 0)
            {

                  var linea = $("<tr ></tr>");
                  var campo1 = $("<td colspan='20'>No se encontraron resultados</td>");
                  linea.append(campo1);
                  lista.append(linea);
                    
            }
            $.each(data.logs.data, function(index, value)
            {
              var linea = $("<tr></tr>");
              var campo1 = $("<td>" + value.capturista.nombre+ "</td>");
              var campo2 = $("<td>" + value.siglas.LeaveName + "</td>");
              var campo3 = $("<td>" + value.STARTSPECDAY.substr(0,16)  + "</td>");
              var campo4 = $("<td>" + value.ENDSPECDAY.substr(0,16) + "</td>");
              var campo5 = $("<td>" + value.YUANYING + "</td>");
              var campo6 = $("<td>" + value.DATE.substr(0,19) + "</td>");
             /*  var campo3 = $("<td>" + value.TITLE + "</td>"); */
              linea.append(campo1, campo2, campo3, campo4, campo5, campo6);
              lista.append(linea);
                console.log(data.logs);

                $.each(value.capturista, function(index_capturista, value_capturista)
                {

                });
               
            });
            
      }).fail(function( jqXHR, textStatus, errorThrown ) {
            
      });
}

function generar_reporte()
{
      var anio = $("#anio").val();
      var mes = $("#mes").val();
      var direccion = $("#direccion").val();
      var nombre = $("#nombre").val();
      var quincena = $("#quincena").val();

      /*obj_filtro = { 'anio': anio, 'mes': mes, 'tipo_trabajador': tipo_trabajador, 'quincena': quincena };*/

      
      win = window.open( './api/reporte-direccion?anio='+anio+"&mes="+mes+"&direccion="+direccion+"&nombre="+nombre+"&quincena="+quincena, '_blank');
}

