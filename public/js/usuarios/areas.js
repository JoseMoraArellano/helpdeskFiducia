$(document).ready(function(){
    // Cargar la tabla de áreas cuando se abra el modal
    $('#modalAgregarAreas').on('shown.bs.modal', function () {
        cargarTablaAreas();
    });
});

// Función para cargar la tabla de áreas
function cargarTablaAreas() {
    $.ajax({
        url: '../procesos/usuarios/areas/obtenerAreas.php',
        success: function(response) {
            $('#tablaAreasLoad').html(response);
        },
        error: function(error) {
            console.error(error);
        }
    });
}

// Función para agregar una nueva área
function agregarNuevaArea() {
    if (!$("#frmAgregarArea")[0].checkValidity()) {
        return false;
    }
    
    $.ajax({
        type: 'POST',
        url: '../procesos/usuarios/areas/agregarArea.php',
        data: $('#frmAgregarArea').serialize(),
        success: function(response) {
            response = response.trim();
            
            if (response === 'duplicado') {
                Swal.fire({
                    title: 'Advertencia',
                    text: 'El área ya existe en la base de datos',
                    icon: 'warning'
                });
                return false;
            } else if (response === 'success') {
                // Limpiar el formulario
                $('#frmAgregarArea')[0].reset();
                
                // Recargar la tabla de áreas
                cargarTablaAreas();
                
                // Mostrar mensaje de éxito
                Swal.fire({
                    title: 'Éxito!',
                    text: 'Área agregada correctamente',
                    icon: 'success'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al agregar el área: ' + response,
                    icon: 'error'
                });
            }
        },
        error: function(error) {
            Swal.fire({
                title: 'Error',
                text: 'Error en la solicitud',
                icon: 'error'
            });
        }
    });
    
    return false;
}

// Función para llenar el formulario de edición
function obtenerDatosArea(idrarea) {
    $.ajax({
        type: 'POST',
        url: '../procesos/usuarios/areas/obtenerDatosArea.php',
        data: {
            idrarea: idrarea
        },
        success: function(response) {
            try {
                response = JSON.parse(response);
                $('#idrareaEditar').val(response.idrarea);
                $('#nombreAreaEditar').val(response.Nomb_area);
                $('#modalEditarArea').modal('show');
            } catch (error) {
                console.error('Error al parsear JSON:', error);
            }
        },
        error: function(error) {
            console.error(error);
        }
    });
}

// Función para actualizar un área
function actualizarArea() {
    if (!$("#frmEditarArea")[0].checkValidity()) {
        return false;
    }
    
    $.ajax({
        type: 'POST',
        url: '../procesos/usuarios/areas/actualizarArea.php',
        data: $('#frmEditarArea').serialize(),
        success: function(response) {
            response = response.trim();
            
            if (response === 'duplicado') {
                Swal.fire({
                    title: 'Advertencia',
                    text: 'El área ya existe en la base de datos',
                    icon: 'warning'
                });
                return false;
            } else if (response === 'success') {
                // Cerrar el modal
                $('#modalEditarArea').modal('hide');
                
                // Recargar la tabla de áreas
                cargarTablaAreas();
                
                // Mostrar mensaje de éxito
                Swal.fire({
                    title: 'Éxito!',
                    text: 'Área actualizada correctamente',
                    icon: 'success'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al actualizar el área: ' + response,
                    icon: 'error'
                });
            }
        },
        error: function(error) {
            Swal.fire({
                title: 'Error',
                text: 'Error en la solicitud',
                icon: 'error'
            });
        }
    });
    
    return false;
}

// Función para eliminar un área
function eliminarArea(idrarea) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede revertir",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '../procesos/usuarios/areas/eliminarArea.php',
                data: {
                    idrarea: idrarea
                },
                success: function(response) {
                    if (response.trim() === 'success') {
                        // Recargar la tabla de áreas
                        cargarTablaAreas();
                        
                        // Mostrar mensaje de éxito
                        Swal.fire(
                            'Eliminado!',
                            'El área ha sido eliminada.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error!',
                            'No se pudo eliminar el área: ' + response,
                            'error'
                        );
                    }
                },
                error: function(error) {
                    Swal.fire(
                        'Error!',
                        'Error en la solicitud',
                        'error'
                    );
                }
            });
        }
    });
}