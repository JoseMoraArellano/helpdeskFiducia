// procesos/reset_password.js
$(document).ready(function() {
    $('#formRestablecerPassword').submit(function(e) {
        e.preventDefault();
        
        let nuevaContrasena = $('#nuevaContrasena').val();
        let confirmarContrasena = $('#confirmarContrasena').val();
        let token = $('input[name="token"]').val();
        
        // Validar que las contraseñas coincidan
        if (nuevaContrasena !== confirmarContrasena) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Las contraseñas no coinciden'
            });
            return;
        }
        
        // Validar longitud mínima
        if (nuevaContrasena.length < 8) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La contraseña debe tener al menos 8 caracteres'
            });
            return;
        }
        
        $.ajax({
            type: 'POST',
            url: 'procesos/procesar_reset_password.php',
            data: {
                token: token,
                nuevaContrasena: nuevaContrasena
            },
            success: function(response) {
                let respuesta = JSON.parse(response);
                if (respuesta.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Tu contraseña ha sido actualizada correctamente',
                        confirmButtonText: 'Ir al login'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.html';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al procesar la solicitud'
                });
            }
        });
    });
});
