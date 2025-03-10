<?php
// Incluir el archivo de conexión a la base de datos
include "../../../clases/Conexion.php";

// Crear una instancia de conexión
$conexion = new Conexion();
$conexion = $conexion->conectar();

// Obtener los datos del formulario
$idrarea = mysqli_real_escape_string($conexion, $_POST['idrareaEditar']);
$nombreArea = mysqli_real_escape_string($conexion, $_POST['nombreAreaEditar']);

// Verificar si el nombre ya existe para otro área (evitar duplicados)
$verificar = "SELECT COUNT(*) as total FROM t_area WHERE Nomb_area = '$nombreArea' AND idrarea != '$idrarea'";
$result = mysqli_query($conexion, $verificar);
$row = mysqli_fetch_assoc($result);

if ($row['total'] > 0) {
    echo "duplicado";
    exit;
}

// Actualizar el area
$sql = "UPDATE t_area SET Nomb_area = '$nombreArea' WHERE idrarea = '$idrarea'";

if (mysqli_query($conexion, $sql)) {
    echo "success";
} else {
    echo "Error: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>