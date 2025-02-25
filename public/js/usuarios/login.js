function loginUsuario() {
    $.ajax({
        type:"POST",
        data:$('#frmLogin').serialize(),
        url:"procesos/usuarios/login/loginUsuario.php",
        success:function(respuesta) {
            respuesta = respuesta.trim();
            if (respuesta == 1) {
                window.location.href = "vistas/inicio.php";
            } 
            if (respuesta == 4) {
                Swal.fire("Error al iniciar sesion", "Usuario inactivo", "Intenta de nuevo");
            }
            else {
                Swal.fire("Error al iniciar sesion", "El usuario o la contraseña son incorrectos ", "Intente nuevamente");
            }
        }
    });

    return false;
}