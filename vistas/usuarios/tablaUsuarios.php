<?php
    session_start();
    include "../../clases/Conexion.php";
    $con = new Conexion();
    $conexion = $con->conectar();
    $sql = "SELECT 
                usuarios.id_usuario AS idUsuario,
                usuarios.usuario AS nombreUsuario,
                roles.nombre AS rol,
                usuarios.id_rol AS id_Rol,
                usuarios.ubicacion AS ubicacion,
                usuarios.activo AS estatus,
                usuarios.id_persona AS idPersona,
                persona.nombre AS nombrePersona,
                persona.paterno AS paterno,
                persona.materno AS materno,
                persona.fecha_nacimiento AS fechaNacimiento,
                persona.sexo AS sexo,
                persona.correo AS correo,
                persona.telefono AS telefono
            FROM
                t_usuarios AS usuarios
                    INNER JOIN
                t_cat_roles AS roles ON usuarios.id_rol = roles.id_rol
                    INNER JOIN
                t_persona AS persona ON usuarios.id_persona = persona.id_persona
                ORDER BY usuarios.usuario ASC";
    $respuesta = mysqli_query($conexion, $sql);
?>

<table class="table table-sm dt-responsive nowrap" 
        id="tablaUsuariosDataTable" style="width:100%">
    <thead>
        <th>Usuario</th>
        <th>Apellido paterno</th>
        <th>Apellido materno</th>
        <th>Nombre</th>
        <th>Ubicacion</th>
        <th>Telefono</th>
        <th>Correo</th>        
        <th>Ubicacion</th>
        <th>Sexo</th>
        <th>Cambiar Contraseña</th>
        <th>Activar</th>
        <th>Editar</th>
        <th>Eliminar</th>
    </thead>
    <tbody>
        <?php
            while($mostrar = mysqli_fetch_array($respuesta)) {   
                // Verificor si es administrador (con id_rol = 2 y idUsuario)
                $esAdmin = ($mostrar['id_Rol'] == 2 && $mostrar['idUsuario'] == '1') ? true : false;
                $esTecnico = ($mostrar['id_Rol'] == 3 ) ? true : false;
        ?>
        <!-- Verificar si el estatus es 0 lo atenuo-->
        <tr class="<?php echo ($mostrar['estatus'] == 0) ? 'text-muted' : ''; ?>">
        <td>
                <?php 
                    echo $mostrar['nombreUsuario']; 
                    // Mostrar insignia si es administrador
                    if ($esAdmin) {
                        echo ' <span class="badge badge-pill badge-primary">Admin</span>';
                    }
                    // Mostrar insignia si es tecnico
                    if ($esTecnico) {
                        echo ' <span class="badge badge-pill badge-success">Tecnico</span>';
                    }
                ?>
            </td>
            <td><?php echo $mostrar['paterno']; ?></td>
            <td><?php echo $mostrar['materno']; ?></td>
            <td><?php echo $mostrar['nombrePersona']; ?></td>
            <td><?php echo $mostrar['ubicacion']; ?></td>
            <td><?php echo $mostrar['telefono']; ?></td>
            <td><?php echo $mostrar['correo']; ?></td>
            <td><?php echo $mostrar['ubicacion']; ?></td>
            <td><?php echo $mostrar['sexo']; ?></td>
            <td>
                <?php if (!$esAdmin || $mostrar['estatus'] == 0): ?>
                <button class="btn btn-info btn-sm" 
                    data-toggle="modal" 
                    data-target="#modalResetPassword"
                    onclick="agregarIdUsuarioReset(<?php echo $mostrar['idUsuario'] ?>)">
                    <span class="fas fa-exchange-alt"></span>
                </button>
                <?php else: ?>
                <button class="btn btn-info btn-sm" disabled title="No permitido para el administrador">
                    <span class="fas fa-exchange-alt"></span>
                </button>
                <?php endif; ?>
            </td>
            <td>
                <?php 
                    if (!$esAdmin) {
                        if ($mostrar['estatus'] == 1) {
                ?>
                    <button class="btn btn-secondary btn-sm" 
                    onclick="cambioEstatusUsuario(<?php echo $mostrar['idUsuario'] ?>, <?php echo $mostrar['estatus'] ?>)">
                        <span class="fas fa-power-off"></span> Off
                    </button>
                <?php
                        } else if($mostrar['estatus'] == 0) {
                ?>
                    
                    <button class="btn btn-success btn-sm" 
                    onclick="cambioEstatusUsuario(<?php echo $mostrar['idUsuario'] ?>, <?php echo $mostrar['estatus'] ?>)">
                        <span class="fas fa-power-off"></span> On
                    </button>
                <?php
                        }
                    } else {
                ?>
                    <button class="btn btn-secondary btn-sm" disabled title="No permitido para administradores">
                        <span class="fas fa-power-off"></span>
                    </button>
                <?php
                    }
                ?>
            </td>
            <td>
                <?php if (!$esAdmin): ?>
                <button class="btn btn-warning btn-sm" 
                        data-toggle="modal" 
                        data-target="#modalActualizarUsuarios"
                        onclick="obtenerDatosUsuario(<?php echo $mostrar['idUsuario'] ?>)">
                    Editar
                </button>
                <?php else: ?>
                <button class="btn btn-warning btn-sm" disabled title="No permitido para administradores">
                    Editar
                </button>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!$esAdmin): ?>
                <button class="btn btn-danger btn-sm" 
                onclick="eliminarUsuario(<?php echo $mostrar['idUsuario']; ?>,<?php echo $mostrar['idPersona']; ?> )">
                    <span class="fas fa-user-times"></span>
                </button>
                <?php else: ?>
                <button class="btn btn-danger btn-sm" disabled title="No permitido para administradores">
                    <span class="fas fa-user-times"></span>
                </button>
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>


<script>
    $(document).ready(function(){
        $('#tablaUsuariosDataTable').DataTable({
            language : {
                url : "../public/datatable/es_es.json"
            }
        });
    });
</script>