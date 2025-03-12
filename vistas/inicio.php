<?php 
    session_start();
    include "header.php";
    if (isset($_SESSION['usuario'])) :
    
    $idUsuario = @$_SESSION['usuario']['id'];
    $rolUsuario = $_SESSION['usuario']['rol'];
?>
    <!-- Page Content -->
    <div class="container">
        <div class="card border-0 shadow my-5">
            <div class="card-body p-5">
                <h1 class="fw-light">Bienvenido <?php echo $_SESSION['usuario']['nombre']; ?></h1>
                
                <?php if ($rolUsuario == 2 || $rolUsuario == 3) : ?>
                <!-- VISTA PARA ROLES 2 Y 3 (ADMINISTRADORES Y TÉCNICOS) -->
                <p class="lead">Dashboard de Reportes</p>
                
                <!-- Inicio del resumen de datos -->
                <div class="row mt-4">
                    <!-- Reportes Abiertos -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Reportes Abiertos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="reportesAbiertos">0</div>
                                        <div class="text-xs text-danger mt-2" id="reporteAntiguoAbierto">
                                            Reporte más antiguo: 0 días
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-folder-open fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reportes En Proceso -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            En Proceso</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="reportesEnProceso">0</div>
                                        <div class="text-xs text-danger mt-2" id="reporteAntiguoEnProceso">
                                            Reporte más antiguo: 0 días
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reportes Cerrados -->
                    <div class="col-xl-3 col-md-6 mb-4">
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

                    <!-- Total de Reportes -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Reportes</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="reportesTotal">0</div>
                                        <div class="row no-gutters align-items-center mt-2">
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-success" role="progressbar" id="barraCerrados"
                                                        style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                    <div class="progress-bar bg-primary" role="progressbar" id="barraEnProceso"
                                                        style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Segunda fila - Información de usuarios -->
                <div class="row mt-2">
                    <!-- Técnicos Activos -->
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Técnicos Activos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="tecnicosActivos">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users-cog fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Clientes Activos -->
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Clientes Activos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="clientesActivos">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de resumen -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Resumen de Reportes</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="graficoReportes"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-warning"></i> Abiertos
                                    </span>
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-primary"></i> En Proceso
                                    </span>
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-success"></i> Cerrados
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin del resumen de datos -->
                
                <?php elseif ($rolUsuario == 1) : ?>
                <!-- VISTA PARA ROL 1 (CLIENTES) -->
                <p class="lead">Mis Reportes</p>
                
                <!-- Resumen de reportes del cliente -->
                <div class="row mt-4">
                    <!-- Reportes Abiertos -->
                    <div class="col-xl-4 col-md-4 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Reportes Abiertos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="misReportesAbiertos">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-folder-open fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reportes En Proceso -->
                    <div class="col-xl-4 col-md-4 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            En Proceso</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="misReportesEnProceso">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reportes Cerrados -->
                    <div class="col-xl-4 col-md-4 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Reportes Cerrados</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="misReportesCerrados">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gráfica de mis reportes -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Mis Reportes</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="graficoMisReportes"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-warning"></i> Abiertos
                                    </span>
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-primary"></i> En Proceso
                                    </span>
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-success"></i> Cerrados
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Fin del contenido condicional para diferentes roles -->
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>
    <script src="../public/js/inicio/personales.js"></script>
    <!-- Agregar Chart.js para las gráficas -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let idUsuario = '<?= $idUsuario; ?>';
        let rolUsuario = '<?= $rolUsuario; ?>';
        
        // Cargar datos personales
        datosPersonalesInicio(idUsuario);
        
        // Cargar resumen de datos según el rol del usuario
        if (rolUsuario == '2' || rolUsuario == '3') {
            cargarResumenAdminTecnico();
        } else if (rolUsuario == '1') {
            cargarResumenCliente(idUsuario);
        }
        
        // Función para cargar el resumen para administradores y técnicos
        function cargarResumenAdminTecnico() {
            fetch('../vistas/obtenerResumen.php')
                .then(response => response.json())
                .then(data => {
                    // Actualizar los contadores de reportes
                    document.getElementById('reportesAbiertos').textContent = data.reportes.abiertos;
                    document.getElementById('reportesEnProceso').textContent = data.reportes.enProceso;
                    document.getElementById('reportesCerrados').textContent = data.reportes.cerrados;
                    document.getElementById('reportesTotal').textContent = data.reportes.total;
                    
                    // Actualizar antigüedad de reportes
                    document.getElementById('reporteAntiguoAbierto').textContent = 
                        `Reporte más antiguo: ${data.antiguedad.abiertosDias} días`;
                    document.getElementById('reporteAntiguoEnProceso').textContent = 
                        `Reporte más antiguo: ${data.antiguedad.enProcesoDias} días`;
                    
                    // Actualizar contadores de usuarios
                    document.getElementById('tecnicosActivos').textContent = data.usuarios.tecnicos;
                    document.getElementById('clientesActivos').textContent = data.usuarios.clientes;
                    
                    // Actualizar barra de progreso
                    const total = data.reportes.total;
                    if (total > 0) {
                        const porcentajeCerrados = (data.reportes.cerrados / total) * 100;
                        const porcentajeEnProceso = (data.reportes.enProceso / total) * 100;
                        
                        document.getElementById('barraCerrados').style.width = porcentajeCerrados + '%';
                        document.getElementById('barraCerrados').setAttribute('aria-valuenow', porcentajeCerrados);
                        
                        document.getElementById('barraEnProceso').style.width = porcentajeEnProceso + '%';
                        document.getElementById('barraEnProceso').setAttribute('aria-valuenow', porcentajeEnProceso);
                    }
                    
                    // Inicializar gráfico
                    inicializarGraficoAdmin(data.reportes);
                })
                .catch(error => {
                    console.error('Error al cargar el resumen:', error);
                });
        }
        
        // Función para cargar el resumen para clientes
        function cargarResumenCliente(idUsuario) {
            fetch(`../vistas/obtenerResumenCliente.php?idUsuario=${idUsuario}`)
                .then(response => response.json())
                .then(data => {
                    // Actualizar los contadores de reportes del cliente
                    document.getElementById('misReportesAbiertos').textContent = data.abiertos;
                    document.getElementById('misReportesEnProceso').textContent = data.enProceso;
                    document.getElementById('misReportesCerrados').textContent = data.cerrados;
                    
                    // Inicializar gráfico
                    inicializarGraficoCliente(data);
                })
                .catch(error => {
                    console.error('Error al cargar el resumen del cliente:', error);
                });
        }
        
        // Función para inicializar el gráfico de admin/técnico
        function inicializarGraficoAdmin(datosReportes) {
            const ctx = document.getElementById('graficoReportes').getContext('2d');
            
            // Crear gráfico circular
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Abiertos', 'En Proceso', 'Cerrados'],
                    datasets: [{
                        data: [
                            datosReportes.abiertos, 
                            datosReportes.enProceso, 
                            datosReportes.cerrados
                        ],
                        backgroundColor: [
                            '#f6c23e', // Amarillo para Abiertos
                            '#4e73df', // Azul para En Proceso
                            '#1cc88a'  // Verde para Cerrados
                        ],
                        hoverBackgroundColor: [
                            '#e0b038',
                            '#4565c7',
                            '#19b77e'
                        ],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });
        }
        
        // Función para inicializar el gráfico de cliente
        function inicializarGraficoCliente(datosReportes) {
            const ctx = document.getElementById('graficoMisReportes').getContext('2d');
            
            // Crear gráfico circular
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Abiertos', 'En Proceso', 'Cerrados'],
                    datasets: [{
                        data: [
                            datosReportes.abiertos, 
                            datosReportes.enProceso, 
                            datosReportes.cerrados
                        ],
                        backgroundColor: [
                            '#f6c23e', // Amarillo para Abiertos
                            '#4e73df', // Azul para En Proceso
                            '#1cc88a'  // Verde para Cerrados
                        ],
                        hoverBackgroundColor: [
                            '#e0b038',
                            '#4565c7',
                            '#19b77e'
                        ],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });
        }
    </script>

<?php else : ?>
    <script type="module">
        import * as modulo from "../public/js/modulo.js";
        window.location.href = `${modulo.BASEURL}/index.html`
    </script>
<?php
    endif;
?>