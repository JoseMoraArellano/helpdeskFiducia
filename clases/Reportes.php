<?php
//    include "Conexion.php";
require_once "Conexion.php";
    class Reportes extends Conexion {
        public function agregarReporteCliente($datos) {
            $conexion = parent::conectar();
            $sql = "INSERT INTO t_reportes (id_usuario,
                                        id_equipo,
                                        descripcion_problema,
                                        fechaRApert) 
            VALUES (?, ?, ?, NOW())";
            $query = $conexion->prepare($sql);
            $query->bind_param('iis', $datos['idUsuario'],
                                        $datos['idEquipo'],
                                        $datos['problema']);
            $respuesta = $query->execute();
            $query->close();
            return $respuesta;
        }

        public function eliminarReporteCliente($idReporte) {
            $conexion = parent::conectar();
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
            
            // Obtenemos la fecha y hora actual
            $fechaActual = date('Y-m-d H:i:s');
            
            // Primero obtenemos el estatus actual del reporte
            $sqlObtenerEstatus = "SELECT estatus FROM t_reportes WHERE id_reporte = ?";
            $queryEstatus = $conexion->prepare($sqlObtenerEstatus);
            $queryEstatus->bind_param('i', $datos['idReporte']);
            $queryEstatus->execute();
            $resultado = $queryEstatus->get_result();
            $estadoActual = $resultado->fetch_assoc()['estatus'];
            $queryEstatus->close();
            
            // Definimos el nuevo estatus
            $nuevoEstado = $datos['estatus'];
            
            // CASO 1: Lo abro cuando antes estaba cerrado (0->1)
            if ($estadoActual == 0 && $nuevoEstado == 1) {
                $sql = "UPDATE t_reportes 
                        SET id_usuario_tecnico = ?,
                            solucion_problema = ?,
                            estatus = ?,
                            fechaAct = ?,
                            fechaRApert = ?,
                            fechaCierre = '0000-00-00 00:00:00'
                        WHERE id_reporte = ?";
                $query = $conexion->prepare($sql);
                $query->bind_param('issisi', 
                                $datos['idUsuario'],
                                $datos['solucion'],
                                $nuevoEstado,
                                $fechaActual,
                                $fechaActual,
                                $datos['idReporte']);
            }
            // CASO 2: Lo cierro cuando estaba abierto (1->0)
            else if ($estadoActual == 1 && $nuevoEstado == 0) {
                $sql = "UPDATE t_reportes 
                        SET id_usuario_tecnico = ?,
                            solucion_problema = ?,
                            estatus = ?,
                            fechaAct = ?,
                            fechaCierre = ?
                        WHERE id_reporte = ?";
                $query = $conexion->prepare($sql);
                $query->bind_param('issisi', 
                                $datos['idUsuario'],
                                $datos['solucion'],
                                $nuevoEstado,
                                $fechaActual,
                                $fechaActual,
                                $datos['idReporte']);
            }
            // CASO 3: Reapertura (2) sin importar el estado anterior
            else if ($estadoActual == 0 && $nuevoEstado == 2) {
                $sql = "UPDATE t_reportes 
                        SET id_usuario_tecnico = ?,
                            solucion_problema = ?,
                            estatus = ?,
                            fechaAct = ?,
                            fechaRApert = ?,
                            fechaCierre = '0000-00-00 00:00:00'
                        WHERE id_reporte = ?";
                $query = $conexion->prepare($sql);
                $query->bind_param('issisi', 
                                $datos['idUsuario'],
                                $datos['solucion'],
                                $nuevoEstado,
                                $fechaActual,
                                $fechaActual,
                                $datos['idReporte']);
            }
            // Caso por defecto (otras transiciones no especificadas)
            else {
                $sql = "UPDATE t_reportes 
                        SET id_usuario_tecnico = ?,
                            solucion_problema = ?,
                            estatus = ?,
                            fechaAct = ?
                        WHERE id_reporte = ?";
                $query = $conexion->prepare($sql);
                $query->bind_param('isssi', 
                                $datos['idUsuario'],
                                $datos['solucion'],
                                $nuevoEstado,
                                $fechaActual,
                                $datos['idReporte']);
            }
            
            $respuesta = $query->execute();
            $query->close();
            return $respuesta;
        }
    }