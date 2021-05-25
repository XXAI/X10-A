$(document).ready(function() {

    $("#mensaje_error").hide();
});



function register_user() {

    var name = $("#name").val();
    var apellido_paterno = $("#apellido_paterno").val();
    var apellido_materno = $("#apellido_materno").val();
    var email = $("#email").val();
    var password = $("#password").val();
    console.log(name + apellido_paterno + apellido_materno + email + password);
    $.ajax({
        type: 'POST',
        url: 'api/registra-usuario',
        data: { name: name, apellido_paterno: apellido_paterno, apellido_materno: apellido_materno, email: email, password: password },
        success: function(data) {

          window.location = "./logout"
            //href=
        },
        error: function(data) {
            // console.log(data);
            swal("Error!", "No se registro ningun dato!", "error");
        }

    })

}