
var tipo;
var user;
var inicio;
var fin;
//btn_filtrar();
$(document).ready(function() {

    var fecha = new Date(); //Fecha actual
    var mes = fecha.getMonth() + 1; //obteniendo mes
    var dia = fecha.getDate(); //obteniendo dia
    var ano = fecha.getFullYear(); //obteniendo año
    if (dia < 10)
        dia = '0' + dia; //agrega cero si el menor de 10
    if (mes < 10)
        mes = '0' + mes //agrega cero si el menor de 10
    document.getElementById('fin').value = ano + "-" + mes + "-" + dia;
    document.getElementById('inicio').value = ano + "-" + mes + "-" + '01';
    
    cargar_empleados();
});

function cargar_empleados() {

    var options = {

        url: function(bi) {
            return 'api/buscaempleado';
        },

        getValue: function(element) {
          //  return (element.NAME + " " + element.apellido_paterno + " " + element.apellido_materno);
            return (element.Badgenumber + "   " + element.Name);



        },
        list: {
            onSelectItemEvent: function() {
                var selectedItemValue = $("#nombre").getSelectedItemData().USERID;
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
        //console.log(data);
        preparePostData: function(data) {
            data.bi = $("#nombre").val();
            return data;
        },


        requestDelay: 400,
        theme: "plate-dark"
    };

    $("#nombre").easyAutocomplete(options);


}


function cargar_dato(dato) {

 
    var lista = $("#lista_incidencias");
    lista.html("");
    var linea_cargar = $("<tr><td colspan='22'>Cargando espere un momento, por favor. <i class='fa fa-spin fa-refresh'></i></td></tr>");
    lista.append(linea_cargar);

    jQuery.ajax({
        data: dato,
        type: "GET",
        dataType: "json",
        url: './api/incidencias',
    }).done(function(data, textStatus, jqXHR) {
        lista.html("");
        
        if (tipo==1) {
            /* var titulos = ["Incidencia","Inicio","Fin","Referencia","Fecha Captura","Capturista","Acciones"];
            for(i=0;i<7;i++) {
                var hilera = document.createElement("tr");     
                
                var tr = document.getElementById('table').tHead.children[0],
                th = document.createElement('th');
        
                th.innerHTML = titulos[i];
                tr.appendChild(th);
                
            } */
            if (data.logs.length == 0) {

                var linea = $("<tr ></tr>");
                var campo1 = $("<td colspan='20'>No se encontraron resultados</td>");
                linea.append(campo1);
                lista.append(linea);
    
            }
            var num = 0;
            console.log(data.logs);
            $.each(data.logs.data, function(index, value) {
                num += 1;
                var linea = $("<tr></tr>");
                var campo = $("<td>" + num + "</td>");
                var campo7 = $("<td>" + value.empleado.Badgenumber + "-" + value.empleado.Name + "</td>");           
                var campo2 = $("<td>" + value.siglas.LeaveName + "</td>");
                var campo3 = $("<td>" + value.STARTSPECDAY.substr(0, 16) + "</td>");
                var campo4 = $("<td>" + value.ENDSPECDAY.substr(0, 16) + "</td>");
                var campo5 = $("<td>" + value.YUANYING + "</td>");
                var campo6 = $("<td>" + value.DATE.substr(0, 19) + "</td>");
                var campo1 = $("<td>" + value.capturista.nombre + "</td>");
                var campo8 = $("<td> <a type='button' class='btn btn-link' onclick='eliminar(" + value.id + ")'><i class='fa fa-eraser' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Eliminar Incidencia'></i></a></td>")
               
               
                linea.append(campo, campo7, campo2, campo3, campo4, campo5, campo6, campo1,campo8);
                lista.append(linea);
                
    
            });
            tr.removeChild();
        }
        else{
           
            if (data.omision.length == 0) {

                var linea = $("<tr ></tr>");
                var campo1 = $("<td colspan='20'>No se encontraron resultados</td>");
                linea.append(campo1);
                lista.append(linea);
    
            }
            var num = 0;
            console.log(data.omision);
            $.each(data.omision.data, function(index, value) {
                num += 1;
                var linea = $("<tr></tr>");
                var campo = $("<td>" + num + "</td>");
                var campo7 = $("<td>" + value.empleado.Badgenumber + "-" + value.empleado.Name + "</td>");           
                         
                switch (value.CHECKTYPE) {
                    case "I":
                        var campo2 = $("<td>OMISION ENTRADA</td>"); 
                        break;

                    case "O":
                        var campo2 = $("<td>OMISION SALIDA</td>");
                        break;
                    default:
                }
                var campo3 = $("<td>" + value.CHECKTIME.substr(0, 16) + "</td>");
                var campo4 = $("<td>---------------</td>"); 
                var campo5 = $("<td>" + value.checadas.Memoinfo + "</td>");
                var campo6 = $("<td>" + value.DATE.substr(0, 19) + "</td>");
                var campo1 = $("<td>" + value.capturista.nombre + "</td>");
                var campo8 = $("<td> <a type='button' class='btn btn-link' onclick='eliminar_omision(" + value.EXACTID + ")'><i class='fa fa-eraser' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Eliminar Omisión'></i></a></td>")
               
               
                linea.append(campo, campo7, campo2, campo3, campo4, campo5, campo6, campo1,campo8);
                lista.append(linea);
                
    
               
    
            });
        }
       

    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}

function btn_filtrar() {
    tipo = $("#tipopermiso").val();
    user = parseInt($("#user").val());
    inicio = $("#inicio").val();
    fin = $("#fin").val();
    //btn_filtrar();
    //console.log("entro");
   
    console.log(inicio);
    obj_filtro = { 'inicio': inicio, 'fin': fin, 'user': user,'tipo': tipo };
    cargar_dato(obj_filtro);
}

function generar_reporte() {
    var user = parseInt($("#user").val());
    var inicio = $("#inicio").val();
    var fin = $("#fin").val();
    
    if (user!=0){
        win = window.open('./api/reporte-incidencias?user=' + user + "&inicio=" + inicio + "&fin=" + fin, '_blank');
    }else{
        alert("Debe Seleccionar un Empleado");
    }
    

//console.log(user + "&inicio=" + inicio + "&fin=" + fin)
   
}


function eliminar(id) {


   swal({
            title: "¿Estás seguro?",
            text: "Una vez eliminado, no podrá recuperar este dato!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'DELETE',
                    url: "api/deleteincidencia/" + id + "/",
                    success: function(data) {
                        swal("¡La incidencia ha sido eliminada!", {
                            icon: "success",
                        });
                       
                        document.getElementById('filtro_check').click();

                    }
                });



            } else {
                swal("El registro no se ha eliminado");
            }
        }); 
 

}


function eliminar_omision(id) {
    swal({
            title: "¿Estás seguro?",
            text: "Una vez eliminado, no podrá recuperar este dato!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'DELETE',
                    url: "api/deleteomision/" + id + "/",
                    success: function(data) {
                        swal("¡La Omisión ha sido eliminada!", {
                            icon: "success",
                        });
                        
                        document.getElementById('filtro_check').click();

                    }
                });



            } else {
                swal("El registro no se ha eliminado");
            }
        });

}


