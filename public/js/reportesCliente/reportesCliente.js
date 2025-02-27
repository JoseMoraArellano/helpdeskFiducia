$(document).ready(function(){
    $('#tablaReporteClienteLoad').load('reportesCliente/tablaReporteCliente.php');
    
    // Manejar cambio en el selector de archivos
    if (document.getElementById('archivosAdjuntos')) {
        document.getElementById('archivosAdjuntos').addEventListener('change', function(e) {
            const fileList = e.target.files;
            const fileNames = Array.from(fileList).map(file => file.name);
            
            // Actualizar el label con el nombre del primer archivo o un texto indicando múltiples archivos
            const label = document.querySelector('.custom-file-label');
            label.textContent = fileNames.length > 1 ? fileNames.length + ' archivos seleccionados' : fileNames[0] || 'Seleccionar archivos';
            
            // Mostrar lista de archivos seleccionados
            const archivosSeleccionados = document.getElementById('archivosSeleccionados');
            
            if (fileNames.length > 0) {
                let html = '<div class="list-group mt-2">';
                fileNames.forEach(name => {
                    html += `<div class="list-group-item list-group-item-action small py-1">${name}</div>`;
                });
                html += '</div>';
                archivosSeleccionados.innerHTML = html;
            } else {
                archivosSeleccionados.innerHTML = '';
            }
        });
    }
});

function agregarNuevoReporte() {
    // Crear FormData para manejar archivos
    const formData = new FormData(document.getElementById('frmNuevoReporte'));
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Procesando...',
        text: 'Creando reporte y subiendo archivos',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        type: "POST",
        data: formData,
        url: "../procesos/reportesCliente/agregarNuevoReporte.php",
        processData: false,  // Importante para FormData
        contentType: false,  // Importante para FormData
        success: function(respuesta) {
            respuesta = respuesta.trim();
            
            if (respuesta == 1) {
                $('#tablaReporteClienteLoad').load('reportesCliente/tablaReporteCliente.php');
                $('#frmNuevoReporte')[0].reset();
                
                // Limpiar la lista de archivos y la etiqueta
                document.getElementById('archivosSeleccionados').innerHTML = '';
                document.querySelector('.custom-file-label').textContent = 'Seleccionar archivos';
                
                Swal.fire("Correcto", "Reporte creado con éxito!", "success");
            } else {
                Swal.fire("Error", "No se pudo crear el reporte: " + respuesta, "error");
            }
        },
        error: function(xhr, status, error) {
            Swal.fire("Error", "Hubo un problema en la comunicación con el servidor", "error");
            console.error("Error AJAX:", xhr.responseText);
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
        cancelButtonText:'Cancelar'
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