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
    
    public function obtenerDatosRecurso($idRecurso) {
        $conexion = parent::conectar();
        
        // Consulta SQL
        $sql = "SELECT 
                    id_equipo as idRecurso,
                    nombre,
                    descripcion,
                    categ_SH as categSH
                FROM 
                    t_cat_equipo 
                WHERE 
                    id_equipo = '$idRecurso'";
        
        $resultado = mysqli_query($conexion, $sql);
        
        // Verificar si se encontraron datos
        if ($fila = mysqli_fetch_assoc($resultado)) {
            return $fila;
        } else {
            return [
                "idRecurso" => 0,
                "nombre" => "",
                "descripcion" => "",
                "categSH" => ""
            ];
        }
    }
    
    public function actualizarRecurso($datos) {
        $conexion = parent::conectar();
        
        // Consulta SQL
        $sql = "UPDATE t_cat_equipo 
                SET nombre = '" . $datos['nombre'] . "',
                    descripcion = '" . $datos['descripcion'] . "',
                    categ_SH = '" . $datos['categSH'] . "'
                WHERE id_equipo = " . $datos['idRecurso'];
        
        $resultado = mysqli_query($conexion, $sql);
        
        // Devolver si la operación fue exitosa
        return $resultado ? 1 : 0;
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