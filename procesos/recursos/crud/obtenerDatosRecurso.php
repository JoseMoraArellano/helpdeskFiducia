<?php
session_start();
require_once "../../../clases/Conexion.php";
require_once "../../../clases/Recursos.php";

// Verificar que se recibió el ID
if (isset($_POST['idRecurso'])) {
    $idRecurso = $_POST['idRecurso'];
    
    // Crear instancia de la clase
    $recursos = new Recursos();
    
    // Obtener los datos
    $datos = $recursos->obtenerDatosRecurso($idRecurso);
    
    // Devolver los datos como JSON
    echo json_encode($datos);
} else {
    echo json_encode(["error" => "No se recibió el ID del recurso"]);
}
?>