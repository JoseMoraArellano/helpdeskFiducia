function loginUsuario() {
    $.ajax({
        type: "POST",
        data: $('#frmLogin').serialize(),
        url: "procesos/usuarios/login/loginUsuario.php",
        success: function(respuesta) {
            respuesta = respuesta.trim();
            if (respuesta == 1) {
                window.location.href = "vistas/inicio.php";
            } else if (respuesta == 4) {
                Swal.fire("Error al iniciar sesión", "Usuario inactivo", "error");
            } else {
                Swal.fire("Error al iniciar sesión", "El usuario o la contraseña son incorrectos", "error");
            }
        }
    });
    return false;
}