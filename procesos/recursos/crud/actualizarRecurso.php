<?php
session_start();
require_once "../../../clases/Conexion.php";
require_once "../../../clases/Recursos.php";

// Verificar que se recibieron los datos
if (isset($_POST['idRecurso']) && isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['categSH'])) {
    
    $datos = [
        "idRecurso" => $_POST['idRecurso'],
        "nombre" => $_POST['nombre'],
        "descripcion" => $_POST['descripcion'],
        "categSH" => $_POST['categSH']
    ];
    
    // Crear instancia de la clase
    $recursos = new Recursos();
    
    // Actualizar los datos
    $resultado = $recursos->actualizarRecurso($datos);
    
    // Devolver el resultado
    echo $resultado;
} else {
    echo "No se recibieron todos los datos necesarios";
}
?>