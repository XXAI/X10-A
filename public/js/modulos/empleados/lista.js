var urlchecadas = "./api/consulta-asistencia";
var dato;
var date = new Date();
var resumen_checadas;
var diaslab;
var diaeco;
var onomastico;
var pasesal;
var inicio;
var fin;
var xini,xfin;
var id, idcap;
var id_x;
var rfc_x;

arreglo_dias = Array("", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO", "DOMINGO")
arreglo_mes = Array("", "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "NOVIEMBRE","DICIEMBRE")

$(document).ready(function(){
  
    cargar_empleados('');

   // console.log(arreglo_dias[1];)
    
   
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
                
              $.each(data.incidencias,function(key, registro) {
                  $("#incidencia_tipo").append("<option value="+registro.LeaveId+">"+registro.LeaveName+"</option>");
              });        
            },
            error: function(data) {
              alert('error');
            }
          });        
    
          
}

function obten_fecnac(rfc_x)
{    
         
            onomastico= rfc_x.substr(4,6);      
            onomastico=onomastico.substr(2,2)+"-"+onomastico.substr(4,2);  
            //console.log(onomastico);
}          

function cargar_departamentos(){
      $("#tipotra").empty();
      $("#tipotra").append("<option disabled selected value=''>Elegir tipo de trabajador</option>");
      $.ajax({
            type: "GET",
            url: './api/empleado', 
            dataType: "json",
            success: function(data){
                  
            
            
              $.each(data.departamentos,function(key, registro) {
                  $("#tipotra").append("<option value="+registro.DEPTID+">"+registro.DEPTNAME+"</option>");
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
          diaslab=(value.horarios[0].detalle_horario);
          var campo5 = $("<td><button id='pruebaclic' type='button' class='btn btn-warning' data-toggle='modal' data-target='#modal_kardex' onclick='incidencia(\""+value.USERID+"\",\""+value.Badgenumber+"\",\""+value.Name+"\",\""+value.TITLE+"\",\""+hentrada+"\",\""+hsalida+"\")'>Incidencia</button></td>");
          var campo6 = $("<td><button type='button' class='btn btn-success' onclick='kardex_empleado(\""+value.TITLE+"\")'>kardex</button></td>");
         
          var campo4 = $("<td>Sin Horario</td>");
          if(value.horarios.length > 0)
                var campo4 = $("<td>Horario Activo</td>");
            
        
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

function sacadias(){
    
      
}

function incidencia(id,iduser,nombre,rfc,jini,jfin)
{     
      sacadias();      
      obten_fecnac(rfc);
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
      id_x=id;
      $("#iduser").html(iduser);
      $("#nombre").html(nombre);
      
      
}
function guardar_incidencia(){

      id = $("#id").val();
      idcap = $("#id_user").val();
      var date_1 = moment($("#f_ini").val());
      var date_2 = moment($("#f_fin").val());       
      var tipo_incidencia = $("#incidencia_tipo").val();     
      var razon = $("#razon").val();  
      var diff_in_days = date_2.diff(date_1, 'days');  
      var  diff=0;
      diff=1+diff_in_days;
      //console.log(diff);
      var fec_com=moment(date_1).format();
      fec_com=fec_com.substr(5,5);     
      var bandera;
      var msj;
       switch (parseInt(tipo_incidencia)) {
            /* case 1:
                  if(diff_in_days==0 ){
                        bandera=1;
                  }                 
                  break;
                   */
            case 6:          
                                          
                  if(resumen_checadas.Día_Económico<2)
                   if((resumen_checadas.Día_Económico==0 && diff<=2)||(resumen_checadas.Día_Económico==1 && diff==1)) {                    
                        bandera=1;                    
                     
                   }
                  
                  else{
                        bandera=0;                       
                        msj="Solo puede tener maximo 2 dias económicos en el mes";
                  }
                  
                  break;
            case 10:                  
                  if(diff_in_days==0 && fec_com==onomastico){
                        bandera=1;
                        
                  }
                  else{
                        bandera=0;
                        msj="La fecha no es la misma que su onomástico";
                  }                 
                  
                  break;
            default:
            bandera=1;
                  
       }  
       if (bandera==1){

       var x=0;
       var dia_eva;
       for (var i = 0; i < parseInt(diff_in_days+1); i++) { 
            fini= moment(date_1.add(x, 'd')).format();
            ffin= moment(date_2.add(x, 'd')).format();
            fini=fini.substr(0,10)+" "+ fini.substr(11,8)+".00";
            ffin=fini.substr(0,10)+" "+ ffin.substr(11,8)+".00";                                   
            for(var j =0; j < diaslab.length;j++){
                  
                  if (moment(fini).day()==0)
                        dia_eva=7;
                  else
                        dia_eva=moment(fini).day();
                  
                  if( dia_eva == diaslab[j].EDAYS){
                        $.ajax({   
                              type: 'POST',
                              url:  "api/guarda-justificante",
                              data: {id:id, fini:fini,ffin:ffin,tipo_incidencia:tipo_incidencia,razon:razon,idcap:idcap},
                              success: function(data){ 
                                    swal("Exito!", "El registro se ha guardado!", "success");                 
                              },
                              error: function(data) {
                                    swal("Error!","No se registro ningun dato!", "error");
                              }
                        })  
                      
                  }
             }
                  
            x=1;
            
      } 
            $('#agregar_incidencia').modal('toggle'); 
            document.getElementById('filtro_check').click(); 
      }
      else{
            swal("Error!",msj+"!", "error");
      }
           
    
    

    
    
}

function guardar_entrasal(){

      

      id = $("#id").val();
      idcap = $("#id_user").val();
      var fecha_ing = moment($("#fecha_reg").val());
      var tipo_registro = $("#tipo_es").val();    
      var razon = $("#refe").val();   
            
      var fing= moment(fecha_ing).format();            
      fing = fing.substr(0,10)+" "+ fing.substr(11,8)+".00";   
      
      
      
             $.ajax({   
                  type: 'POST',
                  url:  "api/guarda-entrasal",
                  data: {id:id, fing:fing,tipo_registro:tipo_registro,razon:razon,idcap:idcap},
                  success: function(data){ 
                        swal("Exito!", "El registro se ha guardado!", "success"); 
                        $("#refe").val(''); 
                        $("#tipo_es").val('');                      
                        $("#agregar_entrasal").modal('hide'); 
                        document.getElementById('filtro_check').click();
                       
                         

                  },
                  error: function(data) {
                        swal("Error!","No se registro ningun dato!", "error");
                        

                  }
              })  

         
     

    
}
function guardar_empleado(){

      

     
      var name = $("#name").val();
      var rf = $("#rfc").val();    
      var sexo = $("#sexo").val();   
      var codigo = $("#codigo").val();    
      var clues = $("#clues").val();  
      var area= $("#area").val();    
      var tipotra= $("#tipotra").val(); 
      var street=$('select[name="tipotra"] option:selected').text();
      var city;
       if (tipotra==6)
            city="416";
      else
            city=street.substr(0,3);

       
    
           var fecnac= rf.substr(4,6);
            if (fecnac.substr(0,2)>=20)
                  fecnac="19"+fecnac.substr(0,2)+"-"+fecnac.substr(2,2)+"-"+fecnac.substr(4,2)+" 00:00:00.00";  
            else
                  fecnac="20"+fecnac.substr(0,2)+"-"+fecnac.substr(2,2)+"-"+fecnac.substr(4,2)+" 00:00:00.00";
      
      var fechaing= moment(fechaing).format();            
      fechaing = fechaing.substr(0,10)+" 00:00:00.00";       
     
      
              $.ajax({   
                  type: 'POST',
                  url:  "api/guarda-empleado",
                  data: {name:name,rf:rf,sexo:sexo,fechaing:fechaing,fecnac:fecnac,codigo:codigo,clues:clues,area:area,tipotra:tipotra,street:street,city:city},
                  success: function(data){ 
                        swal("Exito!", data.mensaje, "success"); 
                        $("#name").val('');
                        $("#rfc").val('');    
                        $("#sexo").val('');   
                        $("#codigo").val('');    
                        $("#clues").val('');  
                        $("#area").val('');        
                        $('#agregar_empleado').modal('toggle');  
                        cargar_empleados('');                 
                       
                         

                  },
                  error: function(data) {
                        swal("Error!","No se registro ningun dato!", "error");
                        

                  }
              })    

         
     

    
}

function sel_inci(valor){

      
       
}


function generar_inci(jini,jfin)
{     
      //alert($("#iduser").text());
      cargar_select();
      $("#id").val(id_x);
      $("#f_ini").val(jini);
      $("#f_fin").val(jfin);
    //  alert(jor_Ini);
      
}

function agregar_entsal(jini,jfin)
{     
   $("#id").val(id_x);
   xini=jini;
   xfin=jfin;
   
         
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
           //console.log(data);
            $("#inicio").val(data.fecha_inicial);
            $("#fin").val(data.fecha_final);
            
            datos_checadas_mes = data.data;            
            resumen_checadas = data.resumen[0];
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
            if(value.validacion == 0 || value.checado_entrada.includes('Retardo'))
            icono = "<i class='fa fa-close' style='color:red'><a type='button' class='btn btn-link' style='color:blue' data-toggle='modal' data-target='#agregar_incidencia' onclick='generar_inci(\""+value.jorini+"\",\""+value.jorfin+"\")'><i class='fa fa-id-card-o' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Generar Incidencia'></i></a><a type='button' class='btn btn-link' style='color:blue' data-toggle='modal' data-target='#agregar_entrasal' onclick='agregar_entsal(\""+value.jorini+"\",\""+value.jorfin+"\")'><i class='fa fa-clock-o' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Agregar Entrada o Salida'></i></a></i>";
            else
            icono = "<i class='fa fa-check' style='color:green'></i>";
           if (value.checado_salida==value.checado_salida_fuera)
                 xs=value.checado_salida;
            else
                  xs=value.checado_salida+"("+value.checado_salida_fuera+")";
          
           
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


function sel_tiporeg(tiporeg){

      agregar_entsal(xini,xfin)
      if(tiporeg=="I"){
            $("#fecha_reg").val(xini);
      }
      else{
            $("#fecha_reg").val(xfin);
      }      

}