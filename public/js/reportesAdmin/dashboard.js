// Cargar la biblioteca de Google Charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(cargarDatos);

// Función para cargar datos desde el servidor
function cargarDatos() {
    $.ajax({
        url: '../procesos/reportes/obtenerDatosGraficos.php',
        type: 'GET',
        dataType: 'json',
        success: function(datos) {
            // Actualizar tarjetas de resumen
            $('#reportesAbiertos').text(datos.resumen.abiertos);
            $('#reportesCerrados').text(datos.resumen.cerrados);
            $('#reportesTotal').text(datos.resumen.total);
            
            // Renderizar gráficos
            dibujarGraficoEstados(datos.resumen);
            dibujarGraficoDispositivos(datos.dispositivos);
            dibujarGraficoMensual(datos.mensual);
            dibujarGraficoTecnicos(datos.tecnicos);
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar datos:", error);
            alert('Error al cargar los datos para los gráficos');
        }
    });
}

// Gráfico de Pie para estados de reportes
function dibujarGraficoEstados(datos) {
    var data = google.visualization.arrayToDataTable([
        ['Estado', 'Cantidad'],
        ['Abiertos', datos.abiertos],
        ['Cerrados', datos.cerrados]
    ]);
    
    var options = {
        title: 'Distribución de reportes por estado',
        colors: ['#e74a3b', '#1cc88a'],
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
        axes: {
            x: {
                0: { side: 'top', label: 'Cantidad de reportes'}
            }
        },
        backgroundColor: 'transparent'
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
            title: 'Cantidad de tickets'
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