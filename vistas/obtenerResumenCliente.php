<?php
session_start();
include "../clases/Conexion.php";
$con = new Conexion();
$conexion = $con->conectar();

// Verificar que el usuario esté autenticado y sea un cliente (rol 1)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] != 1) {
    echo json_encode(['error' => 'Usuario no autorizado']);
    exit;
}

// Obtener el ID del usuario
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : $_SESSION['usuario']['id'];

// Verificar que el usuario solo acceda a sus propios datos
if ($idUsuario != $_SESSION['usuario']['id']) {
    echo json_encode(['error' => 'Acceso no autorizado a datos de otro usuario']);
    exit;
}

// Consulta para obtener reportes abiertos del usuario
$sqlAbiertos = "SELECT COUNT(*) as total FROM t_reportes WHERE id_usuario = ? AND estatus = 1";
$stmt = $conexion->prepare($sqlAbiertos);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultadoAbiertos = $stmt->get_result();
$totalAbiertos = $resultadoAbiertos->fetch_assoc()['total'] ?? 0;

// Consulta para obtener reportes en proceso del usuario
$sqlEnProceso = "SELECT COUNT(*) as total FROM t_reportes WHERE id_usuario = ? AND estatus = 2";
$stmt = $conexion->prepare($sqlEnProceso);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultadoEnProceso = $stmt->get_result();
$totalEnProceso = $resultadoEnProceso->fetch_assoc()['total'] ?? 0;

// Consulta para obtener reportes cerrados del usuario
$sqlCerrados = "SELECT COUNT(*) as total FROM t_reportes WHERE id_usuario = ? AND estatus = 0";
$stmt = $conexion->prepare($sqlCerrados);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultadoCerrados = $stmt->get_result();
$totalCerrados = $resultadoCerrados->fetch_assoc()['total'] ?? 0;

// Preparar respuesta
$respuesta = [
    'abiertos' => $totalAbiertos,
    'enProceso' => $totalEnProceso,
    'cerrados' => $totalCerrados,
    'total' => $totalAbiertos + $totalEnProceso + $totalCerrados
];

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($respuesta);