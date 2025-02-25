<?php
require_once "../clases/Conexion.php";

class ResetPassword {
    private $conexion;
    
    public function __construct() {
        $conectar = new Conexion();
        $this->conexion = $conectar->conectar();
    }
    
    public function resetear($token, $nuevaContrasena) {
        // Verificar token válido y no expirado
        $sql = "SELECT p.id_persona, u.id_usuario 
                FROM t_persona p 
                INNER JOIN t_usuarios u ON p.id_persona = u.id_persona 
                WHERE p.token_recuperacion = ? 
                AND p.token_expira > NOW()";
                
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($resultado) > 0) {
            $datos = mysqli_fetch_assoc($resultado);
            
            // Actualizar contraseña
            $passwordHash = sha1($nuevaContrasena);
            $sql_update = "UPDATE t_usuarios 
                          SET password = ? 
                          WHERE id_usuario = ?";
                          
            $stmt_update = mysqli_prepare($this->conexion, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "si", $passwordHash, $datos['id_usuario']);
            
            if(mysqli_stmt_execute($stmt_update)) {
                // Limpiar token
                $sql_clear = "UPDATE t_persona 
                             SET token_recuperacion = NULL, 
                                 token_expira = NULL 
                             WHERE id_persona = ?";
                                 
                $stmt_clear = mysqli_prepare($this->conexion, $sql_clear);
                mysqli_stmt_bind_param($stmt_clear, "i", $datos['id_persona']);
                mysqli_stmt_execute($stmt_clear);
                
                return json_encode(['status' => 'success']);
            }
        }
        
        return json_encode([
            'status' => 'error',
            'message' => 'Token inválido o expirado'
        ]);
    }
}

// Procesar la solicitud
if(isset($_POST['token']) && isset($_POST['nuevaContrasena'])) {
    $reset = new ResetPassword();
    echo $reset->resetear($_POST['token'], $_POST['nuevaContrasena']);
}
?>