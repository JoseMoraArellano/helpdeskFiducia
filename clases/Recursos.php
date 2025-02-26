<?php
include "Conexion.php";

class Recursos extends Conexion {
    public function agregarRecurso($datos) {
        $conexion = parent::conectar();
        $sql = "INSERT INTO t_cat_equipo (nombre, 
                                          descripcion, 
                                          categ_SH) 
                VALUES (?, ?, ?)";
                
        $query = $conexion->prepare($sql);
        $query->bind_param('sss', $datos['nombre'],
                                $datos['descripcion'],
                                $datos['categSH']);
        $respuesta = $query->execute();
        $query->close();
        
        return $respuesta;
    }
    /*
    public function obtenerDatosRecurso($idRecurso) {
        $conexion = parent::conectar();
        $sql = "SELECT id_equipo AS idRecurso,
                       nombre,
                       descripcion,
                       categ_SH AS categSH
                FROM t_cat_equipo 
                WHERE id_equipo = '$idRecurso'";
                
        $result = mysqli_query($conexion, $sql);
        
        $recurso = mysqli_fetch_array($result);
        
        $datos = array(
            'idRecurso' => $recurso['idRecurso'],
            'nombre' => $recurso['nombre'],
            'descripcion' => $recurso['descripcion'],
            'categSH' => $recurso['categSH']
        );
        
        return $datos;
    }
    */
    public function obtenerDatosRecurso($idRecurso) {
        $conexion = parent::conectar();
        
        // Usar consulta preparada para mayor seguridad
        $sql = "SELECT id_equipo, nombre, descripcion, categ_SH 
                FROM t_cat_equipo 
                WHERE id_equipo = ?";
                
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idRecurso);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($recurso = $resultado->fetch_assoc()) {
            // Renombrar las claves para la interfaz
            $datos = array(
                'idRecurso' => $recurso['id_equipo'],
                'nombre' => $recurso['nombre'],
                'descripcion' => $recurso['descripcion'],
                'categSH' => $recurso['categ_SH']
            );
            
            $stmt->close();
            return $datos;
        } else {
            $stmt->close();
            return array(
                'idRecurso' => 0,
                'nombre' => '',
                'descripcion' => '',
                'categSH' => ''
            );
        }
    }
    /*
    public function actualizarRecurso($datos) {
        $conexion = parent::conectar();
        $sql = "UPDATE t_cat_equipo 
                SET nombre = ?, 
                    descripcion = ?, 
                    categ_SH = ? 
                WHERE id_equipo = ?";
                
        $query = $conexion->prepare($sql);
        $query->bind_param('sssi', $datos['nombre'],
                                  $datos['descripcion'],
                                  $datos['categSH'],
                                  $datos['idRecurso']);
        $respuesta = $query->execute();
        $query->close();
        
        return $respuesta;
    }
    */
    public function actualizarRecurso($datos) {
        $conexion = parent::conectar();
        
        // Usar consulta preparada
        $sql = "UPDATE t_cat_equipo 
                SET nombre = ?, 
                    descripcion = ?, 
                    categ_SH = ? 
                WHERE id_equipo = ?";
                
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('sssi', 
        $datos['nombre'],
        $datos['descripcion'],
        $datos['categSH'],
        $datos['idRecurso']);
        
        $respuesta = $stmt->execute();
        
        // Registrar el resultado para depuración
        error_log("Actualización de recurso ID: " . $datos['idRecurso'] . ", Resultado: " . ($respuesta ? "Exitoso" : "Fallido"));
        
        $stmt->close();
        return $respuesta;
    }
    public function eliminarRecurso($idRecurso) {
        $conexion = parent::conectar();
        
        // Verificar si el recurso está asignado a algún usuario
        $sqlVerificar = "SELECT COUNT(*) AS total FROM t_asignacion WHERE id_equipo = ?";
        $queryVerificar = $conexion->prepare($sqlVerificar);
        $queryVerificar->bind_param('i', $idRecurso);
        $queryVerificar->execute();
        $resultVerificar = $queryVerificar->get_result();
        $total = $resultVerificar->fetch_assoc()['total'];
        $queryVerificar->close();
        
        if ($total > 0) {
            return "Este recurso está asignado a " . $total . " usuario(s) y no puede ser eliminado";
        }
        
        // Si no está asignado, proceder con la eliminación
        $sql = "DELETE FROM t_cat_equipo WHERE id_equipo = ?";
        $query = $conexion->prepare($sql);
        $query->bind_param('i', $idRecurso);
        $respuesta = $query->execute();
        $query->close();
        
        return $respuesta;
    }
}
?>