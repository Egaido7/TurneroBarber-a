<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reprogramar Turno - LeanBarber</title>
    <link rel="stylesheet" href="<?= base_url('src/output.css') ?>">
    <link rel="icon" href="<?= base_url('src/Imagenes/leanbarber.png') ?>" type="image/png">
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4">

    <?php
        // --- LÓGICA PARA DETECTAR SI ES USUARIO O ADMIN ---
        // Si existe la variable $token, es un usuario público. Si no, es admin.
        $isUser = isset($token) && !empty($token);
        
        if ($isUser) {
            // Rutas para el Usuario
            $urlHorarios = site_url('turnos/cambiar/horarios/' . $token);
            $urlGuardar  = site_url('turnos/cambiar/guardar/' . $token);
            $urlCancelar = site_url('/'); // Vuelve al inicio
        } else {
            // Rutas para el Admin
            $urlHorarios = site_url('admin/turnos/horarios/' . $turno['id_turno']);
            $urlGuardar  = site_url('admin/turnos/reprogramar/' . $turno['id_turno']);
            $urlCancelar = site_url('admin?section=turnos'); // Vuelve al panel
        }
    ?>

    <div class="max-w-4xl w-full space-y-8">
        <div class="text-center">
            <img src="<?= base_url('src/imagenes/logoinicial.png') ?>" alt="Logo" class="mx-auto h-24 w-auto">
            <h2 class="mt-6 text-3xl font-bold text-gray-800">Reprogramar Turno</h2>
            <p class="mt-2 text-lg text-gray-600">Selecciona la nueva fecha y hora para el turno.</p>
        </div>

        <!-- Mensajes de Error/Éxito -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">¡Atención!</strong>
                <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Detalles del Turno Actual -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Turno Actual</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-700">
                <div>
                    <span class="block text-sm font-medium text-gray-500">Cliente</span>
                    <span class="text-lg font-semibold"><?= esc($turno['cliente_nombre'] . ' ' . $turno['cliente_apellido']) ?></span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-gray-500">Servicio</span>
                    <span class="text-lg font-semibold"><?= esc($turno['servicio_nombre']) ?></span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-gray-500">Fecha/Hora Actual</span>
                    <span class="text-lg font-semibold text-red-600"><?= esc(date('d/m/Y', strtotime($turno['fecha']))) ?> a las <?= esc(substr($turno['hora_turno'], 0, 5)) ?></span>
                </div>
            </div>
        </div>

        <!-- Formulario de Reprogramación -->
        <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200">
            
            <h3 class="text-xl font-bold mb-6">1. Elige la nueva fecha</h3>
            
            <!-- Formulario 1: Ver Horarios (Url Dinámica) -->
            <form action="<?= $urlHorarios ?>" method="post" class="space-y-6 mb-6">
                <?= csrf_field() ?>
                <!-- Si es usuario, necesitamos pasar el token en inputs ocultos o en la URL (ya está en la URL) -->
                
                <div>
                    <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Nueva Fecha</label>
                    <input type="date" id="fecha" name="fecha" value="<?= esc($fechaSeleccionada) ?>" required min="<?= date('Y-m-d') ?>" class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <button type="submit" class="w-full bg-sky-500 hover:bg-sky-600 text-white font-bold py-3 px-6 rounded-lg text-lg transition-colors">
                    Ver Horarios Disponibles
                </button>
            </form>

            <hr class="my-8">

            <!-- Formulario 2: Reprogramar Turno (Url Dinámica) -->
            <h3 class="text-xl font-bold mb-6">2. Confirma el nuevo horario</h3>
            <form action="<?= $urlGuardar ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                
                <!-- Campos ocultos para mantener los datos -->
                <input type="hidden" name="fecha" value="<?= esc($fechaSeleccionada) ?>">
                <input type="hidden" name="id_servicio" value="<?= esc($turno['id_servicio_fk']) ?>">
                <input type="hidden" name="id_barbero" value="<?= esc($turno['id_barbero_fk']) ?>">

                <!-- Input oculto para capturar el texto del horario seleccionado (para el mensaje de éxito) -->
                <input type="hidden" name="horario_texto" id="horario_texto" value="">

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nuevos Horarios Disponibles (para el <?= esc(date('d/m/Y', strtotime($fechaSeleccionada))) ?>)</label>
                    
                    <?php if (!empty($horariosDisponibles)): ?>
                        <div class="grid grid-cols-3 md:grid-cols-5 gap-2">
                            <?php foreach($horariosDisponibles as $horas): ?>
                                <label class="cursor-pointer">
                                    <input type="radio" name="horario" value="<?= $horas['id_horario'] ?>" 
                                           onclick="document.getElementById('horario_texto').value = '<?= substr($horas['horario'], 0, 5) ?>'"
                                           required class="sr-only peer">
                                    <div class="p-3 text-sm border rounded-md transition-colors peer-checked:bg-sky-600 peer-checked:text-white peer-checked:border-sky-700 hover:bg-gray-100 text-center">
                                        <?= substr($horas['horario'], 0, 5) ?>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-600">Por favor, selecciona una fecha y haz clic en "Ver Horarios Disponibles".</p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-lg transition-colors">
                    Aceptar y Reprogramar
                </button>
                
                <!-- Botón Cancelar (Url Dinámica) -->
                <a href="<?= $urlCancelar ?>" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-4 px-6 rounded-lg text-lg transition-colors">
                    Cancelar
                </a>
            </form>
        </div>
    </div>

    <!-- Script de restricciones de fecha -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputFecha = document.getElementById('fecha');

            // Bloquear fechas pasadas
            const hoy = new Date().toISOString().split('T')[0];
            inputFecha.setAttribute('min', hoy);

            // Bloquear domingos
            inputFecha.addEventListener('input', function () {
                const seleccionada = new Date(this.value);
                const diaSemana = seleccionada.getUTCDay(); // 0 = domingo

                if (diaSemana === 0) {
                    // Usamos un simple console.warn en lugar de alert, es menos molesto
                    console.warn("No se atiende los domingos.");
                    this.value = ""; // limpia la selección
                    alert("Lo sentimos, no atendemos los domingos. Por favor selecciona otro día.");
                }
            });
        });
    </script>
</body>
</html>