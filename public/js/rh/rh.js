var datos_credencializacion;
var iden;
var datos_checadas_mes;
var resumen_checadas;
var validacion;
var urlchecadas = "../api/consulta-asistencia";
var dato;
var inicio;
var fin;
var jIni;
var ban_url = 0;


arreglo_dias = Array("", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO", "DOMINGO")

$(document).ready(function() {

    dato = getParameterByName();


    inicio = $("#inicio").val();
    fin = $("#fin").val();

    var urlrh = "http://credencializacion.saludchiapas.gob.mx/ConsultaRhPersonal.php";

    cargar_dato(dato, urlrh);

    $("#datos_filtros_checadas").html("<tr><td colspan='5'><i class='fa fa-refresh fa-spin'></i> Cargando, Espere un momento por favor</td></tr>");

});

function cargar_dato(dato, urlrh) {

    jQuery.ajax({
        data: { 'buscar': dato },
        type: "GET",
        dataType: "json",
        url: urlrh,
    }).done(function(data, textStatus, jqXHR) {
        datos_credencializacion = data[0];
        cargar_blade_credencializacion();

        cargar_datos_checadas(urlchecadas);


    }).fail(function(jqXHR, textStatus, errorThrown) {
        if (console && console.log) {

            alert("Error en la carga de Datos, acuda a Sistematización y Credencialización: " + " " + textStatus);
            window.location.replace("http://induccion.saludchiapas.gob.mx/");
        }
    });

    for (var i = 2019; i < 2030; i++) {
        $("select[name=anio]").append(new Option(i, i));
    }
}

function cargar_datos_checadas(urlchecadas) {

    $("#datos_filtros_checadas").html("<tr><td colspan='5'><i class='fa fa-refresh fa-spin'></i> Cargando, Espere un momento por favor</td></tr>");
    jQuery.ajax({
        data: {
            'rfc': dato,
            'fecha_inicio': inicio,
            'fecha_fin': fin,
            'soli': '1'
        },
        type: "GET",
        dataType: "json",
        url: urlchecadas,
    }).done(function(data, textStatus, jqXHR) {
        $("#inicio").val(data.fecha_inicial);
        $("#fin").val(data.fecha_final);
        datos_checadas_mes = data.data;
        resumen_checadas = data.resumen[0];
        validacion = data.validacion;


        if (validacion != null) {
            cargar_blade_checadas();
            cargar_blade_resumen();

        } else {

            var resumen = $("#resumen");
            var checadas = $("#checadas");
            resumen.html("");
            checadas.html("");
            $("#resumen").append("<div class=card-body> <h1 class=card-title align=center>Acudir a Sistematización</h1> <hr> <div class=row> <div class='col-md-6 col-sm-6' style='text-align:left'><img src=../images/salud.png class=img-fluid flex alt=Responsive image width=50%></div> <div class='col-md-6 col-sm-6' style='text-align:right' ><img src=../images/chiapas.png class=img-fluid flex alt=Responsive image width=50%></div></div> </div>");

            document.getElementById('modal').click();


        }



    }).fail(function(jqXHR, textStatus, errorThrown) {
        if (console && console.log) {

            alert("No se cargo la lista de asistencia " + " " + textStatus);
        }
    });
}

function cargar_blade_credencializacion() {
    //


    $("#Nombre").text(datos_credencializacion.Nombre);
    $("#Adscripcion_Area").text(datos_credencializacion.DesPuesto);
    $("#codigo").text(datos_credencializacion.codTab);
    $("#Direccion").text(datos_credencializacion.Direccion);
    $("#Adscripcion_Area").text(datos_credencializacion.Adscripcion_Area);
    $("#TipoSangre").text(datos_credencializacion.TipoSangre);
    $("#Curp").text(datos_credencializacion.Curp);
    $("#Rfc").text(datos_credencializacion.Rfc);
    $("#Clue").text(datos_credencializacion.Clue);

    if (datos_credencializacion.tieneFoto == 0) {

        $("#foto").attr('src', '../images/usuarios.jpg');
    } else {
        $("#foto").attr("src", "http://credencializacion.saludchiapas.gob.mx/images/credenciales/" + datos_credencializacion.id + "." + datos_credencializacion.tipoFoto);

    }
}


function cargar_blade_checadas() {

    if (datos_credencializacion.CodTab.substring(0, 2) == "CF" && resumen_checadas.horastra == 6)
        alert("Favor de Acudir al Área de Sistematización para verificar su horario");
    var table = $("#datos_filtros_checadas");
    table.html("");
    $.each(datos_checadas_mes, function(index, value) {

        icono = "<i class='fa fa-check' style='color:green'></i>";
        if (value.validacion == 0 || value.checado_entrada.includes('Retardo'))
            icono = "<i class='fa fa-close' style='color:red'><a type='button' class='btn btn-link' style='color:yellow' data-toggle='modal' data-target='#modal_justificante' onclick='cargar_formato(\"" + value.jorini + "\",\"" + value.jorfin + "\")'><i class='fa fa-id-card-o' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Generar Incidencia'></i></a><a type='button' class='btn btn-link' style='color:yellow' data-toggle='modal' data-target='#agregar_entrasal' onclick='agregar_entsal(\"" + value.jorini + "\",\"" + value.jorfin + "\")'></i></a></i>";
        // icono = "<i class='fa fa-close' style='color:red'><a type='button' class='btn btn-warning' style='color:blue' data-toggle='modal' data-target='#modal_justificante' onclick='cargar_formato()'>Incidencia</a></i>";
        else
        if (value.sol == 1) {
            icono = "<i class='fa fa-question-circle' style='color:red' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='En proceso de Validación'></i>";
        } else {
            icono = "<i class='fa fa-check' style='color:green'></i>";
        }
        if (value.checado_salida == value.checado_salida_fuera)
            xs = value.checado_salida;
        else
            xs = value.checado_salida + "(" + value.checado_salida_fuera + ")";

        if (value.ban_inci >= 1)
            icono2 = "<a type='button' class='btn btn-link' onclick='eliminar_in_emp(" + value.ban_inci + ")' ><i class='fa fa-eraser' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Eliminar Incidencia'></i></a>";
        else
            icono2 = " ";
        // $("#datos_filtros_checadas tr").append("<td><a type='button' class='btn btn-link' style='color:red'>Eliminar</a></td>");

        table.append("<tr><td>" + arreglo_dias[value.numero_dia] + "</td><td>" + value.fecha + "</td>" + "</td><td>" + value.checado_entrada + "</td>" + "</td><td>" + value.checado_salida + "</td> <td>" + icono + "</td><td>" + icono2 + "</td></tr>");

    })


    $('#datos_filtros_checadas tr').hover(function() {
        $(this).addClass('hover');
    }, function() {
        $(this).removeClass('hover');
    });

}

function cargar_blade_resumen() {

    //$("#jorini").text(datos_checadas_mes[0].jorini);
    $("#Ident").text(validacion.Badgenumber);

    $
    $("#Día_Económico").text(resumen_checadas.Día_Económico);
    $("#Falta").text(resumen_checadas.Falta);
    $("#Omisión_Entrada").text(resumen_checadas.Omisión_Entrada);
    $("#Omisión_Salida").text(resumen_checadas.Omisión_Salida);
    $("#Onomástico").text(resumen_checadas.Onomástico);
    $("#Pase_Salida").text(resumen_checadas.Pase_Salida);
    $("#Retardo_Mayor").text(resumen_checadas.Retardo_Mayor);
    $("#Retardo_Menor").text(resumen_checadas.Retardo_Menor);
    $("#Vacaciones_2018_Invierno").text(resumen_checadas.Vacaciones_2018_Invierno);
    $("#Vacaciones_2018_Primavera_Verano").text(resumen_checadas.Vacaciones_2018_Primavera_Verano);
    $("#Vacaciones_2019_Invierno").text(resumen_checadas.Vacaciones_2019_Invierno);
    $("#Vacaciones_2019_Primavera_Verano").text(resumen_checadas.Vacaciones_2019_Primavera_Verano);
    $("#Vacaciones_Extra_Ordinarias").text(resumen_checadas.Vacaciones_Extra_Ordinarias);
    $("#Vacaciones_Mediano_Riesgo").text(resumen_checadas.Vacaciones_Mediano_Riesgo);

}

function filtrar_checadas() {
    inicio = $("#inicio").val();
    fin = $("#fin").val();
    cargar_datos_checadas(urlchecadas);
    //cargar_blade_checadas();


}

function cargar_formato(jini, jfin) {

    document.getElementById('justificante').click();
    ban_url = 1;  
    diaslab = validacion.horarios[0].detalle_horario;   

    cargar_incidencias();
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);

    //console.log(datos_credencializacion);

    //mGZmt316o4aBibe5mw==
    //iGZ3wn9zo4aAjsi9lw==



    $("#nombre_empleado").val(datos_credencializacion.Nombre);
    $("#direccion_departamento").val(datos_credencializacion.Direccion);
    $("#departamento").val(datos_credencializacion.Adscripcion_Area);
    $("#no_tarjeta").val(validacion.Badgenumber);
    $("#userid").val(validacion.USERID);
    $("#fecha").val(today);
    $("#att").val(datos_credencializacion.Nombre);
    $("#f_ini").val(jini);
    $("#f_fin").val(jfin);

    $("#horario_emp").val(jini.substring(16, 11) + '-' + jfin.substring(16, 11));


}

function getParameterByName() {
    var ruta_completa = location.pathname;
    var splits = ruta_completa.split("/");
    //console.log(ruta_completa);
    return splits[(splits.length - 1)];
}