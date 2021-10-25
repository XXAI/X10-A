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

            linea.append(campo1, campo2, campo3, campo4, campo5, campo6);

            lista.append(linea);
        });

    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}

function btn_filtrar() {
    var filtro = "filtro=" + $("#filtro").val();
    cargar_grid(filtro);
}

function generar_reporte() {
    var valor = $('input:checkbox[class=empleado]:checked').val();
    var anio = $("#anio").val();
    console.log(anio);
    win = window.open('./api/reporte-cardex?empleado=' + valor + '&anio=' + anio, '_blank');
}