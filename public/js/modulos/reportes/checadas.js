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
    document.getElementById('inicio').value = ano + "-" + mes + "-" + dia;
    btn_filtrar();
    // cargar_usuarios();
});

function cargar_usuarios() {

    var options = {

        url: function(bi) {
            return 'api/buscacapturista';
        },

        getValue: function(element) {
            return (element.nombre + " " + element.apellido_paterno + " " + element.apellido_materno);


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

function btn_filtrar() {


    var inicio = $("#inicio").val();
    var fin = $("#fin").val();
    var nombre = $("#nombre").val();
    // console.log(inicio);
    obj_filtro = { 'inicio': inicio, 'fin': fin, 'nombre': nombre };
    cargar_dato(obj_filtro);
}

function cargar_dato(dato) {

    var lista = $("#lista_checadas");
    lista.html("");
    var linea_cargar = $("<tr><td colspan='22'>Cargando espere un momento, por favor. <i class='fa fa-spin fa-refresh'></i></td></tr>");
    lista.append(linea_cargar);

    jQuery.ajax({
        data: dato,
        type: "GET",
        dataType: "json",
        url: './api/checadas',
    }).done(function(data, textStatus, jqXHR) {
        lista.html("");
        //console.log(data.checadas);
        if (data.checadas.length == 0) {

            var linea = $("<tr ></tr>");
            var campo1 = $("<td colspan='20'>No se encontraron resultados</td>");
            linea.append(campo1);
            lista.append(linea);

        }
        var num = 0;
        console.log(data.checadas);
        $.each(data.checadas, function(index, value) {
            num += 1;
            var linea = $("<tr></tr>");
            var campo = $("<td>" + num + "</td>");
            var campo1 = $("<td>" + value.Badgenumber + "</td>");
            var campo2 = $("<td>" + value.Name + "</td>");
            var campo3 = $("<td>" + value.CHECKTIME.substr(0, 10) + "</td>");
            var campo4 = $("<td>" + value.CHECKTIME.substr(11, 8) + "</td>");

            /*  var campo3 = $("<td>" + value.TITLE + "</td>"); */
            linea.append(campo, campo1, campo2, campo3, campo4);
            lista.append(linea);



        });

    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}

function generar_reporte() {
    var anio = $("#anio").val();
    var mes = $("#mes").val();
    var direccion = $("#direccion").val();
    var nombre = $("#nombre").val();
    var quincena = $("#quincena").val();

    /*obj_filtro = { 'anio': anio, 'mes': mes, 'tipo_trabajador': tipo_trabajador, 'quincena': quincena };*/


    win = window.open('./api/reporte-direccion?anio=' + anio + "&mes=" + mes + "&direccion=" + direccion + "&nombre=" + nombre + "&quincena=" + quincena, '_blank');
}