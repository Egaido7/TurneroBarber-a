<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Gestión de Servicios</h2>
        <button onclick="openModal('addServiceModal')" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
            <i data-lucide="plus" class="h-5 w-5 inline mr-2"></i>Agregar Servicio
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        $servicios = [
            ['nombre' => 'Corte Clásico', 'precio' => '$15.000', 'duracion' => '30 min'],
            ['nombre' => 'Corte + Barba', 'precio' => '$25.000', 'duracion' => '50 min'],
            ['nombre' => 'Afeitado Tradicional', 'precio' => '$18.000', 'duracion' => '40 min'],
        ];
        foreach($servicios as $servicio): ?>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold mb-3"><?= $servicio['nombre'] ?></h3>
            <div class="space-y-2 mb-4">
                <p class="text-gray-600"><span class="font-semibold">Precio:</span> <?= $servicio['precio'] ?></p>
                <p class="text-gray-600"><span class="font-semibold">Duración:</span> <?= $servicio['duracion'] ?></p>
            </div>
            <div class="flex gap-2">
                <button class="flex-1 bg-sky-100 text-sky-700 hover:bg-sky-200 font-medium py-2 rounded-lg transition-colors">Editar</button>
                <button class="flex-1 bg-red-100 text-red-700 hover:bg-red-200 font-medium py-2 rounded-lg transition-colors">Eliminar</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal Agregar Servicio -->
    <div id="addServiceModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold mb-6">Agregar Servicio</h3>
            <form action="<?= base_url('admin/servicios/agregar') ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Servicio</label>
                    <input type="text" name="nombre" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio</label>
                    <input type="number" name="precio" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duración (minutos)</label>
                    <input type="number" name="duracion" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 rounded-lg transition-colors">Guardar</button>
                    <button type="button" onclick="closeModal('addServiceModal')" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-lg transition-colors">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
