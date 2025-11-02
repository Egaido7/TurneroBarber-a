<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeanBarber Arg</title>
        <link rel="stylesheet" href="<?= base_url('src/output.css') ?>">
        <link rel="icon" href="<?= base_url('src/Imagenes/leanbarber.png') ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
   
</head>
<body class="min-h-screen bg-white">
    <?php if (session()->getFlashdata('success')): ?>
    <div class="flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
        <!-- Ícono check -->
        <svg class="flex-shrink-0 w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.364 7.364a1 1 0 01-1.414 0L3.293 9.414a1 1 0 111.414-1.414l4.222 4.222 6.657-6.657a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        <span class="sr-only">Éxito</span>
        <div class="ml-3 text-sm font-medium">
            <?= session()->getFlashdata('success') ?>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="flex items-center p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
        <!-- Ícono X -->
        <svg class="flex-shrink-0 w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        <span class="sr-only">Error</span>
        <div class="ml-3 text-sm font-medium">
            <?= session()->getFlashdata('error') ?>
        </div>
    </div>
<?php endif; ?>
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-sm border-b border-gray-200 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <a href="<?= base_url('/') ?>">
                        <img src="<?= base_url('src/imagenes/logoinicial.png') ?>" alt="" style="width: 6.5em; height: 6.5em;">
                    </a>
                    
                    <span class="text-xl font-bold text-sky-500">LeanBarber</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <button onclick="scrollToSection('inicio')" class="text-gray-800 hover:text-sky-600 transition-colors">Inicio</button>
                    <button onclick="scrollToSection('historia')" class="text-gray-800 hover:text-sky-600 transition-colors">Nuestra Historia</button>
                    <button onclick="scrollToSection('servicios')" class="text-gray-800 hover:text-sky-600 transition-colors">Servicios</button>
                    <button onclick="scrollToSection('turnos')" class="text-gray-800 hover:text-sky-600 transition-colors">Sacar Turno</button>
                    <button onclick="scrollToSection('ubicacion')" class="text-gray-800 hover:text-sky-600 transition-colors">Ubicación</button>
                    <div class="flex items-center space-x-4">
                        <a href="https://www.instagram.com/lean_style01/" target="_blank" class="text-gray-800 hover:text-sky-600 transition-colors">
                            <i data-lucide="instagram" class="h-5 w-5"></i>
                        </a>
                        <a href="https://www.tiktok.com/@leanbraca" target="_blank" class="text-gray-800 hover:text-sky-600 transition-colors">
                            <i data-lucide="music" class="h-5 w-5"></i>
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-800 hover:text-sky-600">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <div class="flex flex-col space-y-2">
                    <button onclick="scrollToSection('inicio')" class="text-left text-gray-800 hover:text-sky-600 transition-colors py-2">Inicio</button>
                    <button onclick="scrollToSection('historia')" class="text-left text-gray-800 hover:text-sky-600 transition-colors py-2">Nuestra Historia</button>
                    <button onclick="scrollToSection('servicios')" class="text-left text-gray-800 hover:text-sky-600 transition-colors py-2">Servicios</button>
                    <button onclick="scrollToSection('turnos')" class="text-left text-gray-800 hover:text-sky-600 transition-colors py-2">Sacar Turno</button>
                    <button onclick="scrollToSection('ubicacion')" class="text-left text-gray-800 hover:text-sky-600 transition-colors py-2">Ubicación</button>
                    <div class="flex items-center space-x-4 py-2">
                        <a href="https://www.instagram.com/lean_style01/" target="_blank" class="text-gray-800 hover:text-sky-600 transition-colors">
                            <i data-lucide="instagram" class="h-5 w-5"></i>
                        </a>
                        <a href="https://www.tiktok.com/@leanbraca" target="_blank" class="text-gray-800 hover:text-sky-600 transition-colors">
                            <i data-lucide="music" class="h-5 w-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="pt-16 min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Creando Estilos, Definiendo Personalidades
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-8">
                    Estilo impecable, atención al milímetro. Trabajando tu imagen desde 2020.
                </p>
                <button onclick="scrollToSection('turnos')" class="bg-sky-500 hover:bg-sky-700 text-white font-bold py-4 px-8 rounded-lg text-lg transition-colors">
                    Reservar Turno
                </button>
            </div>
        </div>
    </section>

    <!-- Historia Section -->
    <section id="historia" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nuestra Historia</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Una tradición familiar que comenzó en 1995 y continúa evolucionando con los tiempos
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-3 h-3 bg-sky-500 rounded-full"></div>
                            <h3 class="text-lg font-bold">2020 - Los Inicios</h3>
                        </div>
                        <p class="text-gray-600">
                            Don Carlos abrió las puertas de su primera barbería con una visión clara: ofrecer el mejor servicio de corte y afeitado de la ciudad.
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-3 h-3 bg-sky-500 rounded-full"></div>
                            <h3 class="text-lg font-bold">2022 - Expansión</h3>
                        </div>
                        <p class="text-gray-600">
                            La segunda generación se suma al negocio familiar, incorporando técnicas modernas sin perder la esencia tradicional.
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-3 h-3 bg-sky-500 rounded-full"></div>
                            <h3 class="text-lg font-bold">2025 - Presente</h3>
                        </div>
                        <p class="text-gray-600">
                            Hoy somos referentes en la ciudad, combinando la experiencia de décadas con las últimas tendencias en barbería masculina.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-100 rounded-lg p-8 text-center">
                    <div class="w-32 h-32 bg-gray-800 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <i data-lucide="scissors" class="h-16 w-16 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Más de 15,000 cortes</h3>
                    <p class="text-gray-600">generando confianza a los clientes a lo largo de los años</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios Section -->
    <section id="servicios" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nuestros Servicios</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Servicios profesionales adaptados a tu estilo y personalidad
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if(isset($dataServicios) && !empty($dataServicios)): ?>
                <?php foreach($dataServicios as $service): ?>
                <div class="bg-white rounded-lg p-6 border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-bold"><?= $service['nombre'] ?></h3>
                        <span class="text-2xl font-bold text-sky-600"><?= $service['precio_total'] ?></span>
                    </div>
                    <p class="text-gray-600"><?= $service['descripcion'] ?></p>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p class="text-center text-gray-600 col-span-3">No hay servicios disponibles en este momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Turnos Section -->
    <section id="turnos" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Reservar Turno</h2>
                <p class="text-xl text-gray-600">Agenda tu cita de forma rápida y sencilla</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-8 border border-gray-200 max-w-2xl mx-auto">
                <h3 class="text-xl font-bold mb-2">Formulario de Reserva</h3>
                <p class="text-gray-600 mb-6">Completa tus datos y selecciona tu horario preferido</p>
                
                    <form action="<?= base_url('home/horarios') ?>" method="post" class="space-y-6 mb-6">
                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <input type="date" id="fecha" name="fecha" value="<?= isset($fechaSeleccionada) ? $fechaSeleccionada : '' ?>" required min="<?= date('Y-m-d') ?>" class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        </div>
                        <button type="submit" class="w-full bg-sky-400 hover:bg-sky-500 text-white font-bold py-4 px-6 rounded-lg text-lg transition-colors">
                        Ver Horarios Disponibles
                    </button>
                    </form>

                <form action="<?= base_url('turnos/procesar') ?>" method="POST" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-4">
                         <input type="hidden" name="fecha" value="<?= isset($fechaSeleccionada) ? $fechaSeleccionada : '' ?>">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                            <input type="text" id="nombre" name="nombre" pattern = "^[a-zA-Z\s]+$" required class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        </div>
                        <div>
                            <label for="apellido" class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                            <input type="text" id="apellido" name="apellido"  pattern = "^[a-zA-Z\s]+$" required class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        </div>
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" required  class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label for="barbero" class="block text-sm font-medium text-gray-700 mb-2">Barbero</label>
                        <select id="barbero" name="id_barbero" required class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            <option value="">Selecciona un barbero</option>
                            <?php if(isset($dataBarberos) && !empty($dataBarberos)): ?>
                                <?php foreach($dataBarberos as $barbero): ?>
                                    <option value="<?= $barbero['id_barbero'] ?>"><?= $barbero['nombre'] ?> <?= $barbero['apellido'] ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No hay barberos disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="servicio" class="block text-sm font-medium text-gray-700 mb-2">Servicio</label>
                        <select id="servicio" name="id_servicio" required class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            <option value="">Selecciona un servicio</option>
                                <?php if(isset($dataServicios) && !empty($dataServicios)): ?>
                            <?php foreach($dataServicios as $service): ?>
                            <option value="<?= $service['id_servicio'] ?>"><?= $service['nombre'] ?> - Total <?= $service['precio_total'] ?> - Seña <?= $service['monto_seña'] ?></option>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <option value="">No hay servicios disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mt-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Horarios Disponibles</label>
    <div class="grid grid-cols-3 md:grid-cols-4 gap-2">
        <?php if(isset($horariosDisponibles) && !empty($horariosDisponibles)): ?>
            <?php foreach($horariosDisponibles as $horas): ?>
                <label class="cursor-pointer">
                    <input type="radio" name="horario" value="<?= $horas['id_horario'] ?>" required class="sr-only peer">
                    <div class="p-2 text-sm border rounded-md transition-colors peer-checked:bg-sky-200 peer-checked:text-white peer-checked:border-sky-500 hover:bg-gray-300 text-center">
                        <?= substr($horas['horario'],0,5) ?>
                    </div>
                </label>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="mb-1">
                <p class="text-gray-600">Por favor, selecciona una fecha y haz clic en "Ver Horarios Disponibles" para mostrar los horarios.</p>
            </div>
           
        <?php endif; ?>
         
    </div>
