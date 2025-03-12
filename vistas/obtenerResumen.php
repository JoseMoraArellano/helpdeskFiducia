<?php
session_start();
include "../clases/Conexion.php";
$con = new Conexion();
$conexion = $con->conectar();

// Verificar que el usuario esté autenticado y tenga rol 2
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] != 2) {
    echo json_encode(['error' => 'Usuario no autorizado']);
    exit;
}

// Conteo por estatus de reportes
$sqlEnProceso = "SELECT COUNT(*) as total FROM t_reportes WHERE estatus = 2";
$respuestaEnProceso = mysqli_query($conexion, $sqlEnProceso);
$totalEnProceso = mysqli_fetch_array($respuestaEnProceso)[0] ?? 0;

$sqlAbiertos = "SELECT COUNT(*) as total FROM t_reportes WHERE estatus = 1";
$respuestaAbiertos = mysqli_query($conexion, $sqlAbiertos);
$totalAbiertos = mysqli_fetch_array($respuestaAbiertos)[0] ?? 0;

$sqlCerrados = "SELECT COUNT(*) as total FROM t_reportes WHERE estatus = 0";
$respuestaCerrados = mysqli_query($conexion, $sqlCerrados);
$totalCerrados = mysqli_fetch_array($respuestaCerrados)[0] ?? 0;

// Reporte más antiguo abierto (en días)
$sqlAntiguoAbierto = "SELECT 
                        DATEDIFF(CURRENT_DATE(), fecha) as dias_antiguedad 
                      FROM 
                        t_reportes 
                      WHERE 
                        estatus = 1 
                      ORDER BY 
                        fecha ASC 
                      LIMIT 1";
$respuestaAntiguoAbierto = mysqli_query($conexion, $sqlAntiguoAbierto);
$diasAntiguoAbierto = mysqli_fetch_array($respuestaAntiguoAbierto)[0] ?? 0;

// Reporte más antiguo en proceso (en días)
$sqlAntiguoEnProceso = "SELECT 
                          DATEDIFF(CURRENT_DATE(), fecha) as dias_antiguedad 
                        FROM 
                          t_reportes 
                        WHERE 
                          estatus = 2 
                        ORDER BY 
                          fecha ASC 
                        LIMIT 1";
$respuestaAntiguoEnProceso = mysqli_query($conexion, $sqlAntiguoEnProceso);
$diasAntiguoEnProceso = mysqli_fetch_array($respuestaAntiguoEnProceso)[0] ?? 0;

// Conteo de usuarios Técnicos
$sqlTecnicos = "SELECT COUNT(*) as total FROM t_usuarios WHERE id_rol = 3 AND activo = 1";
$respuestaTecnicos = mysqli_query($conexion, $sqlTecnicos);
$totalTecnicos = mysqli_fetch_array($respuestaTecnicos)[0] ?? 0;

// Conteo de usuarios Clientes
$sqlClientes = "SELECT COUNT(*) as total FROM t_usuarios WHERE id_rol = 1 AND activo = 1";
$respuestaClientes = mysqli_query($conexion, $sqlClientes);
$totalClientes = mysqli_fetch_array($respuestaClientes)[0] ?? 0;

// Preparar respuesta
$respuesta = [
    'reportes' => [
        'abiertos' => $totalAbiertos,
        'enProceso' => $totalEnProceso,
        'cerrados' => $totalCerrados,
        'total' => $totalAbiertos + $totalEnProceso + $totalCerrados
    ],
    'antiguedad' => [
        'abiertosDias' => $diasAntiguoAbierto,
        'enProcesoDias' => $diasAntiguoEnProceso
    ],
    'usuarios' => [
        'tecnicos' => $totalTecnicos,
        'clientes' => $totalClientes
    ]
];

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($respuesta);