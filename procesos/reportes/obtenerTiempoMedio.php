<?php
session_start();
require_once "../../clases/Conexion.php";

// Verificar que el usuario tenga rol de administrador o técnico
if (isset($_SESSION['usuario']) && ($_SESSION['usuario']['rol'] == 2 || $_SESSION['usuario']['rol'] == 3)) {
    $conexion = new Conexion();
    $con = $conexion->conectar();
    
    // Consulta para obtener tiempo medio de resolución
    $sql = "SELECT 
                AVG(TIMESTAMPDIFF(HOUR, fecha, fechaCierre)) as tiempoMedio,
                COUNT(*) as totalResueltos
            FROM 
                t_reportes 
            WHERE 
                estatus = 0 
                AND fechaCierre IS NOT NULL";
    
    $resultado = mysqli_query($con, $sql);
    
    if ($resultado) {
        $datos = mysqli_fetch_assoc($resultado);
        
        // Formateamos los resultados
        $tiempoMedio = $datos['tiempoMedio'] !== NULL ? round($datos['tiempoMedio'], 2) : 0;
        $totalResueltos = $datos['totalResueltos'] ?? 0;
        
        $respuesta = [
            'tiempoMedio' => $tiempoMedio,
            'totalResueltos' => $totalResueltos
        ];
    } else {
        // Si hay un error en la consulta
        $respuesta = [
            'error' => 'Error en la consulta: ' . mysqli_error($con),
            'tiempoMedio' => 0,
            'totalResueltos' => 0
        ];
    }
    
    // Devolver los datos
    header('Content-Type: application/json');
    echo json_encode($respuesta);
} else {
    echo json_encode([
        'error' => 'No tiene permisos para esta acción',
        'tiempoMedio' => 0,
        'totalResueltos' => 0
    ]);
}
?>