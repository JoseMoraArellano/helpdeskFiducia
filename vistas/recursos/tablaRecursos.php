<?php
    session_start();
    include "../../clases/Conexion.php";
    $con = new Conexion();
    $conexion = $con->conectar();
    $sql = "SELECT 
                id_equipo,
                nombre,
                descripcion,
                categ_SH
            FROM
                t_cat_equipo
            ORDER BY nombre";
    $respuesta = mysqli_query($conexion, $sql);
?>

<table class="table table-sm table-bordered dt-responsive nowrap" 
       style="width:100%" id="tablaRecursosDataTable">
    <thead>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Categoría</th>
        <th>Editar</th>
        <th>Eliminar</th>
    </thead>
    <tbody>
    <?php while($mostrar = mysqli_fetch_array($respuesta)) { ?>
        <tr>
            <td><?php echo $mostrar['id_equipo'] ?></td>
            <td><?php echo $mostrar['nombre'] ?></td>
            <td><?php echo $mostrar['descripcion'] ?></td>
            <td><?php echo $mostrar['categ_SH'] ?></td>
            <td>
                <button class="btn btn-warning btn-sm" 
                        onclick="obtenerDatosRecurso(<?php echo $mostrar['id_equipo'] ?>)"
                        data-toggle="modal" data-target="#modalActualizarRecurso">
                    <i class="fas fa-edit"></i>
                </button>
            </td>
            <td>
                <button class="btn btn-danger btn-sm" 
                        onclick="eliminarRecurso(<?php echo $mostrar['id_equipo'] ?>)">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<script>
    $(document).ready(function(){
        $('#tablaRecursosDataTable').DataTable({
            language: {
                url: "../public/datatable/es_es.json"
            },
            dom: 'Bfrtip',
            buttons: {
                buttons: [
                    { 
                        extend: 'copy', 
                        className: 'btn btn-outline-info', 
                        text: '<i class="far fa-copy"></i> Copiar' 
                    },
                    { 
                        extend: 'csv', 
                        className: 'btn btn-outline-primary', 
                        text: '<i class="fas fa-file-csv"></i> CSV' 
                    },
                    { 
                        extend: 'excel', 
                        className: 'btn btn-outline-success', 
                        text: '<i class="fas fa-file-excel"></i> XLS' 
                    },
                    { 
                        extend: 'pdf', 
                        className: 'btn btn-outline-danger', 
                        text: '<i class="fas fa-file-pdf"></i> PDF' 
                    }
                ],
                dom: {
                    button: {
                        className: 'btn'
                    }
                }
            }
        });
    });
</script>