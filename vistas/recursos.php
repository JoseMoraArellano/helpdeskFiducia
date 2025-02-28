<?php 
    session_start();
    include "header.php"; 
    if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 2 || $_SESSION['usuario']['rol'] == 3) : 
?>

<!-- Page Content -->
    <div class="container">
        <div class="card border-0 shadow my-5">
            <div class="card-body p-5">
                <h1 class="fw-light">Gestión de Recursos</h1>
                <p class="lead">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarRecurso">
                        <i class="fas fa-plus-circle"></i> Nuevo Recurso
                    </button>
                    <hr>
                    <div id="tablaRecursosLoad"></div>
                </p>
            </div>
        </div>
    </div>

<?php 
    include "recursos/modalAgregar.php";
    include "recursos/modalActualizar.php";
    include "footer.php";
?>
    <script src="../public/js/recursos/recursos.js"></script>

<?php else : ?>
    <script type="module">
        import * as modulo from "../public/js/modulo.js";
        window.location.href = `${modulo.BASEURL}/index.html`
    </script>
<?php
    endif;
?>