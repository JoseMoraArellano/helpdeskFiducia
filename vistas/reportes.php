<?php 
    session_start();
    include "header.php"; 
    if (isset($_SESSION['usuario']) && ($_SESSION['usuario']['rol'] == 2 || $_SESSION['usuario']['rol'] == 3)) : 
?>

<!-- Page Content -->
<div class="container">
    <div class="card border-0 shadow my-5">
        <div class="card-body p-5">
            <h1 class="fw-light">Gestión de Reportes</h1>
            
            <!-- Pestañas de navegación -->
            <ul class="nav nav-tabs" id="reportesTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="lista-tab" data-toggle="tab" href="#lista" role="tab" aria-controls="lista" aria-selected="true">
                        <i class="fas fa-list"></i> Lista de Reportes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="dashboard-tab" data-toggle="tab" href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="false">
                        <i class="fas fa-chart-bar"></i> Dashboard
                    </a>
                </li>
            </ul>
            
            <!-- Contenido de las pestañas -->
            <div class="tab-content" id="reportesTabsContent">
                <!-- Pestaña de Lista de Reportes -->
                <div class="tab-pane fade show active" id="lista" role="tabpanel" aria-labelledby="lista-tab">
                    <div class="mt-4">
                        <div id="tablaReporteAdminLoad"></div>
                    </div>
                </div>
                
                <!-- Pestaña de Dashboard -->
                <div class="tab-pane fade" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                    <div class="mt-4">
                        <!-- Tarjetas de resumen -->
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Reportes Abiertos</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="reportesAbiertos">0</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nueva tarjeta para Reportes en proceso -->
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    En Proceso</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="reportesProceso">0</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-cogs fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Reportes Cerrados</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="reportesCerrados">0</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Total Reportes</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="reportesTotal">0</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-clipboard fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tiempo medio de resolución -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Tiempo medio de resolución</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div id="chartTiempoMedio" style="width: 100%; height: 120px;"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="text-center">
                                                    <h4 class="mb-0 font-weight-bold text-gray-800"><span id="tiempoMedio">0</span></h4>
                                                    <p class="mb-0">horas laborales promedio</p>
                                                    <small class="text-muted">Basado en <span id="totalResueltos">0</span> tickets resueltos</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Reportes por Estado</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartEstados" style="width: 100%; height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Gráficos -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Reportes por Dispositivo</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartDispositivos" style="width: 100%; height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Tickets Resueltos por Técnico</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartTecnicos" style="width: 100%; height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Reportes por Mes</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartMensual" style="width: 100%; height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    include "reportesAdmin/modalAgregarSolucion.php";
    include "footer.php";
?>
    
<!-- Scripts específicos para reportes -->
<script src="../public/js/reportesAdmin/reportesAdmin.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="../public/js/reportesAdmin/dashboard.js"></script>

<!-- Script para cargar dashboard al cambiar a esa pestaña -->
<script>
    $(document).ready(function() {
        // Cuando se haga clic en la pestaña de dashboard
        $('#dashboard-tab').on('shown.bs.tab', function (e) {
            // Recargar los gráficos
            if (typeof cargarDatos === 'function') {
                cargarDatos();
            }
        });
    });
</script>

<?php else : ?>
    <script type="module">
        import * as modulo from "../public/js/modulo.js";
        window.location.href = `${modulo.BASEURL}/index.html`
    </script>
<?php
    endif;
?>