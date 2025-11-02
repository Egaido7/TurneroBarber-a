<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Gestión de Peluqueros</h2>
        <button onclick="openModal('addBarberModal')" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
            <i data-lucide="plus" class="h-5 w-5 inline mr-2"></i>Agregar Peluquero
        </button>
    </div>

    <!-- Tabla de Peluqueros -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Especialidad</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Teléfono</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4">Carlos Morales</td>
                    <td class="px-6 py-4">Cortes Modernos</td>
                    <td class="px-6 py-4">+54 11 1234-5678</td>
                    <td class="px-6 py-4"><span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Activo</span></td>
                    <td class="px-6 py-4">
                        <button class="text-sky-600 hover:text-sky-800 mr-4">Editar</button>
                        <button class="text-red-600 hover:text-red-800">Eliminar</button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4">Juan García</td>
                    <td class="px-6 py-4">Barbería Tradicional</td>
                    <td class="px-6 py-4">+54 11 8765-4321</td>
                    <td class="px-6 py-4"><span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Activo</span></td>
                    <td class="px-6 py-4">
                        <button class="text-sky-600 hover:text-sky-800 mr-4">Editar</button>
                        <button class="text-red-600 hover:text-red-800">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Agregar Peluquero -->
    <div id="addBarberModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold mb-6">Agregar Peluquero</h3>
            <form action="<?= base_url('admin/peluqueros/agregar') ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                    <input type="text" name="nombre" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Especialidad</label>
                    <input type="text" name="especialidad" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                    <input type="tel" name="telefono" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 rounded-lg transition-colors">Guardar</button>
                    <button type="button" onclick="closeModal('addBarberModal')" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-lg transition-colors">Cancelar</button>
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
