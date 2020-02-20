$(document).ready(function(){
  
    cargar_dato('');
});

function cargar_dato(dato, urlrh)
{
    var table = $("#empleados").html('');
    jQuery.ajax({
          data: {'buscar': dato},
          type: "GET",
          dataType: "json",
          url:  './api/empleado',
    }).done(function( data, textStatus, jqXHR ) {
          console.log(data);
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
          var campo5 = $("<td><button type='button' class='btn btn-success' onclick='kardex_usuario(\""+value.TITLE+"\")'>kardex</button></td>");
          
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
      cargar_dato(buscar);
}