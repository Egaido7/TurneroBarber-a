<div class="p-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-8">Gestión de Precios y Señas</h2>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Servicio</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Precio Actual</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Monto Seña</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nuevo Precio</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">

                <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $servicio): ?>
                
                        <form action="<?= site_url('admin/precios/actualizar/' . $servicio['id_servicio']) ?>" method="POST">
                            <?= csrf_field() ?>
                            <tr>
                                <td class="px-6 py-4 font-medium"><?= esc($servicio['nombre']) ?></td>
                                <td class="px-6 py-4">$<?= number_format(esc($servicio['precio_total']), 0, ',', '.') ?></td>
                                <td class="px-6 py-4">$<?= number_format(esc($servicio['monto_seña']), 0, ',', '.') ?></td>
                                <td class="px-6 py-4">
                                    <input 
                                        type="number" 
                                        name="nuevo_precio" 
                                        placeholder="<?= esc($servicio['precio_total']) ?>" 
                                        class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-sky-500"
                                        min="0"
                                        required
                                    >
                                </td>
                                <td class="px-6 py-4">
                                    <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded transition-colors">Actualizar</button>
                                </td>
                            </tr>
                        </form>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay servicios registrados.</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
    
</div>