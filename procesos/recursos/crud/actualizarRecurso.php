
<?php
session_start();
require_once "../../../clases/Conexion.php";
require_once "../../../clases/Recursos.php";

$Recursos = new Recursos();

$datos = array(
    'idRecurso' => $_POST['idRecurso'],
    'nombre' => $_POST['nombre'],
    'descripcion' => $_POST['descripcion'],
    'categSH' => $_POST['categSH']
);

echo $Recursos->actualizarRecurso($datos);
?>