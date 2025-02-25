<?php
session_start();
require_once "../../clases/Conexion.php";
require_once "../../clases/Reportes.php";

// Verificar que sea un usuario cliente
if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 1) {
    
    // Configuración para archivos
    $directorioBase = "../../archivos/reportes/";
    $maxFileSize = 10 * 1024 * 1024; // 10MB
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
    
    // Crear directorio base si no existe
    if (!file_exists($directorioBase)) {
        mkdir($directorioBase, 0777, true);
    }
    
    // Datos del reporte
    $datos = array(
        'idUsuario' => $_SESSION['usuario']['id'],
        'idEquipo' => $_POST['idEquipo'],
        'problema' => $_POST['problema']
    );
    
    $conexion = new Conexion();
    $conexionDB = $conexion->conectar();
    
    // Iniciar transacción
    mysqli_autocommit($conexionDB, false);
try {
    $sql="INSERT INTO t_reportes (id_usuario, id_equipo, descripcion_problema) 
    VALUES (?, ?, ?)";
    $query = $conexionDB->prepare($sql);
    $query->bind_param('iis', $datos['idUsuario'], $datos['idEquipo'], $datos['problema']);
    if(!$query->execute()){
        throw new Exception("Error al insertar el reporte".$query->error);
    }
    $idReporte = $conexionDB->insert_id;
    $query->close();
    
    if($idReporte<=0)  {
        throw new Exception("No se puede obtener el ID del reporte");
    }

    // Crear directorio específico para este reporte
    $directorioReporte = $directorioBase . $idReporte . "/";
    if (!file_exists($directorioReporte)) {
        mkdir($directorioReporte, 0777, true);
    }

    // Procesar archivos adjuntos si existen   
    if(isset($_FILES['archivosAdjuntos'])&& !empty ($_FILES['archivosAdjuntos']['name'][0])) {
        $totalArchivos = count($_FILES['archivosAdjuntos']['name']);
        for ($i = 0; $i < $totalArchivos; $i++) {
            $nombreOriginal = $_FILES['archivosAdjuntos']['name'][$i];
            $tipoMime = $_FILES['archivosAdjuntos']['type'][$i];
            $tamano = $_FILES['archivosAdjuntos']['size'][$i];
            $tempFile = $_FILES['archivosAdjuntos']['tmp_name'][$i];
            $error = $_FILES['archivosAdjuntos']['error'][$i];
            
            // Verificar errores
            if ($error !== UPLOAD_ERR_OK) {
                throw new Exception("Error al subir el archivo $nombreOriginal: Código $error");
            }
            
            // Verificar tamaño
            if ($tamano > $maxFileSize) {
                throw new Exception("El archivo $nombreOriginal excede el tamaño permitido (10MB)");
            }
            
            // Verificar extensión
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            if (!in_array($extension, $extensionesPermitidas)) {
                throw new Exception("El archivo $nombreOriginal tiene una extensión no permitida");
            }
            
            // Generar nombre único para el sistema
            $nombreSistema = uniqid('file_') . '.' . $extension;
            $rutaCompleta = $directorioReporte . $nombreSistema;
            
            // Mover el archivo
            if (!move_uploaded_file($tempFile, $rutaCompleta)) {
                throw new Exception("No se pudo guardar el archivo $nombreOriginal");
            }
            
            // Registrar en la base de datos - Usar el idReporte obtenido directamente
            $sqlArchivo = "INSERT INTO t_archivos_reportes (
                            id_reporte, 
                            nombre_original, 
                            nombre_sistema, 
                            ruta, 
                            extension, 
                            tipo_mime, 
                            tamano) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $queryArchivo = $conexionDB->prepare($sqlArchivo);
            $queryArchivo->bind_param('isssssi', 
                        $idReporte,  // Usar el ID obtenido directamente
                        $nombreOriginal,
                        $nombreSistema,
                        $rutaCompleta,
                        $extension,
                        $tipoMime,
                        $tamano);
            
            if (!$queryArchivo->execute()) {
                throw new Exception("Error al registrar archivo en la base de datos: " . $queryArchivo->error);
            }
            
            $queryArchivo->close();
        }
    }
    
    // Todo salió bien
    mysqli_commit($conexionDB);
    echo 1;
    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexionDB);
    echo $e->getMessage();
} finally {
    // Restaurar modo autocommit
    mysqli_autocommit($conexionDB, true);
}

} else {
echo "No tiene permisos para realizar esta acción";
}
?>