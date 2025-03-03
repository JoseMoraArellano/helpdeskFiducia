// Cargar la biblioteca de Google Charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(cargarDatos);

// Función para cargar todos los datos del dashboard
function cargarDatos() {
    console.log("Iniciando carga de datos del dashboard...");
    
    // Mostrar indicadores de carga en todos los elementos
    $('#reportesAbiertos').html('<small><i class="fas fa-spinner fa-spin"></i></small>');
    $('#reportesCerrados').html('<small><i class="fas fa-spinner fa-spin"></i></small>');
    $('#reportesTotal').html('<small><i class="fas fa-spinner fa-spin"></i></small>');
    $('#reportesProceso').html('<small><i class="fas fa-spinner fa-spin"></i></small>');
    $('#tiempoMedio').html('<small><i class="fas fa-spinner fa-spin"></i></small>');
    $('#totalResueltos').html('<small><i class="fas fa-spinner fa-spin"></i></small>');
    
    // Primera petición AJAX para datos generales de gráficos
    $.ajax({
        url: '../procesos/reportes/obtenerDatosGraficos.php',
        type: 'GET',
        dataType: 'json',
        success: function(datos) {
            console.log("Datos de gráficos recibidos:", datos);
            
            // Actualizar tarjetas de resumen
            $('#reportesAbiertos').text(datos.resumen.abiertos);
            $('#reportesCerrados').text(datos.resumen.cerrados);
            $('#reportesTotal').text(datos.resumen.total);
            $('#reportesProceso').text(datos.resumen.proceso || 0);
            
            // Renderizar gráficos
            dibujarGraficoEstados(datos.resumen);
            dibujarGraficoDispositivos(datos.dispositivos);
            dibujarGraficoMensual(datos.mensual);
            dibujarGraficoTecnicos(datos.tecnicos);
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar datos de gráficos:", error);
            console.error("Respuesta del servidor:", xhr.responseText);
            
            // Mostrar mensaje de error en lugar de spinners
            $('#reportesAbiertos').text('Error');
            $('#reportesCerrados').text('Error');
            $('#reportesTotal').text('Error');
            $('#reportesProceso').text('Error');
            
            // Mostrar mensajes de error en los gráficos
            $('#chartEstados, #chartDispositivos, #chartMensual, #chartTecnicos').each(function() {
                $(this).html('<div class="text-center text-danger p-3"><i class="fas fa-exclamation-triangle"></i> Error al cargar datos</div>');
            });
        }
    });
    
    // Segunda petición AJAX para el tiempo medio de resolución
    $.ajax({
        url: '../procesos/reportes/obtenerTiempoMedio.php',
        type: 'GET',
        dataType: 'json',
        success: function(datos) {
            console.log("Datos de tiempo medio recibidos:", datos);
            
            // Verificar que los datos sean válidos y no sean nulos
            if (datos && 'tiempoMedio' in datos && 'totalResueltos' in datos) {
                $('#tiempoMedio').text(datos.tiempoMedio || '0');
                $('#totalResueltos').text(datos.totalResueltos || '0');
            } else {
                console.warn("Los datos de tiempo medio no tienen el formato esperado:", datos);
                $('#tiempoMedio').text('0');
                $('#totalResueltos').text('0');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar datos de tiempo medio:", error);
            console.error("Respuesta del servidor:", xhr.responseText);
            
            // Mostrar mensaje de error
            $('#tiempoMedio').text('Error');
            $('#totalResueltos').text('Error');
        },
        complete: function() {
            console.log("Carga de datos completada");
        }
    });
}

// Gráfico de Pie para estados de reportes
function dibujarGraficoEstados(datos) {
    var data = google.visualization.arrayToDataTable([
        ['Estado', 'Cantidad'],
        ['Abiertos', parseInt(datos.abiertos) || 0],
        ['En proceso', parseInt(datos.proceso) || 0],
        ['Cerrados', parseInt(datos.cerrados) || 0]
    ]);
    
    var options = {
        title: 'Distribución de reportes por estado',
        colors: ['#e74a3b', '#f6c23e', '#1cc88a'],
        is3D: true,
        backgroundColor: 'transparent'
    };
    
    var chart = new google.visualization.PieChart(document.getElementById('chartEstados'));
    chart.draw(data, options);
}

// Gráfico de Barras para reportes por dispositivo
function dibujarGraficoDispositivos(datos) {
    var dataArray = [['Dispositivo', 'Cantidad']];
    
    datos.forEach(function(item) {
        dataArray.push([item.dispositivo, item.total]);
    });
    
    var data = google.visualization.arrayToDataTable(dataArray);
    
    var options = {
        title: 'Reportes por tipo de dispositivo',
        legend: { position: 'none' },
        bars: 'horizontal',
        backgroundColor: 'transparent',
        colors: ['#36b9cc'],
        axes: {
            x: {
                0: { side: 'top', label: 'Cantidad de reportes'}
            }
        },
                // barra individualmente si lo deseas
                bar: { groupWidth: '90%' },
                //degradado a las barras
                dataOpacity: 0.85
    
    };
    
    var chart = new google.visualization.BarChart(document.getElementById('chartDispositivos'));
    chart.draw(data, options);
}

// Gráfico de Línea para reportes por mes
function dibujarGraficoMensual(datos) {
    var dataArray = [['Mes', 'Reportes']];
    
    datos.forEach(function(item) {
        // Formatear mes para mejor visualización
        var partesFecha = item.mes.split('-');
        var nombreMes = new Date(partesFecha[0], partesFecha[1] - 1, 1).toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
        dataArray.push([nombreMes, item.total]);
    });
    
    var data = google.visualization.arrayToDataTable(dataArray);
    
    var options = {
        title: 'Evolución de reportes por mes',
        curveType: 'function',
        legend: { position: 'bottom' },
        backgroundColor: 'transparent',
        hAxis: {
            title: 'Mes'
        },
        vAxis: {
            title: 'Cantidad de reportes',
            minValue: 0
        }
    };
    
    var chart = new google.visualization.LineChart(document.getElementById('chartMensual'));
    chart.draw(data, options);
}
// Función para cargar datos desde el servidor
function cargarDatos() {
    // Llamada AJAX existente para datos generales
    $.ajax({
        url: '../procesos/reportes/obtenerDatosGraficos.php',
        type: 'GET',
        dataType: 'json',
        success: function(datos) {
            // Código existente para actualizar gráficos
            $('#reportesAbiertos').text(datos.resumen.abiertos);
            $('#reportesCerrados').text(datos.resumen.cerrados);
            $('#reportesTotal').text(datos.resumen.total);
            $('#reportesProceso').text(datos.resumen.proceso);
            
            // Renderizar gráficos existentes
            dibujarGraficoEstados(datos.resumen);
            dibujarGraficoDispositivos(datos.dispositivos);
            dibujarGraficoMensual(datos.mensual);
            dibujarGraficoTecnicos(datos.tecnicos);
        },
        error: function() {
            alert('Error al cargar los datos para los gráficos');
        }
    });
    
    // Nueva llamada AJAX para tiempo medio
    $.ajax({
        url: '../procesos/reportes/obtenerTiempoMedio.php',
        type: 'GET',
        dataType: 'json',
        success: function(datos) {
            $('#tiempoMedio').text(datos.tiempoMedio);
            $('#totalResueltos').text(datos.totalResueltos);
        },

        error: function() {
            console.error('Error al cargar datos de tiempo medio');
        }
    });
}
// NUEVA FUNCIÓN: Gráfico de barras para reportes cerrados por técnico
function dibujarGraficoTecnicos(datos) {
    if (!datos || datos.length === 0) {
        // Si no hay datos, mostrar mensaje
        document.getElementById('chartTecnicos').innerHTML = '<div class="text-center p-4"><i class="fas fa-info-circle text-info"></i> No hay datos disponibles</div>';
        return;
    }
    
    var dataArray = [['Técnico', 'Tickets Resueltos']];
    
    datos.forEach(function(item) {
        dataArray.push([item.tecnico, item.total]);
    });
    
    var data = google.visualization.arrayToDataTable(dataArray);
    
    var options = {
        title: 'Tickets resueltos por técnico',
        legend: { position: 'none' },
        bars: 'horizontal',
        colors: ['#4e73df'],
        backgroundColor: 'transparent',
        hAxis: {
            title: 'Cantidad de tickets',
            format: '0',
        },
        vAxis: {
            title: 'Técnico'
        }
    };
    
    var chart = new google.visualization.BarChart(document.getElementById('chartTecnicos'));
    chart.draw(data, options);
}

// Asegurar que los gráficos se redimensionen con la ventana
$(window).resize(function() {
    cargarDatos();
});