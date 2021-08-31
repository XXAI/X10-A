$(document).ready(function() {

    $("#mensaje_error").hide();
    cargar_catalogo_base();
});



function register_user() {

    var name = $("#name").val();
    var apellido_paterno = $("#apellido_paterno").val();
    var apellido_materno = $("#apellido_materno").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var base = $("#cat_base").val();
    var clues = $("#clues").val();
    console.log(name + apellido_paterno + apellido_materno + email + password);
    $.ajax({
        type: 'POST',
        url: 'api/registra-usuario',
        data: { name: name, apellido_paterno: apellido_paterno, apellido_materno: apellido_materno, email: email, password: password, base: base, clues: clues },
        success: function(data) {
            swal("Exito!", "El registro se ha guardado", "success");
            window.location = "./logout";
            //href=
        },
        error: function(data) {
            // console.log(data);
            swal("Error!", "No se registro ningun dato!", "error");
        }

    })

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

            select.append("<option value='" + valor.id + "'>" + valor.descripcion + "</option>");


        });

    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}