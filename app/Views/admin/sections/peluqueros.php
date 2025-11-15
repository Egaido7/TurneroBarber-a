<div x-data="{ addModal: false, editModal: false, deleteModal: false, barbero: {} }">

    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Gestión de Peluqueros</h2>
            <button @click="addModal = true" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                <i data-lucide="plus" class="h-5 w-5 inline mr-2"></i>Agregar Peluquero
            </button>
        </div>

        <!-- Tabla de Peluqueros -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Apellido</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
                    <!-- Bucle dinámico -->
                    <?php if (!empty($barberos)): ?>
                        <?php foreach ($barberos as $barbero): ?>
                            <tr>
                                <td class="px-6 py-4"><?= esc($barbero['nombre']) ?></td>
                                <td class="px-6 py-4"><?= esc($barbero['apellido']) ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($barbero['activo']): ?>
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Activo</span>
                                    <?php else: ?>
                                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <!-- 
                                        Botón Editar:
                                        - Abre el 'editModal'.
                                        - Carga los datos del barbero en la variable 'barbero' de Alpine.
                                        - Prepara el 'action' del formulario de edición.
                                    -->
                                    <button 
                                        @click="editModal = true; 
                                                barbero = <?= htmlspecialchars(json_encode($barbero), ENT_QUOTES, 'UTF-8') ?>;
                                                $nextTick(() => { 
                                                    document.getElementById('editForm').action = '<?= site_url('admin/peluqueros/editar/') ?>' + barbero.id_barbero;
                                                })"
                                        class="text-sky-600 hover:text-sky-800 mr-4 font-medium">Editar</button>
                                    
                                    <!-- Botón Eliminar -->
                                    <button 
                                        @click="deleteModal = true; 
                                                barbero = <?= htmlspecialchars(json_encode($barbero), ENT_QUOTES, 'UTF-8') ?>;"
                                        class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay peluqueros registrados.</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Agregar Peluquero -->
    <div x-show="addModal" @keydown.escape.window="addModal = false" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4" @click.away="addModal = false">
            <h3 class="text-2xl font-bold mb-6">Agregar Peluquero</h3>
            <form action="<?= site_url('admin/peluqueros/agregar') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                    <input type="text" name="nombre" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                    <input type="text" name="apellido" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña (para inicio de sesión)</label>
                    <input type="password" name="password" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 rounded-lg transition-colors">Guardar</button>
                    <button type="button" @click="addModal = false" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-lg transition-colors">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Peluquero -->
    <div x-show="editModal" @keydown.escape.window="editModal = false" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4" @click.away="editModal = false">
            <h3 class="text-2xl font-bold mb-6">Editar Peluquero</h3>
            <!-- El 'action' se establece dinámicamente con JS -->
            <form id="editForm" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                    <input type="text" name="nombre" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" x-model="barbero.nombre">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                    <input type="text" name="apellido" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" x-model="barbero.apellido">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña (Dejar en blanco para no cambiar)</label>
                    <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" placeholder="••••••••">
                </div>
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="activo" value="1" class="rounded border-gray-300 text-sky-600 shadow-sm focus:ring-sky-500" :checked="barbero.activo == 1">
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 rounded-lg transition-colors">Actualizar</button>
                    <button type="button" @click="editModal = false" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-lg transition-colors">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Eliminar Peluquero -->
    <div x-show="deleteModal" @keydown.escape.window="deleteModal = false" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4" @click.away="deleteModal = false">
            <div class="flex items-center space-x-3 mb-4">
                <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800">Confirmar Eliminación</h3>
            </div>
            <p class="text-gray-600 mb-6">
                ¿Estás seguro de que deseas eliminar a <strong x-text="barbero.nombre + ' ' + barbero.apellido"></strong>? Esta acción no se puede deshacer.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <button @click="deleteModal = false" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 rounded-lg transition-colors">
                    Cancelar
                </button>
                <a x-bind:href="'<?= site_url('admin/peluqueros/eliminar/') ?>' + barbero.id_barbero"
                   class="w-full bg-red-600 hover:bg-red-700 text-white text-center font-bold py-2 rounded-lg transition-colors">
                    Sí, eliminar
                </a>
            </div>
        </div>
    </div>

</div>