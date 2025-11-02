<div class="p-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-8">Calendario de Turnos</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Calendar -->
        <div class="lg:col-span-2 bg-white rounded-lg p-6 border border-gray-200">
            <div class="mb-6">
                <input type="month" id="monthPicker" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-sky-500">
            </div>
            <div id="calendar" class="space-y-2"></div>
        </div>

        <!-- Turnos del día -->
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <h3 class="text-xl font-bold mb-4">Turnos de Hoy</h3>
            <div class="space-y-3">
                <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                    <p class="font-semibold text-gray-800">Juan Pérez</p>
                    <p class="text-sm text-gray-600">Corte Clásico</p>
                    <p class="text-sm text-sky-600 font-medium">10:00 AM</p>
                </div>
                <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                    <p class="font-semibold text-gray-800">Carlos López</p>
                    <p class="text-sm text-gray-600">Corte + Barba</p>
                    <p class="text-sm text-sky-600 font-medium">14:30 PM</p>
                </div>
                <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                    <p class="font-semibold text-gray-800">Marco Diaz</p>
                    <p class="text-sm text-gray-600">Afeitado Tradicional</p>
                    <p class="text-sm text-sky-600 font-medium">16:00 PM</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple calendar generator
    function generateCalendar() {
        const today = new Date();
        const monthPicker = document.getElementById('monthPicker');
        monthPicker.valueAsDate = today;

        monthPicker.addEventListener('change', function() {
            const calendar = document.getElementById('calendar');
            const date = new Date(this.value);
            
            calendar.innerHTML = '';
            const year = date.getFullYear();
            const month = date.getMonth();
            
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            
            for (let i = 1; i <= lastDay.getDate(); i++) {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'p-3 bg-gray-50 border border-gray-200 rounded cursor-pointer hover:bg-sky-100 transition-colors';
                dayDiv.textContent = i;
                dayDiv.onclick = function() {
                    alert('Turnos para el día: ' + i + '/' + (month + 1) + '/' + year);
                };
                calendar.appendChild(dayDiv);
            }
        });

        generateCalendar.apply(monthPicker);
    }

    generateCalendar();
</script>
