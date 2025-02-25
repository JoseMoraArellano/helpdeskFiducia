<?php
    include "Conexion.php";

    class Reportes extends Conexion {
        public function agregarReporteCliente($datos) {
            $conexion = parent::conectar();
            $sql = "INSERT INTO t_reportes (id_usuario,
                                            id_equipo,
                                            descripcion_problema) 
                    VALUES (?, ?, ?)";
            $query = $conexion->prepare($sql);
            $query->bind_param('iis', $datos['idUsuario'],
                                        $datos['idEquipo'],
                                        $datos['problema']);
            $respuesta = $query->execute();
            
            if ($respuesta) {
                $idReporte = $conexion->insert_id; // Obtener el ID del reporte insertado
                $query->close();
                return $idReporte;
            } else {
                $query->close();
                return 0;
            }
        }

        public function eliminarReporteCliente($idReporte) {
            $conexion = parent::conectar();
            
            // Primero obtenemos información sobre los archivos para eliminarlos del sistema
            $sqlArchivos = "SELECT ruta FROM t_archivos_reportes WHERE id_reporte = ?";
            $queryArchivos = $conexion->prepare($sqlArchivos);
            $queryArchivos->bind_param('i', $idReporte);
            $queryArchivos->execute();
            $resultadoArchivos = $queryArchivos->get_result();
            
            // Eliminar archivos físicos
            while ($archivo = $resultadoArchivos->fetch_assoc()) {
                if (file_exists($archivo['ruta'])) {
                    unlink($archivo['ruta']);
                }
            }
            
            $queryArchivos->close();
            
            // Eliminar el reporte (los archivos se eliminan automáticamente por la restricción de clave foránea)
            $sql = "DELETE FROM t_reportes WHERE id_reporte = ?";
            $query = $conexion->prepare($sql);
            $query->bind_param('i', $idReporte);
            $respuesta = $query->execute();
            $query->close();
            return $respuesta;
        }

        public function obtenerSolucion($idReporte) {
            $conexion = parent::conectar();
            $sql = "SELECT solucion_problema, 
                            estatus
                    FROM t_reportes 
                    WHERE id_reporte = '$idReporte'";
            $respuesta = mysqli_query($conexion, $sql);
            $reporte = mysqli_fetch_array($respuesta);

            $datos = array(
                "idReporte" => $idReporte,
                "estatus" => $reporte['estatus'],
                "solucion" => $reporte['solucion_problema']
            );

            return $datos;
        }

        public function actualizarSolucion($datos) {
            $conexion = parent::conectar();
            $sql = "UPDATE t_reportes 
                    SET id_usuario_tecnico = ?,
                        solucion_problema = ?,
                        estatus = ? 
                    WHERE id_reporte = ?";
            $query = $conexion->prepare($sql);
            $query->bind_param('isii', $datos['idUsuario'],
                                        $datos['solucion'],
                                        $datos['estatus'],
                                        $datos['idReporte']);
            $respuesta = $query->execute();
            $query->close();
            return $respuesta;
        }
        
        // Nuevo método para obtener archivos de un reporte
        public function obtenerArchivosReporte($idReporte) {
            $conexion = parent::conectar();
            $sql = "SELECT 
                        id_archivo,
                        nombre_original,
                        nombre_sistema,
                        ruta,
                        extension,
                        tipo_mime,
                        tamano,
                        fecha_subida
                    FROM 
                        t_archivos_reportes 
                    WHERE 
                        id_reporte = ?";
            $query = $conexion->prepare($sql);
            $query->bind_param('i', $idReporte);
            $query->execute();
            
            $resultado = $query->get_result();
            $archivos = array();
            
            while ($archivo = $resultado->fetch_assoc()) {
                $archivos[] = $archivo;
            }
            
            $query->close();
            return $archivos;
        }
    }
?>