<?php
session_start();
require_once "../../clases/Conexion.php";

// Verificar que el usuario tenga rol de administrador
if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 2 || $_SESSION['usuario']['rol'] == 3) {
    $conexion = new Conexion();
    $con = $conexion->conectar();
    
    // Obtener listado de usuarios administradores (rol 2)
    $sqlTecnicos = "SELECT id_usuario, usuario FROM t_usuarios WHERE id_rol = 2 OR id_rol = 3 ORDER BY usuario";
    $resultadoTecnicos = mysqli_query($con, $sqlTecnicos);
    
    $opciones = "";
    
    while($tecnico = mysqli_fetch_array($resultadoTecnicos)) {
        $opciones .= '<option value="'.$tecnico['id_usuario'].'">'.$tecnico['usuario'].'</option>';
    }
    
    echo $opciones;
} else {
    echo '<option value="">No disponible</option>';
}
?>