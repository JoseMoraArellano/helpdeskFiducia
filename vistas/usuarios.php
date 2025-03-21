<?php 
    session_start();
    include "header.php"; 
    if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 2 || $_SESSION['usuario']['rol'] == 3) :
?>

<!-- Page Content -->
    <div class="container">
        <div class="card border-0 shadow my-5">
            <div class="card-body p-5">
            <h1 class="fw-light">Administrar Usuarios</h1>
            <p class="lead">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarUsuarios">
                    Agregar Usuario
                </button>
                <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregarAreas">
                    Gestión de Áreas
                </button>
                <hr>
                <div id="tablaUsuariosLoad"></div>
            </p>
        </div>
    </div>
    

<?php
    include "usuarios/modalAgregar.php";
    include "usuarios/modalActualizar.php";
    include "usuarios/modalResetPassword.php";
    include "usuarios/modalAgregarAreas.php";
    include "footer.php";
?>
    <script src="../public/js/usuarios/usuarios.js"></script>
    <script src="../public/js/usuarios/areas.js"></script>
<?php else : ?>
    <script type="module">
        import * as modulo from "../public/js/modulo.js";
        window.location.href = `${modulo.BASEURL}/index.html`;
    </script>
<?php
    endif;
?>    