var urlchecadas = "./api/consulta-asistencia";
var dato, impre = 0;
var date = new Date();
var resumen_checadas;
var diaslab;

var diaeco;
var onomastico, omisiones_total;
var pasesal;
var inicio, base, maxid;
var fin;
var xini, xfin;
var id, idcap, fecha, tipo, pagoGuardiaTotal = 0;
var id_x;
var rfc_x, tipotra;
var id_inci;
var msj, ban_url;
var mes_nac, idempleado, idhorario;
var tipo_incidencia, date_1, date_2, razon, diff_in_days, diff_in_hours, diff, fec_com, bandera, msj, val_in, yy, url_emp;
var banemp = 0;
var leyenda = 1;
let permisos = [];
arreglo_diafest = Array();
arreglo_dias = Array("", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO", "DOMINGO")
arreglo_mes = Array("", "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE")

$(document).ready(function() {

    $("#form-hora").hide();
    superuser();
    cargar_catalogo_base();
    cargar_empleados('');
    $("#buscar").keypress(function(e) {
        if (e.which == 13) {
            btn_filtrar();
        }
    });
    cargar_horarios();
    $('#btn-mod-hora').hide();
    //cargar_incidencias()
    idcap = $("#id_user").val();
    $('#incidencia').blur(function() {
        obtener_incidencias();
    });
    $("#agregar_incidencia").on('hidden.bs.modal', function() {
        $("#code_in").val("");
    });
    $("#agregar_entrasal").on('hidden.bs.modal', function() {
        //$("#code_in").val("");
    });

    $("#agregar_empleado").on('hidden.bs.modal', function() {
        limpia_empleados();
        //  $("#tipotra").empty();
    });
    // console.log(idcap);




});

function superuser() {
    var superuser = $("#super_user").val();
    if (superuser == 1) {
        document.getElementById('cat_base').disabled = false;
    } else {
        document.getElementById('cat_base').disabled = true;
    }
}

function mostrar_form_hora() {
    $("#form-hora").show();
}

function cargar_empleados(dato) {

    var table = $("#empleados").html('');
    jQuery.ajax({
        data: { 'buscar': dato },
        type: "GET",
        dataType: "json",
        url: './api/empleado',
    }).done(function(data, textStatus, jqXHR) {
        //console.log(data);
        base = data.base.id;
        maxid = data.max;
        cargar_datos_empleado(data.usuarios.data);
        festivos(data.festivos);
    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}

function festivos(diasfestivos) {
    $.each(diasfestivos, function(key, value) {
        const arreglo_diafest = value.STARTTIME.substr(0, 10);

        //console.log(arreglo_diafest);
    });
}



function festivos() {
    jQuery.ajax({
        type: "GET",
        dataType: "json",
        url: './api/empleado',
    }).done(function(data, textStatus, jqXHR) {
        arreglo_diafest = data.festivos;
        // console.log(arreglo_diafest);
        /*  $.each(data.festivos, function(key, value) {
             arreglo_diafest = value.STARTTIME.substr(0, 10);
             //console.log(arreglo_diafest);
         }); */
    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}


function cargar_horarios() {

    var options = {

        url: function(bh) {
            return "./api/empleado/fetch";
        },

        getValue: function(element) {
            return element.NAME;

        },
        list: {
            onSelectItemEvent: function() {
                var selectedItemValue = $("#horario").getSelectedItemData().NUM_RUNID;
                $("#code").val(selectedItemValue).trigger("change");

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
            data.bh = $("#horario").val();
            return data;
        },


        requestDelay: 400,
        theme: "plate-dark"
    };

    $("#horario").easyAutocomplete(options);

}


function cargar_incidencias() {

    if (ban_url == 1) {
        url_in = '../api/empleado/tipoincidencia';
    } else {
        url_in = './api/empleado/tipoincidencia'
    }

    var options = {

        url: function(bi) {
            return url_in;
        },

        getValue: function(element) {
            return element.LeaveName;

        },
        list: {
            onSelectItemEvent: function() {
                var selectedItemValue = $("#incidencia").getSelectedItemData().LeaveId;
                $("#code_in").val(selectedItemValue).trigger("change");

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
            data.bi = $("#incidencia").val();

            return data;
        },


        requestDelay: 400,
        theme: "plate-dark"
    };

    $("#incidencia").easyAutocomplete(options);


}



function obten_fecnac(rfc_x) {

    onomastico = rfc_x.substr(4, 6);
    onomastico = onomastico.substr(2, 2) + "-" + onomastico.substr(4, 2);
    // console.log("   ono   "+onomastico);
}


function limpia_empleados() {
    $("#name").val('');
    $("#rfc").val('');
    $("#sexo").val('');
    $("#fechaing").val('');
    $("#codigo").val('');
    $("#clues").val('');
    $("#area").val('');


    $("#horario").val('');
    $("#code").val('');

}

function cargar_departamentos() {
    $("#tipotra").empty();
    if (base == 5) {
        document.getElementById("biometrico").style.display = "block";
        $("#biome").val(maxid);
    } else {
        document.getElementById("biometrico").style.display = "none";
    }
    $.ajax({
        type: "GET",
        url: './api/empleado',
        async: false,
        dataType: "json",
        success: function(data) {
            $.each(data.departamentos, function(key, registro) {
                $("#tipotra").append("<option value=" + registro.id + ">" + registro.descripcion + "</option>");
            });
        },
        error: function(data) {
            alert('error');
        }
    });


}

function cargar_horarios_empleado(horarios) {
    var table = $("#empleado-hora").html('');

    $.each(horarios, function(key, value) {

        //console.log(value);
        var linea = $("<tr></tr>");
        var campo1 = $("<td>" + value.nombre_horario[0].NAME + "</td>");
        var campo2 = $("<td>" + moment(value.STARTDATE).format('YYYY-MM-DD') + "</td>");
        var campo3 = $("<td>" + moment(value.ENDDATE).format('YYYY-MM-DD') + "</td>");
        //]
        var campo4 = $("<a type='button' class='btn btn-link'' onclick='modifica_horario(\"" + value.NUM_OF_RUN_ID + "\",\"" + value.STARTDATE + "\",\"" + value.ENDDATE + "\",\"" + value.nombre_horario[0].NAME + "\", \"" + value.id + "\")'><i class='fa fa-edit' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Editar Horario'></i></a><a type='button' class='btn btn-link'' onclick='eliminar_hora_emp(\"" + value.id + "\")'><i class='fa fa-trash' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Eliminar Horario'></i></a>");

        // moment(data.data.HIREDDAY).format('YYYY-MM-DD'));

        linea.append(campo1, campo2, campo3, campo4);
        table.append(linea);

    });

}

function save_horario() {



    var ini_fec = $("#ini_fec").val();
    var fin_fec = $("#fin_fec").val();
    var code = $("#code").val();



    //console.log("idhorario: " + idhorario + "    inifec: " + ini_fec + "    fechafin:  " + fin_fec)
    $.ajax({
        type: 'GET',
        //"./api/buscaempleado/" + idempleado
        url: "api/hora-empleado/" + idhorario,
        data: { idhorario: idhorario, ini_fec: ini_fec, fin_fec: fin_fec, code: code },
        success: function(data) {
            swal("Exito!", "El registro se ha modeficado!", "success");
            $('#btn-mod-hora').hide();
            document.getElementById('btn-save-emp').disabled = false;
            editEmpleado(idempleado)
            $("#form-hora").hide();


        },
        error: function(data) {
            swal("Error!", "No se registro ningun dato!", "error");


        }
    })





}

function modifica_horario(idho, inifec, finfec, idh, id) {

    idhorario = id;
    $("#form-hora").show();
    $('#btn-mod-hora').show();


    document.getElementById('btn-save-emp').disabled = true;
    ini_fec = moment(inifec).format('YYYY-MM-DD');
    fec_fin = moment(finfec).format('YYYY-MM-DD');
    $("#ini_fec").val(ini_fec);
    $("#fin_fec").val(fec_fin);
    $("#horario").val(idh);
    $("#code").val(idho);
    cargar_horarios();


    //ini_fec = ini_fec.substr(0, 10) + " 00:00:00.00";
    //   fin_fec = moment(fin_fec).format();

}

function cargar_datos_empleado(datos) {
    var table = $("#empleados");
    //console.log(datos);
    $.each(datos, function(key, value) {
        var linea = $("<tr></tr>");
        var campo1 = $("<td>" + value.Badgenumber + "</td>");
        var campo2 = $("<td>" + value.Name + "</td>");
        var campo3 = $("<td>" + value.TITLE + "</td>");
        //console.log("gggogogogogogogogogo");
        if (value.horarios.length > 0) {
            var hentrada = value.horarios[0].detalle_horario[0].STARTTIME;
            var hsalida = value.horarios[0].detalle_horario[0].ENDTIME;
            hentrada = hentrada.substring(16, 11);
            hsalida = hsalida.substring(16, 11);
            //  diaslab = (value.horarios[0].detalle_horario);

            // diaslab = (value.horarios);
            // console.log(diaslab[mike]);
            var campo5 = $("<td>" + hentrada + " - " + hsalida + "</td>");
            if (idcap == 15 || idcap == 13 || idcap == 10) {
                var campo6 = $("<a type='button' class='btn btn-link'' data-toggle='modal' data-target='#modal_kardex' onclick='incidencia(\"" + value.USERID + "\",\"" + value.Badgenumber + "\",\"" + value.Name + "\",\"" + value.TITLE + "\",\"" + hentrada + "\",\"" + hsalida + "\",\"" + diaslab + "\")'><i class='fa fa-eye' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Ver Checadas'></i></a>");
            } else {

                var campo6 = $("<a type='button' class='btn btn-link'' data-toggle='modal' data-target='#modal_kardex' onclick='incidencia(\"" + value.USERID + "\",\"" + value.Badgenumber + "\",\"" + value.Name + "\",\"" + value.TITLE + "\",\"" + hentrada + "\",\"" + hsalida + "\",\"" + diaslab + "\")'><i class='fa fa-eye' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Ver Checadas'></i></a><a type='button' class='btn btn-link' data-toggle='modal' data-target='#agregar_empleado' onclick='editEmpleado(" + value.USERID + ")'><i class='fa fa-edit' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Editar Empleado'></i></a>");
            }
        } else
            var campo4 = $("<td>Sin Horario</><a type='button' class='btn btn-link' data-toggle='modal' data-target='#agregar_empleado' onclick='editEmpleado(" + value.USERID + ")'><i class='fa fa-edit' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Editar Empleado'></i></a>");

        linea.append(campo1, campo2, campo3, campo4, campo5, campo6);
        table.append(linea);


    });

}



function btn_filtrar() {
    var buscar = $("#buscar").val();
    cargar_empleados(buscar);


}

function kardex_empleado(rfc) {
    cargar_kardex();
    dato = rfc;
    inicio = $("#inicio").val();
    fin = $("#fin").val();
    cargar_dato(rfc)

}

function sacadias() {

    mes_nac = onomastico.substr(0, 2);
    if (mes_nac < 10)
        mes_nac = mes_nac.substr(1, 1);


}

function obtenerDiasLab(idho) {
    jQuery.ajax({
        data: { 'buscar': dato },
        type: "GET",
        dataType: "json",
        url: './api/empleado',
    }).done(function(data, textStatus, jqXHR) {
        cargar_datos_empleado(data.usuarios.data);


    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}

function incidencia(id, iduser, nombre, rfc, jini, jfin, diaslab) {


    console.log(id);
    editEmpleado(id);
    obten_fecnac(rfc);
    sacadias();
    var mes = date.getMonth() + 1; //obteniendo mes
    var dia = date.getDate(); //obteniendo dia
    var ano = date.getFullYear(); //obteniendo año
    if (dia < 10)
        dia = '0' + dia;
    if (mes < 10)
        mes = '0' + mes;
    document.getElementById('checadas_modal').click();
    dato = rfc;
    inicio = $("#inicio").val = "01-" + mes + "-" + ano;
    fin = $("#fin").val = dia + "-" + mes + "-" + ano;
    cargar_datos_checadas(urlchecadas)
    $("#hentra").html(jini);
    $("#hsal").html(jfin);
    id_x = id;
    $("#iduser").html(iduser);
    $("#nombre").html(nombre);

    //console.log(diaslab);


}

function guardar_entrasal() {
    obtener_omisiones();




}

function guardar_empleado() {

    var name = $("#name").val();
    var rf = $("#rfc").val();
    var sexo = $("#sexo").val();
    var codigo = $("#codigo").val();
    var clues = $("#clues").val();
    var area = $("#area").val();
    var fechaing = $("#fechaing").val();
    var biome = $("#biome").val();
    tipotra = $("#tipotra").val();
    var ini_fec = $("#ini_fec").val();
    var code = $("#code").val();
    var fin_fec = $("#fin_fec").val();
    var street = $('select[name="tipotra"] option:selected').text();
    var mmi, interino;
    if ($("#mmi").prop('checked')) {
        mmi = 0;
    } else { mmi = 1; }
    if ($("#interino").prop('checked')) {
        interino = 0;
    } else { interino = 1; }
    var city;
    if (tipotra == 1)
        city = "416";
    else
        city = street.substr(0, 3);
    var fecnac = rf.substr(4, 6);
    if (fecnac.substr(0, 2) >= 20)
        fecnac = "19" + fecnac.substr(0, 2) + "-" + fecnac.substr(2, 2) + "-" + fecnac.substr(4, 2) + " 00:00:00.00";
    else
        fecnac = "20" + fecnac.substr(0, 2) + "-" + fecnac.substr(2, 2) + "-" + fecnac.substr(4, 2) + " 00:00:00.00";

    fechaing = moment(fechaing).format();
    fechaing = fechaing.substr(0, 10) + " 00:00:00.00";
    ini_fec = moment(ini_fec).format();
    ini_fec = ini_fec.substr(0, 10) + " 00:00:00.00";
    fin_fec = moment(fin_fec).format();
    fin_fec = fin_fec.substr(0, 10) + " 00:00:00.00"

    var tipo;
    // console.log(banemp);
    if (banemp == 1) {
        url_emp = "api/edita-empleado/" + idempleado;
        tipo = 'GET';

    } else {
        url_emp = "api/guarda-empleado";
        tipo = 'POST';
    }
    $.ajax({
        type: tipo,
        url: url_emp,
        data: { biome: biome, name: name, rf: rf, sexo: sexo, fechaing: fechaing, fecnac: fecnac, codigo: codigo, clues: clues, area: area, tipotra: tipotra, street: street, city: city, ini_fec: ini_fec, fin_fec: fin_fec, code: code, mmi: mmi, interino: interino },
        success: function(data) {

            swal("Exito!", data.mensaje, "success");
            limpia_empleados();
            /* $("#agregar_empleado").find("input,textarea,select").val("");
            $("#agregar_empleado input[type='checkbox']").prop('checked', false).change(); */
            $("#empleado-hora").html('');
            $("#tipotra").empty();
            $('#agregar_empleado').modal('hide');

            cargar_empleados('');



        },
        error: function(xhr) {
            var qw = '';
            var pinta = "";
            var res = xhr.responseJSON;
            if ($.isEmptyObject(res) == false) {
                $.each(res.errors, function(key, value) {
                    console.log($('#' + key))
                    $('#' + key).css("border", "1px solid red");

                    qw = value + "\n" + qw + "\n";


                });
                swal("Error", qw, "error");


            }
        }
    })




}



function mostrarMensaje(mensaje) {
    $("#divmsg").empty();
    $("#divmsg").append("<p>" + mensaje + "</p>");
    //  $("#divmsg").show(500);
    // $("#divmsg").hide(30000);

}

function mostrarMensaje2(mensaje) {
    $("#divmsg2").empty();
    $("#divmsg2").append("<p>" + mensaje + "</p>");
    //  $("#divmsg").show(500);
    // $("#divmsg").hide(30000);

}

function generar_inci(jini, jfin) {

    ban_url = 0;
    val_in = 0;
    document.getElementById('btn_save_inci').innerText = "Guardar";
    cargar_incidencias();
    $("#id").val(id_x);
    $("#f_ini").val(jini);
    $("#f_fin").val(jfin);
    $("#razon").val('');
    $("#incidencia").val('');
    var mensaje = "  ";
    mostrarMensaje(mensaje);
    obtener_incidencias();
    // obtener_justificantes();



}

function agregar_entsal(jini, jfin) {
    $("#id").val(id_x);
    xini = jini;
    xfin = jfin;
    //fecha = xini;

    //obtener_omisiones();
    //console.log(omisiones_total.omisiones.length);


}

function guarda_omision() {
    id = $("#id").val();

    var fecha_ing = moment($("#fecha_reg").val());
    var tipo_registro = $("#tipo_es").val();
    var razon = $("#refe").val();
    var fing = moment(fecha_ing).format();
    fing = fing.substr(0, 10) + " " + fing.substr(11, 8) + ".00";

    if (razon == "") {
        swal("Verifique!", "Este campo es necesario", "error");
        $("#refe").focus();
    } else {

        $.ajax({
            type: 'POST',
            url: "api/guarda-entrasal",
            data: { id: id, fing: fing, tipo_registro: tipo_registro, razon: razon, idcap: idcap },
            success: function(data) {
                swal("Exito!", "El registro se ha guardado!", "success");
                $("#refe").val('');
                $("#tipo_es").val('');
                $("#agregar_entrasal").modal('hide');
                document.getElementById('filtro_check').click();



            },
            error: function(data) {
                swal("Error!", "No se registro ningun dato!", "error");


            }
        })

    }


}



function obtener_omisiones() {
    //omision = [];
    id = $("#id").val();
    tipoomi = $("#tipo_es").val();
    fecha = xini;
    var algo = 0;
    oentrada = 0;
    osalida = 0;

    $.ajax({
        type: "GET",
        url: "./api/omisiones/",
        data: { id: id, fecha: fecha, tipoomi: tipoomi },
        dataType: "json",
        success: function(data) {
            // console.log(data);
            $.each(data.omisiones, function(key, value) {

                if (value.CHECKTYPE == 'I') {
                    oentrada += 1;
                } else { osalida += 1; }
                if (fecha.substr(0, 10) == value.CHECKTIME.substr(0, 10)) {
                    algo = 1;
                }


            });


            omisiones_total = data;
            console.log("oentrada: " + oentrada + " osalida: " + osalida + " algo: " + algo);

            if (oentrada < 2 && osalida < 2 && algo == 0) {
                /*  var mensaje = "  ";
                 mostrarMensaje2(mensaje);
                 $('#btn_save_entrasal').attr('disabled', false); */

                guarda_omision();
            } else {

                swal("¡La Omisión no se puede ingresar porque ya agotó la cantidad permitida!", {
                    icon: "warning",
                });

                /*  algo = 0;
                     oentrada = 0;
                     osalida = 0; */
                /* var mensaje = "Ya se agoto la cantidad de omisiones";
                $('#btn_save_entrasal').attr('disabled', true);
                mostrarMensaje2(mensaje); */
            }





        },
        error: function(data) {
            alert('error');
        }
    });
}

function obtener_justificantes(fini, ffin) {
    fini = $("#f_ini").val();
    ffin = $("#f_fin").val();
    codein = $("#code_in").val();
    id = $("#id").val();
    fecha = xini;
    permisos = [];
    jQuery.ajax({
        data: { id: id, fini: fini, ffin: ffin, codein: codein },
        type: "GET",
        dataType: "json",
        url: "./api/permisos/",

    }).done(function(data) {

        data.permisos.forEach(element => {
            permisos.push(element);
        });
        console.log(permisos);

        if (permisos.length > 0) {

            swal("¡La incidencia no se puede ingresar porque la fecha ya esta asignada a una incidencia!", {
                icon: "warning",
            });
        } else {
            validando_incidencia();
            if (bandera == 1) {

                if (ban_url == 1) { save_justi_emp(); } else {
                    if (val_in == 0) {
                        save_justi_emp();

                    } else { acepta_incidencia(); }

                }

            } else {
                swal("Error!", msj + "!", "error");
            }
        }

    }).fail(function(jqXHR, textStatus, errorThrown) {

    });




}

function obtener_incidencias() {

    fini = $("#f_ini").val();
    ffin = $("#f_fin").val();
    codein = $("#code_in").val();
    id = $("#id").val();
    fecha = xini;
    $.ajax({
        type: "GET",
        url: "./api/omisiones/",
        data: { id: id, fini: fini, ffin: ffin, codein: codein },
        dataType: "json",
        success: function(data) {
            // console.log(data);
            $.each(data.diasJustificados, function(key, value) {

                // pagoGuardia = data.diasJustificados.length;


            });



        },
        error: function(data) {
            alert('error');
        }
    });
}

function sel_tiporeg(tiporeg) {

    agregar_entsal(xini, xfin)
    if (tiporeg == "I") {
        $("#fecha_reg").val(xini);
    } else {
        $("#fecha_reg").val(xfin);
    }
    //obtener_omisiones();




}

function probando() {
    cargar_datos_checadas(urlchecadas);

}

function cargar_datos_checadas(urlchecadas) {

    $("#datos_filtros_checadas").html("<tr><td colspan='5'><i class='fa fa-refresh fa-spin'></i> Cargando, Espere un momento por favor</td></tr>");

    jQuery.ajax({
        data: {
            'id': dato,
            'fecha_inicio': inicio,
            'fecha_fin': fin
        },
        type: "GET",
        dataType: "json",
        url: urlchecadas,

    }).done(function(data, textStatus, jqXHR) {
        // console.log("aca", data);
        $("#inicio").val(data.fecha_inicial);
        $("#fin").val(data.fecha_final);

        datos_checadas_mes = data.data;
        resumen_checadas = data.resumen[0];
        // console.log(resumen_checadas.resumen2);
        validacion = data.validacion;
        //  console.log(datos_checadas_mes);

        if (validacion != null) {


            cargar_blade_checadas();


        } else {
            var checadas = $("#checadas");
            checadas.html("");
            $("#resumen").append("<div class=card-body> <h1 class=card-title align=center>Acudir a Sistematización</h1> <hr> <div class=row> <div class='col-md-6 col-sm-6' style='text-align:left'><img src=../images/salud.png class=img-fluid flex alt=Responsive image width=50%></div> <div class='col-md-6 col-sm-6' style='text-align:right' ><img src=../images/chiapas.png class=img-fluid flex alt=Responsive image width=50%></div></div> </div>");
            document.getElementById('modal').click();
        }



    }).fail(function(jqXHR, textStatus, errorThrown) {
        if (console && console.log) {
            swal("Error!", "No se cargo sus datos, Verificar horario ", "error");
            //  alert("No se cargo sus datos, Verificar horario en el area de control de asistencia  " + textStatus);
        }
    });
}

function incluir_leyenda() {

    if ($("#leyenda").prop('checked')) {
        leyenda = 1;
    } else {
        leyenda = 0;
    }

    console.log("val", leyenda);


}

function sel_inci(valor) {
    var mensaje;

    // console.log(datos_checadas_mes);
    switch (parseInt(valor)) {

        case 1:
            console.log(resumen_checadas.Pase_Salida);
            pasesal = 6 - resumen_checadas.Pase_Salida;
            mensaje = "Tiene " + pasesal + " horas disponibles para pase de salida, Recuerde que solo puede tomar máximo 2 horas en la jornada";
            mostrarMensaje(mensaje);

            //swal("Aviso","Tiene "+pasesal+ " horas disponibles para pase de salida, Recuerde que solo puede tomar maximo 2 horas en un dia");
            break;
        case 6:
            if (diaslab.length == 5) {
                console.log("economico:  " + resumen_checadas.Día_Económico);
                diaeco = 2 - resumen_checadas.Día_Económico;
                mensaje = "Tiene " + diaeco + " dia(s) disponible(s) para económico, Recuerde que solo puede tomar máximo 2 al mes";
            } else {
                diaeco = 1 - resumen_checadas.Día_Económico;
                mensaje = "Tiene " + diaeco + " dia(s) disponible(s) para económico, Recuerde que solo puede tomar máximo 1 al mes";
            }
            mostrarMensaje(mensaje);

            break;
        case 10:

            mensaje = "Su onomástico es el: " + onomastico.substr(3, 2) + " de " + arreglo_mes[mes_nac] + " No se puede tomar en fecha diferente";
            mostrarMensaje(mensaje);

            break;

        case 22:
            // $('#f_fin').attr('disabled', true);


            break;
        default:
            mensaje = "  ";
            mostrarMensaje(mensaje);
    }

}

function cargar_blade_checadas() {


    var table = $("#datos_filtros_checadas");
    var xe = 'SIN REGISTRO';;
    table.html("");
    $.each(datos_checadas_mes, function(index, value) {
        //  console.log(datos_checadas_mes);
        console.log(value);

        icono = "<i class='fa fa-check' style='color:green'></i>";

        if (value.validacion == 0 || value.checado_entrada.includes('Retardo') || value.faltaxmemo != 0)
            icono = "<i class='fa fa-close' style='color:red'><a type='button' class='btn btn-link' style='color:blue' data-toggle='modal' data-target='#agregar_incidencia' onclick='generar_inci(\"" + value.jorini + "\",\"" + value.jorfin + "\")'><i class='fa fa-id-card-o' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Generar Incidencia'></i></a><a type='button' class='btn btn-link' style='color:blue' data-toggle='modal' data-target='#agregar_entrasal' onclick='agregar_entsal(\"" + value.jorini + "\",\"" + value.jorfin + "\")'><i class='fa fa-clock-o' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Agregar Entrada o Salida'></i></a></i>";

        else {

            icono = "<i class='fa fa-check' style='color:green'></i>";
        }

        //   console.log(value.checado_entrada_fuera);

        // console.log(value.retardo);
        if (value.checado_entrada == "SIN REGISTRO" || value.retardo == 1) {


            icono = "<i class='fa fa-close' style='color:red'><a type='button' class='btn btn-link' style='color:blue' data-toggle='modal' data-target='#agregar_incidencia' onclick='generar_inci(\"" + value.jorini + "\",\"" + value.jorfin + "\")'><i class='fa fa-id-card-o' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Generar Incidencia'></i></a><a type='button' class='btn btn-link' style='color:blue' data-toggle='modal' data-target='#agregar_entrasal' onclick='agregar_entsal(\"" + value.jorini + "\",\"" + value.jorfin + "\")'><i class='fa fa-clock-o' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Agregar Entrada o Salida'></i></a></i>";
            //if (value.checado_entrada_fuera != null || value.retardo == 1 ) {
            xe = value.checado_entrada + "<i style='color:red'><br>(" + value.checado_entrada + ")</i>";
            //}
            xe = "<i style='color:red'>" + value.checado_entrada + "</i>";

            if (value.checado_entrada_fuera != null && value.checado_entrada == "SIN REGISTRO") {
                xe = "<i style='color:red'>" + value.checado_entrada_fuera + "</i>";
            }
            /* else (value.retardo == 1) 
                xe = "<i style='color:red'>" + value.checado_entrada + "</i>";
             */

        } else {
            xe = value.checado_entrada;
        }
        /*  if (value.retardo == 1) {
            xe = "<i style='color:red'>" + value.checado_entrada + "</i>";
        } else
            xe = value.checado_entrada;
 */
        // console.log(xe,"  retardo:  "+value.retardo);

        if (value.omision == undefined) {
            icono4 = " ";
        } else {
            icono4 = "<a type='button' class='btn btn-link' onclick='eliminar_omision(" + value.omision + ")' ><i class='fa fa-eraser' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Eliminar Omision-----Capturado por: " + value.captura_omision.username + "' ></i></i></a>";
        }

        if (value.omisionsal == undefined) {
            icono5 = " ";
        } else {
            icono5 = "<a type='button' class='btn btn-link' onclick='eliminar_omision(" + value.omisionsal + ")' ><i class='fa fa-eraser' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Eliminar Omision-----Capturado por: " + value.captura_omision.username + "' ></i></i></a>";
        }
        if (value.checado_salida == "SIN REGISTRO")
            if (value.checado_salida_fuera != null) { xs = value.checado_salida + "<i style='color:red'><br>(" + value.checado_salida_fuera + ")</i>"; } else { xs = value.checado_salida; }

        else
            xs = value.checado_salida;


        if (value.ban_inci >= 1)
            icono2 = "<a type='button' class='btn btn-link' onclick='eliminar(" + value.ban_inci + ")' ><i class='fa fa-eraser' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Eliminar Incidencia'></i></i></a>";
        else
            icono2 = " ";

        if (value.capturista == undefined)
            icono3 = " ";
        else
            icono3 = "<i class='fa fa-check-square ' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='Capturado por: " + value.capturista + "'></i>";

        // $("#datos_filtros_checadas tr").append("<td><a type='button' class='btn btn-link' style='color:red'>Eliminar</a></td>");

        table.append("<tr><td>" + arreglo_dias[value.numero_dia] + "</td><td>" + value.fecha + "</td>" + "</td><td>" + xe + icono4 + "</td>" + "</td><td>" + xs + icono3 + icono5 + "</td> <td>" + icono + "</td><td>" + icono2 + "</td></tr>");

        xe = 'SIN REGISTRO';


    })


    $('#datos_filtros_checadas tr').hover(function() {
        $(this).addClass('hover');
    }, function() {
        $(this).removeClass('hover');
    });




}

function imprimir_tarjeta() {

    impre = 1;

    //win = window.open('./api/reporte-trimestral?anio=' + anio + "&trimestre=" + trimestre + "&tipo_trabajador=" + tipo_trabajador + "&nombre=" + nombre, '_blank'); 
    win = window.open('./api/imprimirTarjeta?id=' + dato + "&fecha_inicio=" + inicio + "&fecha_fin=" + fin + "&leyenda=" + leyenda + "&impre=" + impre, '_blank');

}

function nuevoEmpleado() {
    banemp = 0;
    $("#empleado-hora").html('');
    cargar_departamentos();

}

function editEmpleado(id) {
    banemp = 1;
    $("#modal-empleado").html("Editar Empleado");
    idempleado = parseInt(id);
    //$("#tipotra").empty();
    cargar_departamentos();


    $.ajax({
        type: "GET",
        url: "./api/buscaempleado/" + idempleado,

        dataType: "json",
        success: function(data) {

            cargar_horarios_empleado(data.data.horarios);
            tipotra = data.data.ur_id
                //console.log("entranndo" + tipotra);

            $("#name").val(data.data.Name);
            $("#rfc").val(data.data.TITLE);
            $("#sexo").val(data.data.Gender);
            $("#fechaing").val(moment(data.data.HIREDDAY).format('YYYY-MM-DD'));
            $("#codigo").val(data.data.PAGER);
            $("#clues").val(data.data.FPHONE);
            $("#area").val(data.data.MINZU);
            if (base == 5) {
                document.getElementById("biometrico").style.display = "block";
                $("#biome").val(maxid);
                $("#biome").val(data.data.Badgenumber);
                document.getElementById("biome").disabled = true;
            } else {
                document.getElementById("biometrico").style.display = "none";
            }


            $("#tipotra").val(tipotra);
            if (data.data.ATT == 0) {
                $("#mmi").prop('checked', true);
            } else { $("#mmi").prop('checked', false); }

            if (data.data.INLATE == 0) {
                $("#interino").prop('checked', true);
            } else { $("#interino").prop('checked', false); }


            //console.log(data.data.horarios[0].detalle_horario);
            diaslab = (data.data.horarios[0].detalle_horario);
            // console.log(data.data.dias_justificados);

        },
        error: function(data) {
            alert('error');
        }
    });

}




function filtrar_checadas() {
    inicio = $("#inicio").val();
    fin = $("#fin").val();
    cargar_datos_checadas(urlchecadas);
    //cargar_blade_checadas();


}


function cargar_catalogo_base() {
    var select = $("#cat_base");

    select.html("");
    jQuery.ajax({
        data: { 'buscar': "" },
        type: "GET",
        dataType: "json",
        url: './api/cat-base',
    }).done(function(data, textStatus, jqXHR) {
        $.each(data.catalogo, function(index, valor) {

            select.append("<option value='" + valor.id + "'>" + valor.alias + "</option>");


        });

    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}



function cargar_dato(dato) {

    var lista = $("#kardex");
    lista.html("");
    var linea_cargar = $("<tr><td colspan='22'>Cargando espere un momento, por favor. <i class='fa fa-spin fa-refresh'></i></td></tr>");
    lista.append(linea_cargar);

    jQuery.ajax({
        data: { 'id': dato, 'mes': '02' },
        type: "GET",
        dataType: "json",
        url: './api/kardex',
    }).done(function(data, textStatus, jqXHR) {
        lista.html("");
        //console.log(data.usuarios.length);
        if (data.usuarios.length == 0) {
            var linea = $("<tr ></tr>");
            var campo1 = $("<td colspan='20'>No se encontraron resultados</td>");
            linea.append(campo1);
            lista.append(linea);

        }
        $.each(data.usuarios, function(index, value) {
            //console.log(data.usuarios);
            var linea = $("<tr  ></tr>");


            var i = 1;
            var linea2 = $("<tr></tr>");
            var tamano = Object.keys(value.asistencia).length;
            $.each(value.asistencia, function(index_asistencia, value_asistencia) {
                var stilo_linea = "";
                if (i >= 16) {
                    stilo_linea = "border-bottom:1px solid black;";
                }
                if (value_asistencia == "F" || value_asistencia == "FE" || value_asistencia == "FS") {
                    campo = $("<td style='text-align:center; background-color:#993e3e; color:white; padding: 0rem !important;" + stilo_linea + "' >" + index_asistencia + "<br>" + value_asistencia + "</td>");
                } else if (value_asistencia == "R1") {
                    campo = $("<td style='text-align:center; background-color:#6a6969; color:white;padding: 0rem !important;" + stilo_linea + "' >" + index_asistencia + "<br>" + value_asistencia + "</td>");
                } else if (value_asistencia == "N/A") {
                    campo = $("<td style='text-align:center;font-weight:bold; background-color: #EFEFEF; padding: 0rem !important;" + stilo_linea + "' >" + index_asistencia + "</td>");
                } else {
                    campo = $("<td style='text-align:center;padding: 0rem !important;" + stilo_linea + "'>" + index_asistencia + "<br>" + value_asistencia + "</td>");
                }
                //console.log(tamano);
                if (tamano == 31 && i == 16) {
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

    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}






function validar(idinci) {
    val_in = 1;
    var idinci = parseInt(idinci);
    cargar_incidencias();
    document.getElementById('btn_save_inci').innerText = "Validar";



    $.ajax({
        type: "GET",
        url: "./api/buscaincidencia/" + idinci,

        dataType: "json",
        success: function(data) {
            $("#incidencia").val(data.data.tipos_incidencia.LeaveName);
            //$("#incidencia").val(data.data.tipos_incidencia.LeaveName);
            $("#razon").val(data.data.documentos);
            $("#f_ini").val((data.data.fecha_ini).replace(" ", "T"));
            $("#f_fin").val((data.data.fecha_fin).replace(" ", "T"));
            id_inci = data.data.id;
            // console.log(data.data);


        },
        error: function(data) {
            alert('error');
        }
    });






}



function validando_incidencia() {
    editEmpleado(idempleado);

    if (ban_url == 1) {
        id = $("#userid").val();
        documentos = $("#documentos").val();
        observaciones = $("#observaciones").val();
        autorizo = $("#autorizo").val();
        razon = $("#documentos").val();
        idcap = 0;
        url_in = "../api/guarda-justificante";
        yy = "../api/guarda-just-emp";

    } else {

        documentos = "-";
        observaciones = "-";
        autorizo = "-";
        id = $("#id").val();
        idcap = $("#id_user").val();
        razon = $("#razon").val();
        url_in = "api/guarda-justificante";
        yy = "api/guarda-just-emp";
    }

    date_1 = moment($("#f_ini").val());
    date_2 = moment($("#f_fin").val());
    tipo_incidencia = $("#code_in").val();

    diff_in_days = date_2.diff(date_1, 'days');

    diff_in_hours = date_2.diff(date_1, 'hours', true);
    diff = 0;
    diff = 1 + diff_in_days;
    var fec_com = moment(date_1).format();
    fec_com = fec_com.substr(5, 5);

    switch (parseInt(tipo_incidencia)) {
        case 1: //Pase de Salida                  
            if (diff_in_days == 0) {
                if (pasesal >= diff_in_hours && diff_in_hours <= 2 && diff_in_hours > 0) {
                    bandera = 1;
                } else {
                    bandera = 0
                    msj = "Verifique la solicitud";
                }
            } else {
                bandera = 0
                msj = "Verifique la solicitud";
            }
            console.log(bandera);
            break;

        case 6: //Dia Economico      
            if (diaslab.length == 5) {
                if (resumen_checadas.Día_Económico <= diff)
                    if ((resumen_checadas.Día_Económico == 0 && diff <= 2) || (resumen_checadas.Día_Económico == 1 && diff == 1)) {
                        bandera = 1;
                    } else {
                        bandera = 0;
                        msj = "Solo puede tener maximo 2 dias económicos en el mes";
                    }
            } else {
                if ((resumen_checadas.Día_Económico == 0 && diff <= 1)) {
                    bandera = 1;

                } else {
                    bandera = 0;
                    msj = "Solo puede tener 1 dia económico en el mes";
                }
            }
            break;
        case 10: //Onomastico   

            if (diff_in_days == 0 && fec_com == onomastico) {
                bandera = 1;
            } else {
                bandera = 0;
                msj = "La fecha no es la misma que su onomástico";
            }
            break;



            // case 12:
            //resumen_checadas.Vacaciones_2018_Invierno
            //    break;

        default:
            bandera = 1;
            //msj="otrooooooooo";

    }
}

function inserta_incidencia() {
    var x = 0;
    var dia_eva;
    // for (var i = 0; i < parseInt(diff_in_days + 1); i++) {
    fini = moment(date_1.add(x, 'd')).format();
    ffin = moment(date_2.add(x, 'd')).format();
    fini = fini.substr(0, 10) + " " + fini.substr(11, 8) + ".00";
    ffin = ffin.substr(0, 10) + " " + ffin.substr(11, 8) + ".00";
    if (razon == "") {
        swal("Verifique!", "Este campo es necesario", "error");
        $("#razon").focus();
    } else {

        $.ajax({
            type: 'POST',
            url: url_in,
            data: { id: id, fini: fini, ffin: ffin, tipo_incidencia: tipo_incidencia, razon: razon, idcap: idcap, id_inci: id_inci },
            success: function(data) {
                swal("Exito!", "La incidencia ha sido Registrada!", "success");
                document.getElementById("cerrar1").click();
                $("#documentos").val('');
                $("#autorizo").val('');
                $("#observaciones").val('');
                document.getElementById('buscar').click();
                document.getElementById('filtro_check').click();

                //    console.log(data);

            },
            error: function(data) {
                swal("Error!", "No se registro ningun dato!", "error");
            }
        })
    }



}

function inserta_incidencia_emp() {



    var x = 0;
    var dia_eva;
    // for (var i = 0; i < parseInt(diff_in_days + 1); i++) {
    fini = moment(date_1.add(x, 'd')).format();
    ffin = moment(date_2.add(x, 'd')).format();
    fini = fini.substr(0, 10) + " " + fini.substr(11, 8) + ".00";
    ffin = ffin.substr(0, 10) + " " + ffin.substr(11, 8) + ".00";

    $.ajax({
        type: 'POST',
        url: "../api/guarda-just-emp",
        data: { id: id, fini: fini, ffin: ffin, tipo_incidencia: tipo_incidencia, documentos: documentos, observaciones: observaciones, autorizo: autorizo },
        success: function(data) {
            document.getElementById("cerrar").click();
            $("#documentos").val('');
            $("#autorizo").val('');
            $("#observaciones").val('');
            document.getElementById('buscar').click();
            document.getElementById('filtro_check').click();

        },
        error: function(data) {
            swal("Error!", "No se registro ningun dato!", "error");
        }
    })

    /*     }
        }

        x = 1;

    } */
    //('#agregar_incidencia').modal('toggle'); 

    //swal("Exito!", "El registro se ha guardado!", "success");
}



function save_justi_emp() {


    fini = moment(date_1.add(0, 'd')).format();
    ffin = moment(date_2.add(0, 'd')).format();
    fini = fini.substr(0, 10) + " " + fini.substr(11, 8) + ".00";
    ffin = ffin.substr(0, 10) + " " + ffin.substr(11, 8) + ".00";
    $.ajax({
        type: 'POST',
        url: yy,
        data: { id: id, fini: fini, ffin: ffin, tipo_incidencia: tipo_incidencia, documentos: documentos, observaciones: observaciones, autorizo: autorizo },
        success: function(data) {

            id_inci = data.id_inci;
            inserta_incidencia();
        },
        error: function(data) {
            swal("Error!", "No se registro ningun dato!", "error");
        }
    })



}


function guardar_incidencia() {
    //permisos = [];
    obtener_justificantes(fini, ffin);
    //permisos = [];
    //console.log(permisos.length + " ini= " + fini + " fin = " + ffin);

}

function acepta_incidencia() {
    $.ajax({
        type: "GET",
        url: "./api/validaincidencia/" + id_inci,
        data: { idcap: idcap },
        success: function(data) {

            swal("Exito!", "La incidencia ha sido validada!", "success");

            $("#agregar_incidencia").modal('hide');
            document.getElementById('filtro_check').click();

        },
        error: function(data) {
            swal("Error!", "No se registro ningun dato!", "error");
        }
    })
}

/* function edita_empleado() {
    $.ajax({
        type: "GET",
        url: "./api/edita-empleado/" + idempleado,
        data: { idcap: idcap },
        success: function(data) {

            swal("Exito!", "La incidencia ha sido validada!", "success");

            $("#agregar_incidencia").modal('hide');
            document.getElementById('filtro_check').click();

        },
        error: function(data) {
            swal("Error!", "No se registro ningun dato!", "error");
        }
    })
} */

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
                        $("#agregar_incidencia").modal('hide');
                        $("#razon").val('');
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
                        $("#agregar_incidencia").modal('hide');
                        $("#razon").val('');
                        document.getElementById('filtro_check').click();

                    }
                });



            } else {
                swal("El registro no se ha eliminado");
            }
        });

}

function eliminar_in_emp(id) {


    swal({
            title: "¿Estás seguro?",
            text: "Una vez eliminado, no podrá recuperar este archivo!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'DELETE',
                    url: "../api/deleteinci-emp/" + id + "/",
                    success: function(data) {
                        swal("¡La incidencia ha sido eliminada!", {
                            icon: "success",
                        });

                        document.getElementById('buscar').click();

                    }
                });



            } else {
                swal("El registro no se ha eliminado");
            }
        });


}

function eliminar_hora_emp(id) {
    //alert("Estamos trabajando......");

    swal({
            title: "¿Estás seguro?",
            text: "Una vez eliminado, no podrá recuperar!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    type: 'DELETE',
                    url: "api/deletehora-emp/" + id + "/",
                    // "api/edita-empleado/" + idempleado;

                    success: function(data) {
                        swal("¡El horario ha sido eliminado!", {
                            icon: "success",
                        });
                        editEmpleado(idempleado);


                    }
                });



            } else {
                console.log(url);
                swal("El horario no se ha eliminado");
            }
        });


}