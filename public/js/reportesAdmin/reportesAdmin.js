$(document).ready(function(){
    $('#tablaReporteAdminLoad').load('reportesAdmin/tablaReportesAdmin.php');
});

function eliminarReporteAdmin(idReporte) {
    Swal.fire({
        title: '¿Estas seguro de eliminar este registro?',
        text: "Una vez eliminado no podra ser recuperado!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!',
        confirmButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type:"POST",
                data:"idReporte=" + idReporte,
                url:"../procesos/reportesCliente/eliminarReporteCliente.php",
                success:function(respuesta) {
                    if (respuesta == 1) {
                        $('#tablaReporteClienteLoad').load('reportesCliente/tablaReporteCliente.php');
                        Swal.fire("Correcto","Eliminado con exito!","success");
                    } else {
                        Swal.fire("Fallo","Fallo al eliminar!" + respuesta, "error");
                    }
                }
            });
        }
    })
    return false;
}

function obtenerDatosSolucion(idReporte) {
    // Cargar los técnicos primero
    $.ajax({
        type: "POST",
        url: "../procesos/reportesAdmin/obtenerTecnicos.php",
        success: function(respuesta) {
            // Agregar las opciones al select
            $('#idTecnico').html('<option value="">Seleccione un técnico</option>' + respuesta);
            
            // Luego cargar los datos del reporte
            $.ajax({
                type: "POST",
                data: "idReporte=" + idReporte,
                url: "../procesos/reportesAdmin/obtenerSolucion.php",
                success: function(respuesta) {
                    respuesta = jQuery.parseJSON(respuesta);
                    $('#idReporte').val(respuesta['idReporte']);
                    $('#solucion').val(respuesta['solucion']);
                    $('#estatus').val(respuesta['estatus']);
                    
                    // Seleccionar el técnico si ya está asignado
                    if (respuesta['idTecnico']) {
                        $('#idTecnico').val(respuesta['idTecnico']);
                    }
                    
                    // Cargar los archivos adjuntos
                    cargarArchivosAdjuntos(idReporte);
                }
            });
        }
    });
}
function agregarSolucionReporte() {
    $.ajax({
        type:"POST",
        data:$('#frmAgregarSolucionReporte').serialize(),
        url:"../procesos/reportesAdmin/actualizarSolucion.php",
        success:function(respuesta) {
            respuesta = respuesta.trim();
            if (respuesta == 1) {
                Swal.fire("Correcto","Agregado con exito!", "success");
                $('#tablaReporteAdminLoad').load('reportesAdmin/tablaReportesAdmin.php');
            } else {
                Swal.fire(":(","Fallo!" + respuesta, "error");
            }
        }
    });

    return false;
}
function verArchivosAdjuntos(idReporte) {
    $('#modalVerArchivos').modal('show');
    
    $.ajax({
        type: "POST",
        data: "idReporte=" + idReporte,
        url: "../procesos/obtenerArchivosReporte.php",
        success: function(respuesta) {
            $('#contenidoArchivosAdjuntos').html(respuesta);
        },
        error: function(xhr, status, error) {
            console.error("Error AJAX:", error);
            $('#contenidoArchivosAdjuntos').html('<p class="text-danger text-center">Error al cargar los archivos</p>');
        }
    });
}