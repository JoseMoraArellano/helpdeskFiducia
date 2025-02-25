<?php
session_start();
require_once "../clases/Conexion.php";

// Verificar que el usuario esté logueado
if (isset($_SESSION['usuario'])) {
    $idReporte = $_POST['idReporte'];
    $rolUsuario = $_SESSION['usuario']['rol'];
    $idUsuario = $_SESSION['usuario']['id'];
    
    $conexion = new Conexion();
    $conexion = $conexion->conectar();
    
    // Si es cliente, verificar que el reporte le pertenezca
    $tienePermiso = true;
    if ($rolUsuario == 1) { // Cliente
        $sqlVerificar = "SELECT id_reporte FROM t_reportes WHERE id_reporte = ? AND id_usuario = ?";
        $queryVerificar = $conexion->prepare($sqlVerificar);
        $queryVerificar->bind_param('ii', $idReporte, $idUsuario);
        $queryVerificar->execute();
        $resultadoVerificar = $queryVerificar->get_result();
        
        if ($resultadoVerificar->num_rows == 0) {
            $tienePermiso = false;
        }
        
        $queryVerificar->close();
    }
    
    if ($tienePermiso) {
        $sql = "SELECT * FROM t_archivos_reportes WHERE id_reporte = ?";
        $query = $conexion->prepare($sql);
        $query->bind_param('i', $idReporte);
        $query->execute();
        $resultado = $query->get_result();
        
        if ($resultado->num_rows > 0) {
            echo '<div class="list-group">';
            while ($archivo = $resultado->fetch_assoc()) {
                $icono = '';
                $extension = strtolower($archivo['extension']);
                
                // Determinar icono según extensión
                switch ($extension) {
                    case 'pdf':
                        $icono = '<i class="fas fa-file-pdf text-danger mr-2"></i>';
                        break;
                    case 'doc':
                    case 'docx':
                        $icono = '<i class="fas fa-file-word text-primary mr-2"></i>';
                        break;
                    case 'xls':
                    case 'xlsx':
                        $icono = '<i class="fas fa-file-excel text-success mr-2"></i>';
                        break;
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        $icono = '<i class="fas fa-file-image text-info mr-2"></i>';
                        break;
                    default:
                        $icono = '<i class="fas fa-file text-secondary mr-2"></i>';
                }
                
                // Crear ruta relativa para acceso web
                $rutaWeb = str_replace('../../', '../', $archivo['ruta']);
                
                echo '<a href="' . $rutaWeb . '" class="list-group-item list-group-item-action" target="_blank" download="' . $archivo['nombre_original'] . '">';
                echo $icono . ' ' . $archivo['nombre_original'] . ' <small class="text-muted">(' . round($archivo['tamano']/1024, 2) . ' KB)</small>';
                echo '</a>';
            }
            echo '</div>';
        } else {
            echo '<p class="text-muted text-center">No hay archivos adjuntos para este reporte</p>';
        }
        
        $query->close();
    } else {
        echo '<p class="text-danger text-center">No tiene permisos para ver estos archivos</p>';
    }
} else {
    echo '<p class="text-danger text-center">Debe iniciar sesión para ver esta información</p>';
}
?>