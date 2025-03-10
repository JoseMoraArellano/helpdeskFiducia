<?php
// Incluir el archivo de conexión a la base de datos
include "../../../clases/Conexion.php";

// Crear una instancia de conexión
$conexion = new Conexion();
$conexion = $conexion->conectar();

// Obtener el ID del área
$idrarea = mysqli_real_escape_string($conexion, $_POST['idrarea']);

// Eliminar el área de la base de datos
$sql = "DELETE FROM t_area WHERE idrarea = '$idrarea'";

if (mysqli_query($conexion, $sql)) {
    echo "success";
} else {
    echo "Error: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>