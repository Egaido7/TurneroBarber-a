<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Procesada - BarberShop Elite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: '#ffffff',
                        foreground: '#1f2937',
                        primary: '#dc2626',
                        'primary-foreground': '#ffffff',
                        secondary: '#1f2937',
                        'secondary-foreground': '#ffffff',
                        muted: '#f3f4f6',
                        'muted-foreground': '#6b7280',
                        border: '#e5e7eb',
                        input: '#f9fafb',
                        card: '#f9fafb'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
    <style>
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes checkmark {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }
        .animate-fade-in-scale { animation: fadeInScale 0.5s ease-out; }
        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            animation: checkmark 0.6s ease-in-out 0.3s forwards;
        }
        .checkmark-check {
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: checkmark 0.3s ease-in-out 0.6s forwards;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-800 to-gray-700 flex items-center justify-center p-4">
    <div class="w-full max-w-md animate-fade-in-scale">
        
        <!-- Mensaje de Éxito -->
        <?php if(session()->getFlashdata('exito')): ?>
        <div class="bg-white rounded-lg shadow-xl p-8 text-center">
            <!-- Icono de éxito animado -->
            <div class="mb-6">
                <svg class="w-24 h-24 mx-auto" viewBox="0 0 52 52">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" stroke="#22c55e" stroke-width="2"/>
                    <path class="checkmark-check" fill="none" stroke="#22c55e" stroke-width="2" d="M14 27l7 7 16-16"/>
                </svg>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-4">¡Reserva Exitosa!</h2>
            
            <p class="text-gray-600 mb-6">
                <?= session()->getFlashdata('exito') ?>
            </p>

            <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                    <i data-lucide="calendar-check" class="h-5 w-5 text-red-600 mr-2"></i>
                    Detalles de tu reserva
                </h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <!-- Los ?? '...' son valores por defecto por si los datos no llegan -->
                    <p><strong>Servicio:</strong> <?= session()->getFlashdata('servicio') ?? 'No especificado' ?></p>
                    <p><strong>Fecha:</strong> <?= session()->getFlashdata('fecha') ?? 'No especificada' ?></p>
                    <p><strong>Horario:</strong> <?= session()->getFlashdata('horario') ?? 'No especificado' ?></p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-2">
                    <i data-lucide="info" class="h-5 w-5 text-blue-600 mt-0.5"></i>
                    <p class="text-sm text-blue-800">
                        Recuerda llegar 5 minutos antes de tu turno. Si necesitas cancelar, contáctanos con al menos 24 horas de anticipación.
                    </p>
                </div>
            </div>

            <button 
                onclick="redirigirInicio()" 
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2"
            >
                <span>Volver al Inicio</span>
                <i data-lucide="home" class="h-5 w-5"></i>
            </button>

            <p class="text-sm text-gray-500 mt-4">
                Serás redirigido automáticamente en <span id="countdown">15</span> segundos...
            </p>
        </div>

        <!-- Mensaje de Error -->
        <?php elseif(session()->getFlashdata('error')): ?>
        <div class="bg-white rounded-lg shadow-xl p-8 text-center">
            <!-- Icono de error -->
            <div class="mb-6">
                <div class="w-24 h-24 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                    <i data-lucide="x-circle" class="h-16 w-16 text-red-600"></i>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-4">Error en la Reserva</h2>
            
            <p class="text-gray-600 mb-6">
                Lo sentimos, hubo un problema al procesar tu reserva. Por favor, intenta nuevamente.
            </p>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-2">
                    <i data-lucide="alert-triangle" class="h-5 w-5 text-red-600 mt-0.5"></i>
                    <p class="text-sm text-red-800">
                        <?= session()->getFlashdata('error') ?? 'Error desconocido. Por favor, contacta con nosotros.' ?>
                    </p>
                </div>
            </div>

            <div class="space-y-3">
                <button 
                    onclick="window.history.back()" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2"
                >
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                    <span>Volver al Formulario</span>
                </button>

                <button 
                    onclick="redirigirInicio()" 
                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2"
                >
                    <span>Ir al Inicio</span>
                    <i data-lucide="home" class="h-5 w-5"></i>
                </button>
            </div>

            <p class="text-sm text-gray-500 mt-4">
                Serás redirigido al inicio en <span id="countdown">18</span> segundos...
            </p>
        </div>
        
        <?php else: ?>
        <!-- Opcional: ¿Qué mostrar si se accede directamente a la URL? -->
        <div class="bg-white rounded-lg shadow-xl p-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Página no encontrada</h2>
            <p class="text-gray-600 mb-6">
                No hay información de reserva para mostrar.
            </p>
            <button 
                onclick="redirigirInicio()" 
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2"
            >
                <span>Volver al Inicio</span>
                <i data-lucide="home" class="h-5 w-5"></i>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();

        // Función para redirigir al inicio
        function redirigirInicio() {
            window.location.href = '<?= base_url('/') ?>';
        }

        // Countdown y redirección automática
        // Solo activa el countdown si existe el elemento
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            let countdown = <?= session()->getFlashdata('error') ? 8 : 5 ?>;
            
            const interval = setInterval(() => {
                countdown--;
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }
                
                if (countdown <= 0) {
                    clearInterval(interval);
                    redirigirInicio();
                }
            }, 1000);
        }
    </script>
</body>
</html>

