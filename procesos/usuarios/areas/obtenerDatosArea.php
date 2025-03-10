<?php
// Incluir el archivo de conexión a la base de datos
include "../../../clases/Conexion.php";

// Crear una instancia de conexión
$conexion = new Conexion();
$conexion = $conexion->conectar();

// Obtener el ID del área
$idrarea = mysqli_real_escape_string($conexion, $_POST['idrarea']);

// Consulta SQL para obtener los datos del área
$sql = "SELECT idrarea, Nomb_area FROM t_area WHERE idrarea = '$idrarea'";
$result = mysqli_query($conexion, $sql);

// Verificar si se encontró el área
if (mysqli_num_rows($result) > 0) {
    $datos = mysqli_fetch_assoc($result);
    echo json_encode($datos);
} else {
    echo json_encode(["error" => "Área no encontrada"]);
}

mysqli_close($conexion);
?>