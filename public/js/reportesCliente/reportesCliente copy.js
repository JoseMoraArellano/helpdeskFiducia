$(document).ready(function(){
    $('#tablaReporteClienteLoad').load('reportesCliente/tablaReporteCliente.php');
});

function agregarNuevoReporte() {

    $.ajax({
        type:"POST",
        data:$('#frmNuevoReporte').serialize(),
        url:"../procesos/reportesCliente/agregarNuevoReporte.php",
        success:function(respuesta) {
            respuesta = respuesta.trim();
            if (respuesta == 1) {
                $('#tablaReporteClienteLoad').load('reportesCliente/tablaReporteCliente.php');
                $('#frmNuevoReporte')[0].reset();
                Swal.fire("Correcto","Agregado con exito!","success");
            } else {
                Swal.fire("Fallo","Error al agregar! " + respuesta, "error");
            }
        }
    });

    return false;
}

function eliminarReporteCliente(idReporte) {
    Swal.fire({
        title: '¿Estas seguro de eliminar este registro?',
        text: "Una vez eliminado no podra ser recuperado!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
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