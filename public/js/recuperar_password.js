
$(document).ready(function() {
    $('#formRecuperarContrasena').submit(function(e) {
        e.preventDefault();
        
        // Obtener el bot¨®n de submit y deshabilitarlo
        const submitButton = $('button[type="submit"][form="formRecuperarContrasena"]');
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
        
        let correo = $('#correoRecuperar').val();
        
        // Mostrar alerta de procesamiento
        const loadingSwal = Swal.fire({
            title: 'Procesando solicitud',
            text: 'Por favor espere mientras enviamos el correo podria tardar unos minutos en llegar...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            type: 'POST',
            url: 'procesos/recuperar_password.php',
            data: {
                correo: correo
            },
            success: function(response) {
                // Cerrar alerta de procesamiento
                loadingSwal.close();
                
                let respuesta = JSON.parse(response);
                if (respuesta.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Correo enviado',
                        text: 'Se ha enviado un enlace de recuperacion a tu correo electronico revise la carpeteta de Spam o no deseados'
                    });
                    $('#modalRecuperarPassword').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta.message
                    });
                }
            },
            error: function() {
                // Cerrar alerta de procesamiento
                loadingSwal.close();
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al procesar la solicitud'
                });
            },
            complete: function() {
                // Restaurar el bot¨®n
                submitButton.prop('disabled', false);
                submitButton.html('Aceptar');
            }
        });
    });
});