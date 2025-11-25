<!-- IMPORTAR APEXCHARTS (Librería de Gráficos) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="p-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h2 class="text-3xl font-bold text-gray-800">Estadísticas Mensuales</h2>
        
        <!-- Selector de Mes/Año -->
        <div class="flex items-center gap-2">
            <label for="monthPicker" class="text-sm font-medium text-gray-700">Seleccionar Mes:</label>
            <input type="month" id="monthPicker" 
                   value="<?= esc($mesSeleccionado) // YYYY-MM ?>" 
                   class="p-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-sky-500">
        </div>
    </div>

    <!-- KPI Cards (Sin cambios, siguen siendo útiles) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Turnos del Mes</p>
                    <p class="text-3xl font-bold text-gray-800"><?= esc($stats['total_turnos']) ?></p>
                </div>
                <i data-lucide="calendar" class="h-8 w-8 text-sky-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Ingresos del Mes</p>
                    <p class="text-3xl font-bold text-green-600">$<?= number_format(esc($stats['total_ingresos']), 0, ',', '.') ?></p>
                </div>
                <i data-lucide="dollar-sign" class="h-8 w-8 text-green-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Clientes Nuevos</p>
                    <p class="text-3xl font-bold text-gray-800"><?= esc($clientesNuevos) ?></p>
                </div>
                <i data-lucide="users" class="h-8 w-8 text-sky-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Ticket Promedio</p>
                    <p class="text-3xl font-bold text-gray-800">
                        <?php
                            if ($stats['total_turnos'] > 0) {
                                $ticketPromedio = $stats['total_ingresos'] / $stats['total_turnos'];
                                echo '$' . number_format($ticketPromedio, 0, ',', '.');
                            } else {
                                echo '$0';
                            }
                        ?>
                    </p>
                </div>
                <i data-lucide="trending-up" class="h-8 w-8 text-sky-500"></i>
            </div>
        </div>
    </div>

    <!-- GRÁFICO DE TENDENCIA DIARIA (NUEVO) -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8 shadow-sm">
        <h3 class="text-lg font-bold mb-4 text-gray-800">Evolución de Ingresos Diarios</h3>
        <!-- Contenedor para el gráfico de área -->
        <div id="chart-ingresos-diarios" style="min-height: 300px;"></div>
    </div>

    <!-- GRÁFICOS SECUNDARIOS (Donut y Barras) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Servicios Más Populares (Donut Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold mb-4 text-gray-800">Servicios Más Solicitados</h3>
            <div id="chart-servicios" class="flex justify-center"></div>
        </div>

        <!-- Barberos con Más Turnos (Bar Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold mb-4 text-gray-800">Desempeño de Barberos</h3>
            <div id="chart-barberos"></div>
        </div>
    </div>
</div>

<!-- CONFIGURACIÓN DE LOS GRÁFICOS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. DATOS DESDE PHP ---
        const statsDiarias = <?= json_encode($statsDiarias) ?>;
        const serviciosPop = <?= json_encode($serviciosPopulares) ?>;
        const barberosPop  = <?= json_encode($barberosPopulares) ?>;

        // --- 2. PREPARAR DATOS PARA GRÁFICO DIARIO ---
        // Necesitamos arrays simples para ApexCharts
        const diasMes = [];
        const ingresosDia = [];
        const turnosDia = [];

        // Llenamos con los datos reales. Nota: Si un día no tiene ventas, no aparecerá en el array de PHP.
        // Para un gráfico perfecto, deberíamos rellenar los días vacíos, pero para simplificar usaremos lo que hay.
        statsDiarias.forEach(item => {
            diasMes.push('Día ' + item.dia);
            ingresosDia.push(item.total_ingresos);
            turnosDia.push(item.total_turnos);
        });


        // --- 3. GRÁFICO DE ÁREA (Ingresos) ---
        if (statsDiarias.length > 0) {
            const optionsArea = {
                series: [{
                    name: 'Ingresos ($)',
                    data: ingresosDia
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: { show: false }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth' },
                xaxis: {
                    categories: diasMes,
                    tooltip: { enabled: false }
                },
                colors: ['#10b981'], // Verde Esmeralda
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "$ " + new Intl.NumberFormat('es-AR').format(val);
                        }
                    }
                }
            };
            const chartArea = new ApexCharts(document.querySelector("#chart-ingresos-diarios"), optionsArea);
            chartArea.render();
        } else {
            document.querySelector("#chart-ingresos-diarios").innerHTML = '<p class="text-center text-gray-500 py-10">No hay movimientos registrados este mes.</p>';
        }


        // --- 4. GRÁFICO DE DONA (Servicios) ---
        const nombresServicios = serviciosPop.map(s => s.nombre);
        const totalesServicios = serviciosPop.map(s => parseInt(s.total));

        if (serviciosPop.length > 0) {
            const optionsDonut = {
                series: totalesServicios,
                labels: nombresServicios,
                chart: {
                    type: 'donut',
                    height: 320
                },
                colors: ['#0ea5e9', '#3b82f6', '#6366f1', '#8b5cf6', '#d946ef'], // Paleta de azules/violetas
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val.toFixed(1) + "%"
                    }
                }
            };
            const chartDonut = new ApexCharts(document.querySelector("#chart-servicios"), optionsDonut);
            chartDonut.render();
        } else {
            document.querySelector("#chart-servicios").innerHTML = '<p class="text-center text-gray-500">Sin datos.</p>';
        }


        // --- 5. GRÁFICO DE BARRAS (Barberos) ---
        const nombresBarberos = barberosPop.map(b => b.nombre + ' ' + b.apellido);
        const totalesBarberos = barberosPop.map(b => parseInt(b.total));

        if (barberosPop.length > 0) {
            const optionsBar = {
                series: [{
                    name: 'Turnos Realizados',
                    data: totalesBarberos
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true, // Barras horizontales se leen mejor los nombres
                    }
                },
                dataLabels: { enabled: true },
                xaxis: {
                    categories: nombresBarberos,
                },
                colors: ['#f59e0b'], // Color Ámbar
            };
            const chartBar = new ApexCharts(document.querySelector("#chart-barberos"), optionsBar);
            chartBar.render();
        } else {
            document.querySelector("#chart-barberos").innerHTML = '<p class="text-center text-gray-500">Sin datos.</p>';
        }

        // Script del selector de fecha
        document.getElementById('monthPicker').addEventListener('change', function() {
            const valor = this.value;
            if (valor) {
                const [anio, mes] = valor.split('-');
                window.location.href = `<?= site_url('admin?section=estadisticas') ?>&mes=${mes}&anio=${anio}`;
            }
        });
    });
</script>