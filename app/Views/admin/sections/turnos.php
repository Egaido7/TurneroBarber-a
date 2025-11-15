<div class_="" x-data="{ modalOpen: false, turnoId: null, turnoInfo: '' }">

    <div class="p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Calendario de Turnos</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calendar -->
            <div class="lg:col-span-2 bg-white rounded-lg p-6 border border-gray-200">
                <div class="mb-6">
                    <!-- El valor del 'month picker' se establece con la fecha que viene del controlador -->
                    <input type="month" id="monthPicker" value="<?= date('Y-m', strtotime($fechaSeleccionada)) ?>" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-sky-500">
                </div>
                <div id="calendar" class="grid grid-cols-7 gap-2">
                    <!-- El JS de abajo llenará esto -->
                </div>
            </div>

            <!-- Turnos del día -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <!-- El título ahora es dinámico y muestra la fecha seleccionada -->
                <h3 class="text-xl font-bold mb-4">
                    Turnos del día: <span class="text-sky-600"><?= date('d/m/Y', strtotime($fechaSeleccionada)) ?></span>
                </h3>
                
                <!-- 
                    REEMPLAZAMOS EL CONTENIDO ESTÁTICO
                    con un bucle PHP que usa la variable $turnos
                -->
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    <?php if (!empty($turnos)): ?>
                        <?php foreach ($turnos as $turno): ?>
                            <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="font-semibold text-gray-800"><?= esc($turno['cliente_nombre']) ?></p>
                                    <p class="text-sm text-sky-600 font-medium"><?= esc(substr($turno['hora_turno'], 0, 5)) ?></p>
                                </div>
                                <p class="text-sm text-gray-600"><?= esc($turno['servicio_nombre']) ?></p>
                                <p class="text-xs text-gray-500">Barbero: <?= esc($turno['barbero_nombre']) ?></p>
                                
                                <!-- Botón Cancelar - Llama al modal de Alpine.js -->
                                <button 
                                    @click="modalOpen = true; 
                                            turnoId = <?= $turno['id_turno'] ?>; 
                                            turnoInfo = '<?= esc($turno['cliente_nombre']) ?> a las <?= esc(substr($turno['hora_turno'], 0, 5)) ?>'"
                                    class="mt-2 text-xs text-red-600 hover:text-red-800 font-medium"
                                >
                                    Cancelar Turno
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Mensaje si no hay turnos para ese día -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500">No hay turnos agendados para este día.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 
      MODAL DE CONFIRMACIÓN (Usando Alpine.js)
    -->
    <div x-show="modalOpen" @keydown.escape.window="modalOpen = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-sm w-full" @click.away="modalOpen = false">
            
            <div class="flex items-center space-x-3 mb-4">
                <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800">Confirmar Cancelación</h3>
            </div>
            
            <p class="text-gray-600 mb-6">
                ¿Estás seguro de que deseas cancelar el turno de 
                <strong x-text="turnoInfo"></strong>? Esta acción no se puede deshacer.
            </p>

            <div class="grid grid-cols-2 gap-4">
                <button @click="modalOpen = false" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg transition-colors">
                    No, volver
                </button>
                <!-- 
                    Este enlace apunta a la nueva ruta /admin/turnos/cancelar/[ID]
                    Usamos 'x-bind:href' para construir la URL dinámicamente con el turnoId
                -->
                <a x-bind:href="'<?= site_url('admin/turnos/cancelar/') ?>' + turnoId"
                   class="w-full bg-red-600 hover:bg-red-700 text-white text-center font-bold py-3 px-4 rounded-lg transition-colors">
                    Sí, cancelar
                </a>
            </div>
        </div>
    </div>
    <!-- Fin del Modal -->
</div>


<!-- SCRIPT DEL CALENDARIO (Actualizado) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthPicker = document.getElementById('monthPicker');
        const calendar = document.getElementById('calendar');
        
        // Obtenemos la fecha seleccionada que vino desde el controlador
        const fechaSeleccionada = new Date('<?= $fechaSeleccionada ?>T12:00:00'); // Usamos T12:00 para evitar problemas de zona horaria
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        function renderCalendar(date) {
            calendar.innerHTML = '';
            const year = date.getFullYear();
            const month = date.getMonth();
            
            const firstDayOfMonth = new Date(year, month, 1).getDay(); // 0 = Domingo, 1 = Lunes
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Ajuste para que la semana empiece en Lunes (si getDay() es 0 (Domingo), lo movemos a 6)
            const startDay = (firstDayOfMonth === 0) ? 6 : firstDayOfMonth - 1;

            // Nombres de los días de la semana
            const daysOfWeek = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'];
            daysOfWeek.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'text-center text-xs font-semibold text-gray-500';
                dayHeader.textContent = day;
                calendar.appendChild(dayHeader);
            });

            // Rellenar espacios vacíos al inicio del mes
            for (let i = 0; i < startDay; i++) {
                calendar.appendChild(document.createElement('div'));
            }

            // Generar los días del mes
            for (let i = 1; i <= daysInMonth; i++) {
                const dayDiv = document.createElement('div');
                const currentDate = new Date(year, month, i);
                
                let classes = 'p-3 text-center bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-sky-100 transition-colors';
                
                // Marcar el día de hoy
                if (currentDate.getTime() === hoy.getTime()) {
                    classes += ' bg-blue-100 border-blue-300 font-bold';
                }
                
                // Marcar el día seleccionado
                if (i === fechaSeleccionada.getDate() && month === fechaSeleccionada.getMonth() && year === fechaSeleccionada.getFullYear()) {
                    classes = 'p-3 text-center border rounded-lg cursor-pointer bg-sky-500 text-white font-bold border-sky-600';
                }

                dayDiv.className = classes;
                dayDiv.textContent = i;
                
                // --- ACCIÓN ONCLICK (IMPORTANTE) ---
                // Al hacer clic, recargamos la página con la nueva fecha en la URL
                dayDiv.onclick = function() {
                    // Formateamos la fecha a YYYY-MM-DD
                    const fechaISO = new Date(year, month, i).toISOString().split('T')[0];
                    // Redirigimos a la página de admin con el parámetro 'fecha'
                    window.location.href = '<?= site_url('admin?section=turnos&fecha=') ?>' + fechaISO;
                };
                
                calendar.appendChild(dayDiv);
            }
        }

        // Event listener para el 'month picker'
        monthPicker.addEventListener('change', function() {
            // Usamos el valor del picker, T12:00 para evitar zona horaria
            const newDate = new Date(this.value + 'T12:00:00'); 
            renderCalendar(newDate);
        });

        // Renderizar el calendario por primera vez con la fecha seleccionada
        renderCalendar(fechaSeleccionada);
    });
</script>