</div>

                    <button type="submit" class="w-full bg-sky-400 hover:bg-sky-500 text-white font-bold py-4 px-6 rounded-lg text-lg transition-colors">
                        Finalizar y Pagar Seña
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Ubicación Section -->
    <section id="ubicacion" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Ubicación</h2>
                <p class="text-xl text-gray-600">Nos encontramos en el corazón de la ciudad</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div class="bg-white rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center space-x-2 mb-4">
                            <i data-lucide="map-pin" class="h-5 w-5 text-sky-500"></i>
                            <h3 class="text-lg font-bold">Dirección</h3>
                        </div>
                        <p class="text-gray-600">
                            San Martín 1349<br>
                            Centro de San Luis, Argentina<br>
                            CP 5700
                        </p>
                    </div>

                    <div class="bg-white rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center space-x-2 mb-4">
                            <i data-lucide="clock" class="h-5 w-5 text-sky-500"></i>
                            <h3 class="text-lg font-bold">Horarios</h3>
                        </div>
                        <div class="space-y-2 text-gray-600">
                            <p>Lunes a Viernes: 9:00 - 19:00</p>
                            <p>Sábados: 9:00 - 18:00</p>
                            <p>Domingos: Cerrado</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center space-x-2 mb-4">
                            <i data-lucide="phone" class="h-5 w-5 text-sky-500"></i>
                            <h3 class="text-lg font-bold">Contacto</h3>
                        </div>
                        <div class="space-y-2 text-gray-600">
                            <p>WhatsApp: +54 266 5044240</p>
                            <p>Email: info@barbershopelite.com</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-100 rounded-lg p-8 flex items-center justify-center">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d162.56753530825245!2d-66.34004509886933!3d-33.29419710494941!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95d4394e45d4846d%3A0x5551a9a0758d5382!2sSan%20Mart%C3%ADn%201349%2C%20D5702%20San%20Luis!5e1!3m2!1ses-419!2sar!4v1759026079809!5m2!1ses-419!2sar" width="550" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i data-lucide="scissors" class="h-8 w-8 text-sky-500"></i>
                        <span class="text-xl font-bold">BarberShop Elite</span>
                    </div>
                    <p class="text-gray-300">Tradición, calidad y estilo desde 1995.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Enlaces Rápidos</h3>
                    <div class="space-y-2">
                        <button onclick="scrollToSection('servicios')" class="block text-gray-300 hover:text-sky-600 transition-colors">Servicios</button>
                        <button onclick="scrollToSection('turnos')" class="block text-gray-300 hover:text-sky-600 transition-colors">Reservar Turno</button>
                        <button onclick="scrollToSection('ubicacion')" class="block text-gray-300 hover:text-sky-600 transition-colors">Ubicación</button>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Seguime</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.instagram.com/lean_style01/" target="_blank" class="text-gray-300 hover:text-sky-600 transition-colors">
                            <i data-lucide="instagram" class="h-6 w-6"></i>
                        </a>
                        <a href="https://www.tiktok.com/@leanbraca" target="_blank" class="text-gray-300 hover:text-sky-600 transition-colors">
                            <i data-lucide="music" class="h-6 w-6"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">© 2025 BarberShop Elite. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();
        
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
            // Cerrar menú móvil si está abierto
            document.getElementById('mobile-menu').classList.add('hidden');
        }

        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Cerrar menú móvil al hacer clic fuera
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuButton = event.target.closest('[onclick="toggleMobileMenu()"]');
            
            if (!menuButton && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>

    <script>
    const inputFecha = document.getElementById('fecha');

    // Bloquear fechas pasadas
    const hoy = new Date().toISOString().split('T')[0];
    inputFecha.setAttribute('min', hoy);

    // Bloquear domingos
    inputFecha.addEventListener('input', function () {
        const seleccionada = new Date(this.value);
        const diaSemana = seleccionada.getUTCDay(); // 0 = domingo

        if (diaSemana === 0) {
            alert("No se atiende los domingos ni se puede seleccionar una fecha pasada, por favor elegí otra fecha.");
            this.value = ""; // limpia la selección
        }
    });
</script>

<script>
    <?php if(isset($fechaSeleccionada) && !empty($fechaSeleccionada)): ?>
        // Si la variable $fechaSeleccionada existe (es decir, venimos de 'Ver Horarios'),
        // esperamos un momento a que la página cargue y hacemos scroll a la sección 'turnos'.
        window.addEventListener('load', function() {
            setTimeout(function() {
                const seccionTurnos = document.getElementById('turnos');
                if (seccionTurnos) {
                    seccionTurnos.scrollIntoView({ behavior: 'smooth' });
                }
            }, 100); // 100ms de espera
        });
    <?php endif; ?>
</script>
</body>
</html>