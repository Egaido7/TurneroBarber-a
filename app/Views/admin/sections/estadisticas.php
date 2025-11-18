<div class="p-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h2 class="text-3xl font-bold text-gray-800">Estadísticas</h2>
        
        <!-- Selector de Mes/Año -->
        <div class="flex items-center gap-2">
            <label for="monthPicker" class="text-sm font-medium text-gray-700">Seleccionar Mes:</label>
            <input type="month" id="monthPicker" 
                   value="<?= esc($mesSeleccionado) // YYYY-MM ?>" 
                   class="p-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-sky-500">
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Turnos del Mes</p>
                    <p class="text-3xl font-bold text-gray-800"><?= esc($stats['total_turnos']) ?></p>
                </div>
                <i data-lucide="calendar" class="h-8 w-8 text-sky-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Ingresos del Mes</p>
                    <p class="text-3xl font-bold text-gray-800">$<?= number_format(esc($stats['total_ingresos']), 0, ',', '.') ?></p>
                </div>
                <i data-lucide="dollar-sign" class="h-8 w-8 text-green-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Clientes Nuevos (este mes)</p>
                    <p class="text-3xl font-bold text-gray-800"><?= esc($clientesNuevos) ?></p>
                </div>
                <i data-lucide="users" class="h-8 w-8 text-sky-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Ticket Promedio</p>
                    <!-- Calculamos el ticket promedio: Ingresos / Turnos -->
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

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Servicios Más Populares -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold mb-4">Servicios Más Solicitados</h3>
            <div class="space-y-4">
                
                <?php if (!empty($serviciosPopulares)): ?>
                    <?php 
                        // El total de turnos de servicios populares para calcular %
                        $totalServiciosPopulares = array_sum(array_column($serviciosPopulares, 'total'));
                    ?>
                    <?php foreach ($serviciosPopulares as $servicio): ?>
                        <?php 
                            $porcentaje = ($totalServiciosPopulares > 0) ? ($servicio['total'] / $totalServiciosPopulares) * 100 : 0;
                        ?>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium"><?= esc($servicio['nombre']) ?></span>
                                <span class="text-sm font-bold"><?= number_format($porcentaje, 0) ?>% (<?= esc($servicio['total']) ?>)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-sky-500 h-2 rounded-full" style="width: <?= $porcentaje ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500">No hay datos de servicios para este mes.</p>
                <?php endif; ?>

            </div>
        </div>

        <!-- Peluqueros con Más Turnos -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold mb-4">Barberos con Más Turnos</h3>
            <div class="space-y-4">
                
                <?php if (!empty($barberosPopulares)): ?>
                    <?php $rank = 1; ?>
                    <?php foreach ($barberosPopulares as $barbero): ?>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium"><?= esc($barbero['nombre']) ?> <?= esc($barbero['apellido']) ?></p>
                                <p class="text-sm text-gray-600"><?= esc($barbero['total']) ?> turnos</p>
                            </div>
                            <span class="bg-sky-100 text-sky-800 px-3 py-1 rounded-full text-sm font-semibold"><?= $rank++ ?>º</span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500">No hay datos de barberos para este mes.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- Script para el selector de mes -->
<script>
    document.getElementById('monthPicker').addEventListener('change', function() {
        // 'this.value' nos da 'YYYY-MM'
        const valor = this.value;
        if (valor) {
            const [anio, mes] = valor.split('-');
            // Recargamos la página con los nuevos parámetros 'mes' y 'anio'
            window.location.href = `<?= site_url('admin?section=estadisticas') ?>&mes=${mes}&anio=${anio}`;
        }
    });
</script>