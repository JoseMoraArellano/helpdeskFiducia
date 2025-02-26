$(document).ready(function(){
    $('#tablaRecursosLoad').load('recursos/tablaRecursos.php');
});

function agregarNuevoRecurso() {
    $.ajax({
        type: "POST",
        data: $('#frmAgregarRecurso').serialize(),
        url: "../procesos/recursos/crud/agregarRecurso.php",
        success: function(respuesta) {
            respuesta = respuesta.trim();
            
            if (respuesta == 1) {
                $('#tablaRecursosLoad').load('recursos/tablaRecursos.php');
                $('#frmAgregarRecurso')[0].reset();
                $('#modalAgregarRecurso').modal('hide');
                Swal.fire("Éxito", "Recurso agregado correctamente", "success");
            } else {
                Swal.fire("Error", "No se pudo agregar el recurso: " + respuesta, "error");
            }
        }
    });

    return false;
}
function obtenerDatosRecurso(idRecurso) {
    $.ajax({
        type: "POST",
        data: "idRecurso=" + idRecurso,
        url: "../procesos/recursos/crud/obtenerDatosRecurso.php",
        success: function(respuesta) {
            console.log("Respuesta del servidor:", respuesta);
            
            //  la respuesta como JSON
            try {
                var datos = JSON.parse(respuesta);                
                // Llenar los campos del formulario
                $('#idRecurso').val(datos.idRecurso);
                $('#nombreU').val(datos.nombre);
                $('#descripcionU').val(datos.descripcion);
                $('#categSHU').val(datos.categSH);
            } catch (error) {
                console.error("Error al parsear JSON:", error);
                alert("Error al cargar los datos del recurso");
            }
        },
        error: function(xhr) {
            console.error("Error AJAX:", xhr.responseText);
            alert("Error de comunicación con el servidor");
        }
    });
}
function actualizarRecurso() {
    $.ajax({
        type: "POST",
        data: $('#frmActualizarRecurso').serialize(),
        url: "../procesos/recursos/crud/actualizarRecurso.php",
        success: function(respuesta) {
            console.log("Respuesta actualización:", respuesta);
            respuesta = respuesta.trim();
            
            if (respuesta == 1) {
                $('#tablaRecursosLoad').load('recursos/tablaRecursos.php');
                $('#modalActualizarRecurso').modal('hide');
                Swal.fire("Éxito", "Recurso actualizado correctamente", "success");
            } else {
                Swal.fire("Error", "No se pudo actualizar el recurso: " + respuesta, "error");
            }
        },
        error: function(xhr) {
            console.error("Error AJAX:", xhr.responseText);
            Swal.fire("Error", "Error de comunicación con el servidor", "error");
        }
    });
    
    return false;
}
function actualizarRecurso() {
    console.log("Datos a enviar:", $('#frmActualizarRecurso').serialize()); // Para depuración
    
    $.ajax({
        type: "POST",
        data: $('#frmActualizarRecurso').serialize(),
        url: "../procesos/recursos/crud/actualizarRecurso.php",
        success: function(respuesta) {
            console.log("Respuesta del servidor:", respuesta); // Para depuración
            
            respuesta = respuesta.trim();
            
            if (respuesta == 1) {
                $('#tablaRecursosLoad').load('recursos/tablaRecursos.php');
                $('#modalActualizarRecurso').modal('hide');
                Swal.fire("Éxito", "Recurso actualizado correctamente", "success");
            } else {
                Swal.fire("Error", "No se pudo actualizar el recurso: " + respuesta, "error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error AJAX:", error);
            Swal.fire(
                'Error',
                'Hubo un problema al comunicarse con el servidor',
                'error'
            );
        }
    });

    return false;
}
function eliminarRecurso(idRecurso) {
    Swal.fire({
        title: '¿Está seguro de eliminar este recurso?',
        text: "Una vez eliminado no podrá ser recuperado",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                data: "idRecurso=" + idRecurso,
                url: "../procesos/recursos/crud/eliminarRecurso.php",
                success: function(respuesta) {
                    respuesta = respuesta.trim();
                    
                    if (respuesta == 1) {
                        $('#tablaRecursosLoad').load('recursos/tablaRecursos.php');
                        Swal.fire("Éxito", "Recurso eliminado correctamente", "success");
                    } else {
                        Swal.fire("Error", "No se pudo eliminar el recurso: " + respuesta, "error");
                    }
                }
            });
        }
    });
}