<?php
session_start();
require_once "../../../clases/Conexion.php";
require_once "../../../clases/Recursos.php";

$Recursos = new Recursos();
$idRecurso = $_POST['idRecurso'];

echo $Recursos->eliminarRecurso($idRecurso);
?>