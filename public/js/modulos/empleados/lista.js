var urlchecadas = "./api/consulta-asistencia";
var dato;
var date = new Date();
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
          cargar_datos_empleado(data.usuarios.data);
         
          }).fail(function( jqXHR, textStatus, errorThrown ) {
          
    });
}
function cargar_select(){
      $("#incidencia_tipo").empty();
      $("#incidencia_tipo").append("<option disabled selected value=''>Elegir tipo de Incidencia</option>");
      $.ajax({
            type: "GET",
            url: './api/empleado', 
            dataType: "json",
            success: function(data){
                  //console.log(data.incidencias);
            
            
              $.each(data.incidencias,function(key, registro) {
                  $("#incidencia_tipo").append("<option value="+registro.LeaveId+">"+registro.LeaveName+"</option>");
              });        
            },
            error: function(data) {
              alert('error');
            }
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
          var hentrada =value.horarios[0].detalle_horario[0].STARTTIME;
          var hsalida =value.horarios[0].detalle_horario[0].ENDTIME;
          hentrada = hentrada.substring(16,11);
          hsalida = hsalida.substring(16,11);
          var campo5 = $("<td><button type='button' class='btn btn-warning' onclick='incidencia(\""+value.Badgenumber+"\",\""+value.Name+"\",\""+value.TITLE+"\",\""+hentrada+"\",\""+hsalida+"\")'>Incidencia</button></td>");
          var campo6 = $("<td><button type='button' class='btn btn-success' onclick='kardex_empleado(\""+value.TITLE+"\")'>kardex</button></td>");
          
          var campo4 = $("<td>Sin Horario</td>");
          if(value.horarios.length > 0)
                var campo4 = $("<td>Horario Activo</td>");
            
         // console.log(value.horarios[0].detalle_horario);
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
   
     cargar_dato(rfc)
      
}

function incidencia(iduser,nombre,rfc,jini,jfin)
{     
      var mes = date.getMonth()+1; //obteniendo mes
      var dia = date.getDate(); //obteniendo dia
      var ano = date.getFullYear(); //obteniendo año
      if(dia<10)
            dia='0'+dia; 
      if(mes<10)
            mes='0'+mes;
      document.getElementById('checadas_modal').click();
      dato=rfc;
      inicio = $("#inicio").val="01-"+mes+"-"+ano;     
      fin = $("#fin").val=dia+"-"+mes+"-"+ano;    
      cargar_datos_checadas(urlchecadas)
      $("#hentra").html(jini);
      $("#hsal").html(jfin);
      $("#iduser").html(iduser);
      $("#nombre").html(nombre);
      
}
function guardar_incidencia(){

  
      var date_1 = new Date($("#f_ini").val());
      var date_2 = new Date($("#f_fin").val());      
      var day_as_milliseconds = 86400000;
      var diff_in_millisenconds = date_2 - date_1;
      var diff_in_days = diff_in_millisenconds / day_as_milliseconds;      
     // alert(parseInt((diff_in_days+1),10));
      for (var i = 0; i < parseInt((diff_in_days+1),10); i++) {

           var fecha_pri = new Date(date_1.setDate(date_1.getDate()+i));
           var fec_sal = fecha_pri.toString("yyyy-MM-dd HH:mm:ss");

            alert("num: "+i+"fecha: "+fecha_pri);
         }
         //alert(date_1.setDate(date_1.getDate() + 1));
      //alert($("#f_ini").val());
      
}

function generar_inci(jini,jfin)
{     
      //alert(jfin);
      cargar_select();
      $("#f_ini").val(jini);
      $("#f_fin").val(jfin);
    //  alert(jor_Ini);
      
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



function cargar_blade_checadas()
{
      
      
      var table = $("#datos_filtros_checadas");
      table.html("");
      $.each(datos_checadas_mes, function(index, value){
            icono = "<i class='fa fa-check' style='color:green'></i>";
            if(value.validacion == 0)
            icono = "<i class='fa fa-close' style='color:red'><a type='button' class='btn btn-link' style='color:blue' data-toggle='modal' data-target='#agregar_incidencia' onclick='generar_inci(\""+value.jorini+"\",\""+value.jorfin+"\")'><i class='fa fa-id-card-o' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Generar Incidencia'></i></a></i>";
            else
            icono = "<i class='fa fa-check' style='color:green'></i>";
           
            table.append("<tr><td>" + arreglo_dias[value.numero_dia] + "</td><td>" + value.fecha + "</td>" + "</td><td>" + value.checado_entrada + "</td>" + "</td><td>" + value.checado_salida + "</td> <td>"+icono+"</td></tr>");
            
      })

        
      $('#datos_filtros_checadas tr').hover(function() {
            $(this).addClass('hover');
        }, function() {
            $(this).removeClass('hover');
        });
      
}
function cargar_kardex(){

      document.getElementById('kardex').click();
}


function filtrar_checadas()
{
      inicio = $("#inicio").val();
      fin = $("#fin").val();
      cargar_datos_checadas(urlchecadas);
      //cargar_blade_checadas();
      

}


function cargar_dato(dato)
{
   
      var lista = $("#kardex");
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
