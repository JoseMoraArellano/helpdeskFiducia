<?php
// Incluir el archivo de conexión a la base de datos
include "../../../clases/Conexion.php";

// Crear una instancia de conexión
$conexion = new Conexion();
$conexion = $conexion->conectar();

// Obtener los datos del formulario
$nombreArea = mysqli_real_escape_string($conexion, $_POST['nombreArea']);

// Verificar si el área ya existe para evitar duplicados
$verificar = "SELECT COUNT(*) as total FROM t_area WHERE Nomb_area = '$nombreArea'";
$result = mysqli_query($conexion, $verificar);
$row = mysqli_fetch_assoc($result);

if ($row['total'] > 0) {
    echo "El area que intenta agregar, Ya existe";
    exit;
}

// Insertar el área en la base de datos
$sql = "INSERT INTO t_area (Nomb_area) VALUES ('$nombreArea')";

if (mysqli_query($conexion, $sql)) {
    echo "success";
} else {
    echo "Error: " . mysqli_error($conexion);
}
mysqli_close($conexion);
?>