<?php
    session_start();
    include "../../clases/Conexion.php";
    $con = new Conexion();
    $conexion = $con->conectar();
    $idUsuario = $_SESSION['usuario']['id'];
    $contador = 1;

        // Recibir parámetro de filtro si existe
        $filtro = isset($_POST['filtro']) ? $_POST['filtro'] : '';

    $sql = "SELECT 
                reporte.id_reporte AS idReporte,
                reporte.id_usuario AS idUsuario,
                CONCAT(persona.paterno,
                        ' ',
                        persona.materno,
                        ' ',
                        persona.nombre) AS nombrePersona,
                equipo.id_equipo AS idEquipo,
                equipo.nombre AS nombreEquipo,
                reporte.descripcion_problema AS problema,
                reporte.estatus AS estatus,
                reporte.solucion_problema AS solucion,
                reporte.fecha AS fecha
            FROM
                t_reportes AS reporte
                    INNER JOIN
                t_usuarios AS usuario ON reporte.id_usuario = usuario.id_usuario
                    INNER JOIN
                t_persona AS persona ON usuario.id_persona = persona.id_persona
                    INNER JOIN
                t_cat_equipo AS equipo ON reporte.id_equipo = equipo.id_equipo";
    // Agregar condición de filtro si existe
    if (!empty($filtro)) {
        if ($filtro === 'abiertos') {
            $sql .= " WHERE reporte.estatus = 1"; // Abierto
        } else if ($filtro === 'proceso') {
            $sql .= " WHERE reporte.estatus = 2"; // En proceso
        } else if ($filtro === 'cerrados') {
            $sql .= " WHERE reporte.estatus = 0"; // Cerrado
        }
    }
    $sql .= " ORDER BY reporte.fecha DESC";            
    $respuesta = mysqli_query($conexion, $sql);
?>

<table class="table table-sm table-bordered dt-responsive nowrap" 
        style="width:100%" id="tablaReportesAdminDataTable">
    <thead>
        <th>#</th>
        <th>Persona</th>
        <th>Dispositivo</th>
        <th>Fecha</th>
        <th>Descripcion</th>
        <th>Estatus</th>
        <th>Solucion</th>
        <th>Adjuntos</th>
        <th>Eliminar</th>
    </thead>
    <tbody>
    <?php while($mostrar = mysqli_fetch_array($respuesta)) {  ?>
        <tr>
            <td> <?php echo $contador++; ?> </td>
            <td><?php echo $mostrar['nombrePersona'];?></td>
            <td><?php echo $mostrar['nombreEquipo'];?></td>
            <td><?php echo $mostrar['fecha'];?></td>
            <td><?php echo $mostrar['problema'];?></td>
            <td>
            <?php
                $estatus = $mostrar['estatus'];
// Crear cadena de estatus              
                if ($estatus == 1) {
                    $cadenaEstatus = '<span class="badge badge-danger">Abierto</span>';
                } else if ($estatus == 0) {
                    $cadenaEstatus = '<span class="badge badge-success">Cerrado</span>';
                } else if ($estatus == 2) {
                    $cadenaEstatus = '<span class="badge badge-warning">En proceso</span>';
                } else {
                    $cadenaEstatus = '<span class="badge badge-secondary">Desconocido</span>';
                }
                
                echo $cadenaEstatus;
            ?>
            </td>
            <td>
                <button class="btn btn-info btn-sm" 
                        onclick="obtenerDatosSolucion('<?php echo $mostrar['idReporte'];?>')"
                        data-toggle="modal" data-target="#modalAgregarSolucionReporte">
                    Solucion
                </button>
                <?php echo $mostrar['solucion'];?>
            </td>
            <td>
                <?php
                // Verificar si tiene archivos adjuntos
                $sqlArchivos = "SELECT COUNT(*) as total FROM t_archivos_reportes WHERE id_reporte = " . $mostrar['idReporte'];
                $resultadoArchivos = mysqli_query($conexion, $sqlArchivos);
                $totalArchivos = mysqli_fetch_assoc($resultadoArchivos)['total'];
                
                if ($totalArchivos > 0) {
                    echo '<button class="btn btn-outline-info btn-sm" onclick="verArchivosAdjuntos(' . $mostrar['idReporte'] . ')" title="Ver archivos adjuntos">';
                    echo '<i class="fas fa-paperclip"></i> ' . $totalArchivos;
                    echo '</button>';
                } else {
                    echo '<span class="text-muted"><i class="fas fa-paperclip"></i> 0</span>';
                }
                ?>
            </td>
            <td>
                <?php
                    if ($mostrar['solucion'] == "") {
                ?>
                        <button class="btn btn-danger btn-sm" 
                            onclick="eliminarReporteAdmin(<?php echo $mostrar['idReporte'] ?>)">
                            Eliminar
                        </button>
                <?php
                    }
                ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<script>
    $(document).ready(function(){
        $('#tablaReportesAdminDataTable').DataTable({
            language : {
                url : "../public/datatable/es_es.json"
            },
            dom: 'Bfrtip',
            buttons : {
                buttons : [
                    {   
                        extend : 'copy', 
                        className : 'btn btn-outline-info', 
                        text : '<i class="far fa-copy"></i> Copiar' 
                    },
                    {   
                        extend : 'csv', 
                        className : 'btn btn-outline-primary', 
                        text : '<li class="fas fa-file-csv"></li> CSV' 
                    },
                    {   
                        extend : 'excel', 
                        className : 'btn btn-outline-success', 
                        text : '<i class="fas fa-file-excel"></i> XLS' 
                    },
                    {   
                        extend : 'pdf', 
                        className : 'btn btn-outline-danger', 
                        text : '<i class="fas fa-file-pdf"></i> PDF' 
                    },
                ],
                dom : {
                    button : {
                        className : 'btn'
                    }
                }
            }
        });
    })
</script>

<!-- Modal para ver archivos adjuntos -->
<div class="modal fade" id="modalVerArchivos" tabindex="-1" role="dialog" aria-labelledby="tituloModalArchivos" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalArchivos">Archivos adjuntos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoArchivosAdjuntos">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>