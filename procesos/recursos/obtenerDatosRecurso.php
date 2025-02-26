/*
<?php
//session_start();
//require_once "../../../clases/Conexion.php";
//require_once "../../../clases/Recursos.php";
// $Recursos = new Recursos();
// $idRecurso = $_POST['idRecurso'];
// echo json_encode($Recursos->obtenerDatosRecurso($idRecurso));
?>
*/
<?php
session_start();
require_once "../../../clases/Conexion.php";
require_once "../../../clases/Recursos.php";

// Verificar que el usuario tenga permisos (rol 2 - admin)
if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 2) {
    $Recursos = new Recursos();
    $idRecurso = $_POST['idRecurso'];
    
    // Registrar la solicitud para depuración
    error_log("Solicitud de datos para recurso ID: " . $idRecurso);
    
    $datos = $Recursos->obtenerDatosRecurso($idRecurso);
    
    // Registrar la respuesta para depuración
    error_log("Datos obtenidos: " . json_encode($datos));
    
    echo json_encode($datos);
} else {
    echo json_encode(["error" => "No tiene permisos para realizar esta acción"]);
}
?>