function cargar_grid(dato) {
    var lista = $("#lista_personal");
    lista.html("");
    var linea_cargar = $("<tr><td colspan='5'>Cargando espere un momento, por favor. <i class='fa fa-spin fa-refresh'></i></td></tr>");
    lista.append(linea_cargar);

    jQuery.ajax({
        data: dato,
        type: "GET",
        dataType: "json",
        url: './api/cardex',
    }).done(function(data, textStatus, jqXHR) {
        lista.html("");
        //console.log(data.usuarios.length);
        //console.log(data.usuarios);
        if (data.usuarios.length == 0) {
            var linea = $("<tr ></tr>");
            var campo1 = $("<td colspan='5'>No se encontraron resultados</td>");
            linea.append(campo1);
            lista.append(linea);

        }
        var contador = 0;
        $.each(data.usuarios, function(index, value) {
            var linea = $("<tr ></tr>");
            var campo1 = $("<td>" + value.Badgenumber + "</td>");
            var campo2 = $("<td>" + value.TITLE + "</td>");
            var campo3 = $("<td>" + value.sirh__empleados.curp + "</td>");
            var campo4 = $("<td>" + value.sirh__empleados.cr + "</td>");
            var campo5 = $("<td>" + value.sirh__empleados.nombre + "</td>");
            var campo6 = $("<td style='text-align:center'><input type='checkbox' class='empleado' value='" + value.Badgenumber + "'></td>");
            var campo7 = $("<td a type='button' class='btn btn-link' style='color:blue' data-toggle='modal' data-target='#modal_incidencias' onclick='btn_x(\"" + value.USERID + "\")'><i class='fa fa-id-card-o' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Ver Incidencias'></i></a></td>");

            //
            linea.append(campo1, campo2, campo3, campo4, campo5, campo6, campo7);

            lista.append(linea);
        });

    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}

function btn_filtrar() {
    var filtro = "filtro=" + $("#filtro").val();
    cargar_grid(filtro);
}

function cargar_incidencias(dato) {


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

            var campo2 = $("<td>" + value.siglas.LeaveName + "</td>");
            var campo3 = $("<td>" + value.STARTSPECDAY.substr(0, 16) + "</td>");
            var campo4 = $("<td>" + value.ENDSPECDAY.substr(0, 16) + "</td>");
            var campo5 = $("<td>" + value.YUANYING + "</td>");
            var campo6 = $("<td>" + value.DATE.substr(0, 19) + "</td>");
            var campo1 = $("<td>" + value.capturista.nombre + "</td>");



            linea.append(campo, campo2, campo3, campo4, campo5, campo6, campo1);
            lista.append(linea);


        });
        tr.removeChild();




    }).fail(function(jqXHR, textStatus, errorThrown) {

    });


}


function btn_x(id) {


    tipo = $("#tipopermiso").val();
    user = id;
    anio = $("#anio").val();

    inicio = $("#anio").val() - 1 + '-10-01';
    fin = $("#anio").val() + '-09-30';

    //alert("inicio " + inicio + "  fin  " + fin);



    obj_datos = { 'inicio': inicio, 'fin': fin, 'user': user };
    cargar_incidencias(obj_datos);
}

function generar_reporte() {
    var valor = $('input:checkbox[class=empleado]:checked').val();
    var anio = $("#anio").val();
    console.log(anio);
    win = window.open('./api/reporte-cardex?empleado=' + valor + '&anio=' + anio, '_blank');
}