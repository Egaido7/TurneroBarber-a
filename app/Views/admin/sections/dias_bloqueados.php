<div class="p-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-8">Días No Laborables / Feriados</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Formulario para bloquear -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm h-fit">
            <h3 class="text-lg font-bold mb-4 text-gray-800">Bloquear un Nuevo Día</h3>
            <form action="<?= site_url('admin/dias/bloquear') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha a Bloquear</label>
                    <input type="date" name="fecha" required min="<?= date('Y-m-d') ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo</label>
                    <input type="text" name="motivo" required placeholder="Ej: Navidad, Feriado Patrio, Cumpleaños..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition-colors">
                    Bloquear Fecha
                </button>
            </form>
        </div>

        <!-- Lista de días bloqueados -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h3 class="font-bold text-gray-700">Próximos Días Bloqueados</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Motivo</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!empty($dias)): ?>
                        <?php foreach ($dias as $dia): ?>
                            <tr>
                                <td class="px-6 py-4 text-gray-800 font-medium">
                                    <?= date('d/m/Y', strtotime($dia['fecha'])) ?>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                                        <?= esc($dia['motivo']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="<?= site_url('admin/dias/desbloquear/' . $dia['id_dia']) ?>" 
                                       class="text-gray-500 hover:text-red-600 transition-colors"
                                       onclick="return confirm('¿Seguro que quieres desbloquear este día?')">
                                        <i data-lucide="trash-2" class="h-5 w-5"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                No hay días bloqueados próximamente.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>