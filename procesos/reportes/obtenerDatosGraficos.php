<?php
session_start();
require_once "../../clases/Conexion.php";

// Verificar que el usuario tenga rol de administrador
if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 2 || $_SESSION['usuario']['rol'] == 3) {
    $conexion = new Conexion();
    $con = $conexion->conectar();
    
    // Datos para las tarjetas de resumen
    $datosResumen = array();
    
    // Reportes abiertos
    $sqlAbiertos = "SELECT COUNT(*) as total FROM t_reportes WHERE estatus = 1";
    $resultadoAbiertos = mysqli_query($con, $sqlAbiertos);
    $datosResumen['abiertos'] = mysqli_fetch_assoc($resultadoAbiertos)['total'];

    // Reportes proceso
    $sqlProceso = "SELECT COUNT(*) as total FROM t_reportes WHERE estatus = 2";
    $resultadoProceso = mysqli_query($con, $sqlProceso);
    $datosResumen['proceso'] = mysqli_fetch_assoc($resultadoProceso)['total'];
        
    // Reportes cerrados
    $sqlCerrados = "SELECT COUNT(*) as total FROM t_reportes WHERE estatus = 0";
    $resultadoCerrados = mysqli_query($con, $sqlCerrados);
    $datosResumen['cerrados'] = mysqli_fetch_assoc($resultadoCerrados)['total'];
    
    // Total de reportes
    $datosResumen['total'] = $datosResumen['abiertos'] + $datosResumen['cerrados'] +$datosResumen['proceso'];
    
    // Datos para gráfico de reportes por dispositivo
    $sqlDispositivos = "SELECT 
                            equipo.nombre as dispositivo,
                            COUNT(*) as total
                        FROM 
                            t_reportes as reporte
                        INNER JOIN 
                            t_cat_equipo as equipo ON reporte.id_equipo = equipo.id_equipo
                        GROUP BY 
                            reporte.id_equipo
                        ORDER BY 
                            total DESC
                        LIMIT 50";
    
    $resultadoDispositivos = mysqli_query($con, $sqlDispositivos);
    $datosDispositivos = array();
    
    while ($fila = mysqli_fetch_assoc($resultadoDispositivos)) {
        $datosDispositivos[] = array(
            'dispositivo' => $fila['dispositivo'],
            'total' => intval($fila['total'])
        );
    }
    
    // Datos para gráfico de reportes por mes y por técnico
$sqlMensualTecnicos = 
"SELECT 
DATE_FORMAT(r.fecha, '%Y-%m') as mes,
u.usuario as tecnico,
COUNT(*) as total
FROM 
t_reportes as r
INNER JOIN t_usuarios as u ON r.id_usuario_tecnico = u.id_usuario
WHERE 
r.id_usuario_tecnico IS NOT NULL
GROUP BY 
DATE_FORMAT(r.fecha, '%Y-%m'), r.id_usuario_tecnico
ORDER BY 
mes, tecnico
LIMIT 100";

$resultadoMensualTecnicos = mysqli_query($con, $sqlMensualTecnicos);
$datosMensualTecnicos = array();

while ($fila = mysqli_fetch_assoc($resultadoMensualTecnicos)) {
$datosMensualTecnicos[] = array(
'mes' => $fila['mes'],
'tecnico' => $fila['tecnico'],
'total' => intval($fila['total'])
);
}

// Agregar los datos al array final
$datos['mensualTecnicos'] = $datosMensualTecnicos;

    // Datos para gráfico de reportes por mes
    $sqlMensual = "SELECT 
                        DATE_FORMAT(fecha, '%Y-%m') as mes,
                        COUNT(*) as total
                    FROM 
                        t_reportes
                    GROUP BY 
                        DATE_FORMAT(fecha, '%Y-%m')
                    ORDER BY 
                        mes
                    LIMIT 12";
    
    $resultadoMensual = mysqli_query($con, $sqlMensual);
    $datosMensual = array();
    
    while ($fila = mysqli_fetch_assoc($resultadoMensual)) {
        $datosMensual[] = array(
            'mes' => $fila['mes'],
            'total' => intval($fila['total'])
        );
    }
    
    
    // Datos para gráfico de tickets cerrados por técnico
    $sqlTecnicos = "SELECT 
                        u.usuario as tecnico,
                        COUNT(*) as total
                    FROM 
                        t_reportes as r
                    INNER JOIN 
                        t_usuarios as u ON r.id_usuario_tecnico = u.id_usuario
                    WHERE 
                        r.estatus = 0
                    GROUP BY 
                        r.id_usuario_tecnico
                    ORDER BY 
                        total DESC";
    
    $resultadoTecnicos = mysqli_query($con, $sqlTecnicos);
    $datosTecnicos = array();
    
    while ($fila = mysqli_fetch_assoc($resultadoTecnicos)) {
        $datosTecnicos[] = array(
            'tecnico' => $fila['tecnico'],
            'total' => intval($fila['total'])
        );
    }
    
    // Construir array final
    $datos = array(
        'resumen' => $datosResumen,
        'dispositivos' => $datosDispositivos,
        'mensual' => $datosMensual,
        'mensualTecnicos' => $datosMensualTecnicos,
        'tecnicos' => $datosTecnicos  // Añadimos los datos de técnicos
    );
    
    // Devolver datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($datos);
} else {
    echo json_encode(array('error' => 'No tiene permisos para ver esta información'));
}
?>