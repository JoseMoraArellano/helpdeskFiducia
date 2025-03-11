<?php

if (
    isset($_POST['paterno']) && 
    isset($_POST['materno']) && 
    isset($_POST['nombre']) && 
    isset($_POST['fechaNacimiento']) && 
    isset($_POST['sexo']) && 
    isset($_POST['telefono']) && 
    isset($_POST['correo']) && 
    isset($_POST['usuario']) && 
    isset($_POST['password']) && 
    isset($_POST['idRol']) && 
    isset($_POST['idArea'])
) {
    // Crear array con los datos del formulario
    $datos = array(
        "paterno" => $_POST['paterno'], 
        "materno" => $_POST['materno'],
        "nombre" => $_POST['nombre'], 
        "fechaNacimiento" => $_POST['fechaNacimiento'], 
        "sexo" => $_POST['sexo'], 
        "telefono" => $_POST['telefono'], 
        "correo" => $_POST['correo'], 
        "usuario" => $_POST['usuario'], 
        "password" => sha1($_POST['password']), 
        "idRol" => $_POST['idRol'],
        "idArea" => $_POST['idArea'],
        "ubicacion" => isset($_POST['ubicacion']) ? $_POST['ubicacion'] : ''
    );

include "../../../clases/Usuarios.php";
$Usuarios = new Usuarios();

$resultado = $Usuarios->agregaNuevoUsuario($datos);
echo $resultado;
} else {
// Si faltan campos requeridos, devolver error
echo 0;
}
// echo $Usuarios->agregaNuevoUsuario($datos);