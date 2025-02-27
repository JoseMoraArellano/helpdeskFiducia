<?php
session_start();
require_once "../../clases/Conexion.php";
require_once "../../clases/Reportes.php";

// Verificar que los datos necesarios fueron enviados
if (isset($_POST['idReporte']) && isset($_POST['solucion']) && isset($_POST['estatus']) && isset($_POST['idTecnico'])) {
    $datos = array(
        "idReporte" => $_POST['idReporte'],
        "solucion" => $_POST['solucion'],
        "estatus" => $_POST['estatus'],
        "idUsuario" => $_POST['idTecnico'] // Ahora usamos el ID del técnico seleccionado
    );
    
    $Reportes = new Reportes();
    echo $Reportes->actualizarSolucion($datos);
} else {
    echo "Faltan datos requeridos para actualizar la solución";
}
?>