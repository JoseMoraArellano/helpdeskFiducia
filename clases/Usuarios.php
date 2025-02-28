<?php
    include "Conexion.php";
class Usuarios extends Conexion {
    public function loginUsuario($usuario, $password) {
        $conexion = parent::conectar();
        // Obtener información del usuario
        $sql = "SELECT id_usuario, password, accfall, activo, id_rol FROM t_usuarios WHERE usuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($datosUsuario = mysqli_fetch_assoc($resultado)) {
            if ($datosUsuario['activo'] == 0) {
                return 4; // Usuario inactivo
            }

            if ($datosUsuario['password'] === $password) {
                // Restablecer intentos fallidos al iniciar sesion correctamente
                $sqlReset = "UPDATE t_usuarios SET accfall = 0 WHERE usuario = ?";
                $stmtReset = mysqli_prepare($conexion, $sqlReset);
                mysqli_stmt_bind_param($stmtReset, "s", $usuario);
                mysqli_stmt_execute($stmtReset);
                // Iniciar sesion
                $_SESSION['usuario']['nombre'] = $usuario;
                $_SESSION['usuario']['id'] = $datosUsuario['id_usuario'];
                $_SESSION['usuario']['rol'] = $datosUsuario['id_rol'];
                return 1;
            } else {
                // Incrementar accfall
                $nuevoAccfall = $datosUsuario['accfall'] + 1;
                $sqlUpdate = "UPDATE t_usuarios SET accfall = ? WHERE usuario = ?";
                $stmtUpdate = mysqli_prepare($conexion, $sqlUpdate);
                mysqli_stmt_bind_param($stmtUpdate, "is", $nuevoAccfall, $usuario);
                mysqli_stmt_execute($stmtUpdate);
                    // Si accfall llega a 5 o mas, desactivar cuenta exepto administradores
                    if ($nuevoAccfall >= 5 && $datosUsuario['id_rol'] != 2) {
                        $sqlDeactivate = "UPDATE t_usuarios SET activo = 0 WHERE usuario = ?";
                        $stmtDeactivate = mysqli_prepare($conexion, $sqlDeactivate);
                        mysqli_stmt_bind_param($stmtDeactivate, "s", $usuario);
                        mysqli_stmt_execute($stmtDeactivate);
                    }
                return 0;
            }
        } else {
            return 0; // Usuario no encontrado
        }
    }
        public function agregaNuevoUsuario($datos) {
            $conexion = parent::conectar();
            $idPersona = $this->agregarPersona($datos);

            if ($idPersona > 0) {
                $sql = "INSERT INTO t_usuarios (id_rol, 
                                                id_persona, 
                                                usuario, 
                                                password, 
                                                ubicacion) 
                        VALUES (?, ?, ?, ?, ?)";
                $query = $conexion->prepare($sql);
                $query->bind_param("iisss", $datos['idRol'],
                                            $idPersona,
                                            $datos['usuario'],
                                            $datos['password'],
                                            $datos['ubicacion']);
                $respuesta = $query->execute();
                return $respuesta;
            } else {
                return 0;
            }

            
        }

        public function agregarPersona($datos) {
            $conexion = parent::conectar();
            $sql = "INSERT INTO t_persona (paterno,
                                            materno,
                                            nombre,
                                            fecha_nacimiento,
                                            sexo,
                                            telefono,
                                            correo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $query = $conexion->prepare($sql);
            $query->bind_param("sssssss", $datos['paterno'], 
                                        $datos['materno'],
                                        $datos['nombre'],
                                        $datos['fechaNacimiento'],
                                        $datos['sexo'],
                                        $datos['telefono'],
                                        $datos['correo']);
            $respuesta = $query->execute();
            $idPersona = mysqli_insert_id($conexion);
            $query->close();
            return $idPersona;
        }

        public function obtenerDatosUsuario($idUSuario) {
            $conexion = parent::conectar();
            
            $sql = "SELECT 
                        usuarios.id_usuario AS idUsuario,
                        usuarios.usuario AS nombreUsuario,
                        roles.nombre AS rol,
                        usuarios.id_rol AS idRol,
                        usuarios.ubicacion AS ubicacion,
                        usuarios.activo AS estatus,
                        usuarios.id_persona AS idPersona,
                        persona.nombre AS nombrePersona,
                        persona.paterno AS paterno,
                        persona.materno AS materno,
                        persona.fecha_nacimiento AS fechaNacimiento,
                        persona.sexo AS sexo,
                        persona.correo AS correo,
                        persona.telefono AS telefono
                    FROM
                        t_usuarios AS usuarios
                            INNER JOIN
                        t_cat_roles AS roles ON usuarios.id_rol = roles.id_rol
                            INNER JOIN
                        t_persona AS persona ON usuarios.id_persona = persona.id_persona
                            AND usuarios.id_usuario = '$idUSuario'";
            $respuesta = mysqli_query($conexion, $sql);
            $usuario = mysqli_fetch_array($respuesta);
            $datos = array(
                'idUsuario' => $usuario['idUsuario'],
                'nombreUsuario' => $usuario['nombreUsuario'],
                'rol' => $usuario['rol'],
                'idRol' => $usuario['idRol'],
                'ubicacion' => $usuario['ubicacion'],
                'estatus' => $usuario['estatus'],
                'idPersona' => $usuario['idPersona'],
                'nombrePersona' => $usuario['nombrePersona'],
                'paterno' => $usuario['paterno'],
                'materno' => $usuario['materno'],
                'fechaNacimiento' => $usuario['fechaNacimiento'],
                'sexo' => $usuario['sexo'],
                'correo' => $usuario['correo'],
                'telefono' => $usuario['telefono']
            );
            return $datos;
        }
        public function actualizarUsuario($datos) {
            $conexion = parent::conectar();
            $exitoPersona = $this->actualizarPersona($datos);

            if ($exitoPersona) {
                $sql = "UPDATE t_usuarios SET id_rol = ?,
                                                usuario = ?,
                                                ubicacion = ? 
                        WHERE id_usuario = ?";
                $query = $conexion->prepare($sql);
                $query->bind_param('issi', $datos['idRol'],
                                            $datos['usuario'],
                                            $datos['ubicacion'],
                                            $datos['idUsuario']);
                $respuesta = $query->execute();
                $query->close();
                return $respuesta;
            } else {
                return 0;
            }
        }
        public function actualizarPersona($datos) {
            $conexion = parent::conectar();
            $idPersona = $this->obtenerIdPersona($datos['idUsuario']);

            $sql = "UPDATE t_persona SET paterno = ?, 
                                        materno = ?,
                                        nombre = ?,
                                        fecha_nacimiento = ?,
                                        sexo = ?,
                                        telefono = ?,
                                        correo = ? 
                    WHERE id_persona = ?";
            $query = $conexion->prepare($sql);
            $query->bind_param('sssssssi', $datos['paterno'],
                                            $datos['materno'],
                                            $datos['nombre'],
                                            $datos['fechaNacimiento'],
                                            $datos['sexo'],
                                            $datos['telefono'],
                                            $datos['correo'],
                                            $idPersona);
            $respuesta = $query->execute();
            $query->close();
            return $respuesta;
        }
        public function obtenerIdPersona($idUsuario) {
            $conexion = parent::conectar();
            $sql = "SELECT 
                        persona.id_persona AS idPersona
                    FROM
                        t_usuarios AS usuarios
                            INNER JOIN
                        t_persona AS persona ON usuarios.id_persona = persona.id_persona
                            AND usuarios.id_usuario = '$idUsuario' ";
            $respuesta = mysqli_query($conexion, $sql);
            $idPersona = mysqli_fetch_array($respuesta)['idPersona'];
            return $idPersona;
        }

        public function resetPassword($datos) {
            $conexion = parent::conectar();
            $sql = "UPDATE t_usuarios 
                    SET password = ? 
                    WHERE id_usuario = ?";
            $query = $conexion->prepare($sql);
            $query->bind_param('si', $datos['password'], 
                                    $datos['idUsuario']);
            $respuesta = $query->execute();
            $query->close();

            return $respuesta;
        }

        public function cambioEstatusUsuario($idUsuario, $estatus) {
            $conexion = parent::conectar();

            if ($estatus == 1) {
                $estatus = 0; 
            } else {
                $estatus = 1;
            }

            $sql = "UPDATE t_usuarios 
                    SET activo = ? 
                    WHERE id_usuario = ?
                    and usuario != 'admin'
                    and id_usuario != 1";
            $query = $conexion->prepare($sql);
            $query->bind_param('ii', $estatus, $idUsuario);
            $respuesta = $query->execute();
            $query->close();
            return $respuesta;
        }

        public function buscarReportesUsuario($idUsuario) {
            $conexion = parent::conectar();
            $sql = "SELECT * FROM t_reportes WHERE id_usuario = '$idUsuario'";
            $respuesta = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($respuesta) > 0) {
                return 1;
            } else {
                return 0;
            }
        }

        public function buscarAsignacionPersona($idPersona) {
            $conexion = parent::conectar();
            $sql = "SELECT * FROM t_asignacion WHERE id_persona = '$idPersona'";
            $respuesta = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($respuesta) > 0) {
                return 1;
            } else {
                return 0;
            }
        }

        public function eliminarUsuario($datos) {
            $conexion = parent::conectar();
            
            $reportes = $this->buscarReportesUsuario($datos['idUsuario']);
            $asignaciones = $this->buscarAsignacionPersona($datos['idPersona']);

            if ($reportes == 0 && $asignaciones == 0) {
                //eliminamos un usuario
                $sql = "DELETE FROM t_usuarios WHERE id_usuario = ?
                and id_usuario != 1";
                $query = $conexion->prepare($sql);
                $query->bind_param('i', $datos['idUsuario']);
                $respuesta = $query->execute();
                $query->close();
                return $respuesta;
            } else {
                return 0;
            }
        }
    }