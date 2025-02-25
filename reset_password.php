<?php
require_once "clases/Conexion.php";

// Verificar si hay un token
if (!isset($_GET['token'])) {
    header('Location: index.html');
    exit;
}

$token = $_GET['token'];
$conexion = new Conexion();
$con = $conexion->conectar();

// Verificar si el token es válido y no ha expirado
$sql = "SELECT id_persona 
        FROM t_persona 
        WHERE token_recuperacion = ? 
        AND token_expira > NOW()";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) == 0) {
    header('Location: index.html?error=token_invalido');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Restablecer Contraseña</h3>
                    </div>
                    <div class="card-body">
                        <form id="formRestablecerPassword">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <div class="form-group">
                                <label for="nuevaContrasena">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="nuevaContrasena" name="nuevaContrasena" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmarContrasena">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirmarContrasena" name="confirmarContrasena" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Cambiar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts necesarios -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="procesos/reset_password.js"></script>
</body>
</html>