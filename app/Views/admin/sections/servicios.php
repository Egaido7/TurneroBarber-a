<!-- 
Esta vista es cargada por 'admin/dashboard.php'
Tiene acceso a la variable $servicios
-->
<div x-data="{ addModal: false, editModal: false, deleteModal: false, servicio: {} }">
    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Gestión de Servicios</h2>
            <!-- Botón Agregar: Abre el modal 'addModal' -->
            <button @click="addModal = true" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                <i data-lucide="plus" class="h-5 w-5 inline mr-2"></i>Agregar Servicio
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Bucle dinámico -->
            <?php if (!empty($servicios)): ?>
                <?php foreach($servicios as $servicio): ?>
                <div class="bg-white rounded-lg border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold mb-3"><?= esc($servicio['nombre']) ?></h3>
                        <div class="space-y-2 mb-4">
                            <p class="text-gray-600"><span class="font-semibold">Precio:</span> $<?= number_format(esc($servicio['precio_total']), 0, ',', '.') ?></p>
                            <p class="text-gray-600"><span class="font-semibold">Seña:</span> $<?= number_format(esc($servicio['monto_seña']), 0, ',', '.') ?></p>
                            <p class="text-gray-500 text-sm truncate"><?= esc($servicio['descripcion']) ?></p>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <!-- Botón Editar: Abre 'editModal' y carga los datos del servicio -->
                        <button 
                            @click="editModal = true; 
                                    servicio = <?= htmlspecialchars(json_encode($servicio), ENT_QUOTES, 'UTF-8') ?>;
                                    $nextTick(() => { 
                                        document.getElementById('editServiceForm').action = '<?= site_url('admin/servicios/editar/') ?>' + servicio.id_servicio;
                                    })"
                            class="flex-1 bg-sky-100 text-sky-700 hover:bg-sky-200 font-medium py-2 rounded-lg transition-colors">Editar</button>
                        
                        <!-- Botón Eliminar: Abre 'deleteModal' y carga los datos del servicio -->
                        <button 
                            @click="deleteModal = true; 
                                    servicio = <?= htmlspecialchars(json_encode($servicio), ENT_QUOTES, 'UTF-8') ?>;"
                            class="flex-1 bg-red-100 text-red-700 hover:bg-red-200 font-medium py-2 rounded-lg transition-colors">Eliminar</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 col-span-full">No hay servicios registrados. Haz clic en "Agregar Servicio" para comenzar.</p>
            <?php endif; ?>

        </div>
    </div>

    <!-- Modal Agregar Servicio -->
    <div x-show="addModal" @keydown.escape.window="addModal = false" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4" @click.away="addModal = false">
            <h3 class="text-2xl font-bold mb-6">Agregar Servicio</h3>
            <form action="<?= site_url('admin/servicios/agregar') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Servicio</label>
                    <input type="text" name="nombre" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio Total</label>
                    <input type="number" name="precio_total" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" placeholder="Ej: 15000" min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Monto Seña</label>
                    <input type="number" name="monto_seña" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" placeholder="Ej: 5000" min="0">
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 rounded-lg transition-colors">Guardar</button>
                    <button type="button" @click="addModal = false" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-lg transition-colors">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Servicio -->
    <div x-show="editModal" @keydown.escape.window="editModal = false" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4" @click.away="editModal = false">
            <h3 class="text-2xl font-bold mb-6">Editar Servicio</h3>
            <form id="editServiceForm" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Servicio</label>
                    <input type="text" name="nombre" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" x-model="servicio.nombre">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" x-model="servicio.descripcion"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio Total</label>
                    <input type="number" name="precio_total" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" x-model="servicio.precio_total" min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Monto Seña</label>
                    <input type="number" name="monto_seña" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" x-model="servicio.monto_seña" min="0">
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 rounded-lg transition-colors">Actualizar</button>
                    <button type="button" @click="editModal = false" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-lg transition-colors">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Eliminar Servicio -->
    <div x-show="deleteModal" @keydown.escape.window="deleteModal = false" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4" @click.away="deleteModal = false">
            <div class="flex items-center space-x-3 mb-4">
                <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800">Confirmar Eliminación</h3>
            </div>
            <p class="text-gray-600 mb-6">
                ¿Estás seguro de que deseas eliminar el servicio <strong x-text="servicio.nombre"></strong>? Esta acción no se puede deshacer.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <button @click="deleteModal = false" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 rounded-lg transition-colors">
                    Cancelar
                </button>
                <a x-bind:href="'<?= site_url('admin/servicios/eliminar/') ?>' + servicio.id_servicio"
                   class="w-full bg-red-600 hover:bg-red-700 text-white text-center font-bold py-2 rounded-lg transition-colors">
                    Sí, eliminar
                </a>
            </div>
        </div>
    </div>

</div>