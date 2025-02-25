<?php
require_once "../clases/Conexion.php";

class RecuperarPassword {
    private $conexion;
    
    public function __construct() {
        $conectar = new Conexion();
        $this->conexion = $conectar->conectar();
    }
    
    public function recuperar($correo) {
        // Verificar si el correo existe
        $sql = "SELECT p.id_persona, p.correo 
                FROM t_persona p 
                INNER JOIN t_usuarios u ON p.id_persona = u.id_persona 
                WHERE p.correo = ?";
                
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($resultado) > 0) {
            $datos = mysqli_fetch_assoc($resultado);
            
            // Generar token único
            $token = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Actualizar token en la base de datos
            $sql_update = "UPDATE t_persona            
                          SET token_recuperacion = ?, 
                              token_expira = ? 
                          WHERE id_persona = ?";
                          
            $stmt_update = mysqli_prepare($this->conexion, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "ssi", $token, $expira, $datos['id_persona']);
            
            if(mysqli_stmt_execute($stmt_update)) {
                // Enviar correo
                $to = $correo;
                $subject = "Recuperación de contraseña - HelpDesk";
                $message = "
                <html>
                <head>
                    <title>Recuperar contraseña</title>
                </head>
                <body>
                    <h2>Recuperación de contraseña</h2>
                    <p>Has solicitado recuperar tu contraseña. Haz clic en el siguiente enlace para restablecerla:</p>
                    <p><a href='http://helpdesk.fianzasfiducia.com/reset_password.php?token=$token'>Restablecer contraseña</a></p>
                    <p>Este enlace expirará en 1 hora.</p>
                    <p>Si no solicitaste este cambio, ignora este correo.</p>
                </body>
                </html>
                ";
                
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: HelpDesk <jmora@fianzasfiducia.com>' . "\r\n";
                
                if(mail($to, $subject, $message, $headers)) {
                    return json_encode(['status' => 'success']);
                } else {
                    return json_encode([
                        'status' => 'error',
                        'message' => 'Error al enviar el correo'
                    ]);
                }
            }
        }
        
        return json_encode([
            'status' => 'error',
            'message' => 'Correo no encontrado en la base de datos'
        ]);
    }
}

// Procesar la solicitud
if(isset($_POST['correo'])) {
    $recuperar = new RecuperarPassword();
    echo $recuperar->recuperar($_POST['correo']);
}
?>