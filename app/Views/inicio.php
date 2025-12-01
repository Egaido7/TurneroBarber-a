<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeanBarber Arg</title>
        <link rel="stylesheet" href="<?= base_url('src/output.css') ?>">
        <link rel="icon" href="<?= base_url('src/Imagenes/logoinicial.png') ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
    
</head>
<body class="min-h-screen bg-white font-sans antialiased text-gray-900">
    <?php if (session()->getFlashdata('success')): ?>
    <div class="fixed top-20 right-4 z-50 animate-bounce">
        <div class="flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 shadow-lg" role="alert">
            <svg class="flex-shrink-0 w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.364 7.364a1 1 0 01-1.414 0L3.293 9.414a1 1 0 111.414-1.414l4.222 4.222 6.657-6.657a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            <div class="ml-3 text-sm font-medium">
                <?= session()->getFlashdata('success') ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="fixed top-20 right-4 z-50 animate-pulse">
        <div class="flex items-center p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 shadow-lg" role="alert">
            <svg class="flex-shrink-0 w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            <div class="ml-3 text-sm font-medium">
                <?= session()->getFlashdata('error') ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 w-full bg-transparent z-50 transition-all duration-300 ease-in-out">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <a href="<?= base_url('/') ?>">
                        <img src="<?= base_url('src/imagenes/logoinicial.png') ?>" alt="" style="width: 4.5em; height: 4.5em;">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <button onclick="scrollToSection('inicio')" class="nav-link text-white hover:text-[#c8a951] transition-colors font-medium text-sm tracking-wide uppercase">Inicio</button>
                    <button onclick="scrollToSection('sobre_mi')" class="nav-link text-white hover:text-[#c8a951] transition-colors font-medium text-sm tracking-wide uppercase">Sobre Mi</button>
                    <button onclick="scrollToSection('servicios')" class="nav-link text-white hover:text-[#c8a951] transition-colors font-medium text-sm tracking-wide uppercase">Servicios</button>
                    <button onclick="scrollToSection('turnos')" class="nav-link text-white hover:text-[#c8a951] transition-colors font-medium text-sm tracking-wide uppercase">Sacar Turno</button>
                    <button onclick="scrollToSection('ubicacion')" class="nav-link text-white hover:text-[#c8a951] transition-colors font-medium text-sm tracking-wide uppercase">Ubicación</button>
                    
                    <div class="flex items-center space-x-4 border-l border-white/20 pl-6">
                        <a href="https://www.instagram.com/lean_style01/" target="_blank" class="nav-link text-white hover:text-[#c8a951] transition-transform hover:scale-110 duration-200">
                            <i data-lucide="instagram" class="h-5 w-5"></i>
                        </a>
                        <a href="https://www.tiktok.com/@leanbraca" target="_blank" class="nav-link text-white hover:text-[#c8a951] transition-transform hover:scale-110 duration-200">
                            <i data-lucide="music" class="h-5 w-5"></i>
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="text-white hover:text-[#c8a951] transition-colors">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 bg-white rounded-b-lg shadow-xl absolute left-0 right-0 top-16 border-t border-gray-100">
                <div class="flex flex-col space-y-2 px-4 pt-2">
                    <button onclick="scrollToSection('inicio')" class="text-left text-gray-800 hover:text-[#c8a951] hover:bg-[#c8a951]/10 rounded-md px-3 py-2 transition-colors font-medium">Inicio</button>
                    <button onclick="scrollToSection('sobre_mi')" class="text-left text-gray-800 hover:text-[#c8a951] hover:bg-[#c8a951]/10 rounded-md px-3 py-2 transition-colors font-medium">Sobre Mi</button>
                    <button onclick="scrollToSection('servicios')" class="text-left text-gray-800 hover:text-[#c8a951] hover:bg-[#c8a951]/10 rounded-md px-3 py-2 transition-colors font-medium">Servicios</button>
                    <button onclick="scrollToSection('turnos')" class="text-left text-gray-800 hover:text-[#c8a951] hover:bg-[#c8a951]/10 rounded-md px-3 py-2 transition-colors font-medium">Sacar Turno</button>
                    <button onclick="scrollToSection('ubicacion')" class="text-left text-gray-800 hover:text-[#c8a951] hover:bg-[#c8a951]/10 rounded-md px-3 py-2 transition-colors font-medium">Ubicación</button>
                    <div class="flex items-center space-x-6 py-4 justify-center border-t border-gray-100 mt-2">
                        <a href="https://www.instagram.com/lean_style01/" target="_blank" class="text-gray-800 hover:text-[#c8a951] transition-colors">
                            <i data-lucide="instagram" class="h-6 w-6"></i>
                        </a>
                        <a href="https://www.tiktok.com/@leanbraca" target="_blank" class="text-gray-800 hover:text-[#c8a951] transition-colors">
                            <i data-lucide="music" class="h-6 w-6"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="pt-0 min-h-screen flex items-center justify-center bg-black relative">
        <!-- Fondo Negro Absoluto Detrás del Video -->
        <div class="absolute inset-0 bg-black z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            
            <!-- BLOQUE DE VIDEO-TEXT -->
            <div class="relative w-full max-w-5xl h-[300px] md:h-[400px] overflow-hidden rounded-2xl mx-auto mb-8 shadow-2xl shadow-[#c8a951]/20 bg-black group transition-transform duration-500 hover:scale-[1.01]">
                
                <!-- Capa 1: El Video -->
                <video
                    class="absolute inset-0 w-full h-full object-cover opacity-90"
                    autoplay loop muted playsinline
                    poster="<?= base_url('src/Imagenes/leanbarber.png') ?>"
                >
                    <source src="<?=base_url('src/Imagenes/videoBarber.mp4') ?>" type="video/mp4" />
                    Tu navegador no soporta el tag de video.
                </video>
                
                <!-- Capa 2: La Máscara -->
                <div class="absolute inset-0 z-10 flex items-center justify-center bg-black mix-blend-multiply p-4">
                    <h1 class="text-4xl md:text-7xl font-black text-white text-center leading-snug md:leading-tight uppercase tracking-wider select-none">
                        Creando Estilos<br> Definiendo Personalidades
                    </h1>
                </div>
            </div>

            <!-- Subtítulo y Botón -->
            <p class="text-xl md:text-2xl text-gray-300 mb-10 font-light tracking-wide">
                Estilo impecable, atención al milímetro. Trabajando tu imagen desde 2020.
            </p>
            <!-- Botón Mejorado -->
            <button onclick="scrollToSection('turnos')" class="group relative bg-[#c8a951] hover:bg-[#b09344] text-white font-bold py-4 px-10 rounded-full text-lg transition-all duration-300 shadow-[0_0_20px_rgba(200,169,81,0.5)] hover:shadow-[0_0_30px_rgba(200,169,81,0.8)] hover:-translate-y-1">
                <span class="flex items-center">
                    Reservar Turno
                    <i data-lucide="calendar-check" class="ml-2 h-5 w-5 group-hover:rotate-12 transition-transform"></i>
                </span>
            </button>
            
        </div>
    </section>

    <!-- Sobre Mi Section -->
    <section id="sobre_mi" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Sobre Mi</h2>
                <div class="w-24 h-1 bg-[#c8a951] mx-auto rounded-full mb-6"></div>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Barbero apasionado, transformo cortes en confianza.
                </p>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto"> Líder en estilo y técnica </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-stretch">
                
                <!-- COLUMNA IZQUIERDA: Carrusel de Videos -->
                <div class="h-full min-h-[450px] bg-white rounded-2xl border border-gray-100 shadow-lg hover:shadow-xl transition-all duration-300 group relative overflow-hidden">
                    
                    <!-- Contenedor de Slides -->
                    <div id="history-carousel" class="relative h-full w-full">
                        <!-- Slide 1: 2020 -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-500 ease-in-out opacity-100" data-index="0" style="z-index: 10;">
                            <video class="w-full h-[60%] object-cover" autoplay loop muted playsinline>
                                <source src="<?= base_url('src/Imagenes/video1.mp4') ?>" type="video/mp4">
                                Tu navegador no soporta video.
                            </video>
                            <div class="p-8 bg-white h-[40%] flex flex-col justify-center relative">
                                <div class="absolute -top-6 left-8 bg-[#c8a951] text-white px-4 py-1 rounded-full text-sm font-bold shadow-md">2020</div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">Los Inicios</h3>
                                <p class="text-gray-600 leading-relaxed">Nacido en 2020 desde su propio hogar, comenzó a desarrollar su pasión por la barbería. Se formó con múltiples cursos y perfeccionó cada técnica mientras atendía a sus primeros clientes con dedicación y estilo.</p>
                            </div>
                        </div>

                        <!-- Slide 2: 2022 -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-500 ease-in-out opacity-0" data-index="1" style="z-index: 0;">
                            <video class="w-full h-[60%] object-cover" autoplay loop muted playsinline>
                                <source src="<?= base_url('src/Imagenes/video2.mp4') ?>" type="video/mp4">
                            </video>
                            <div class="p-8 bg-white h-[40%] flex flex-col justify-center relative">
                                <div class="absolute -top-6 left-8 bg-[#c8a951] text-white px-4 py-1 rounded-full text-sm font-bold shadow-md">2022</div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">Expansión</h3>
                                <p class="text-gray-600 leading-relaxed">Tras un año trabajando en una barbería profesional, ganó experiencia, confianza y una base sólida de clientes fieles. Su crecimiento lo llevó a mudarse al espacio donde continuó afianzando su identidad como barbero.</p>
                            </div>
                        </div>

                        <!-- Slide 3: 2025 -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-500 ease-in-out opacity-0" data-index="2" style="z-index: 0;">
                            <video class="w-full h-[60%] object-cover" autoplay loop muted playsinline>
                                <source src="<?= base_url('src/Imagenes/video3.mp4') ?>" type="video/mp4">
                            </video>
                            <div class="p-8 bg-white h-[40%] flex flex-col justify-center relative">
                                <div class="absolute -top-6 left-8 bg-[#c8a951] text-white px-4 py-1 rounded-full text-sm font-bold shadow-md">2025</div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">Presente</h3>
                                <p class="text-gray-600 leading-relaxed">Hoy se encuentra en su mejor momento: consolidado, con una comunidad que lo acompaña, y dando el siguiente paso hacia su propia barbería. Innovación, calidad y compromiso en cada corte.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Controles del Carrusel -->
                    <div class="absolute top-[25%] w-full flex justify-between px-4 z-20">
                        <button onclick="moveSlide(-1)" class="p-2 bg-white/80 hover:bg-[#c8a951] hover:text-white rounded-full shadow-md transition-all backdrop-blur-sm cursor-pointer">
                            <i data-lucide="chevron-left" class="h-6 w-6"></i>
                        </button>
                        <button onclick="moveSlide(1)" class="p-2 bg-white/80 hover:bg-[#c8a951] hover:text-white rounded-full shadow-md transition-all backdrop-blur-sm cursor-pointer">
                            <i data-lucide="chevron-right" class="h-6 w-6"></i>
                        </button>
                    </div>

                    <!-- Indicadores (Dots) -->
                    <div class="absolute bottom-6 right-8 flex space-x-2 z-20">
                        <button onclick="goToSlide(0)" class="carousel-dot w-3 h-3 rounded-full bg-[#c8a951] transition-all"></button>
                        <button onclick="goToSlide(1)" class="carousel-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-[#c8a951]/50 transition-all"></button>
                        <button onclick="goToSlide(2)" class="carousel-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-[#c8a951]/50 transition-all"></button>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: Card de Estadísticas (+15,000) -->
                <div class="h-full flex flex-col justify-center">
                    <div class="bg-gray-50 rounded-2xl p-10 text-center border border-gray-100 shadow-inner h-full flex flex-col justify-center items-center hover:shadow-lg transition-shadow duration-300">
                        <div class="w-32 h-32 bg-gray-900 rounded-full mb-6 flex items-center justify-center shadow-xl shadow-gray-500/20">
                            <i data-lucide="scissors" class="h-14 w-14 text-[#c8a951]"></i>
                        </div>
                        <h3 class="text-4xl font-bold text-gray-900 mb-2">15,000+</h3>
                        <p class="text-xl text-gray-600 font-medium">Cortes Realizados</p>
                        <p class="text-sm text-gray-400 mt-4 max-w-xs mx-auto">
                            Generando confianza, estilo y satisfacción en cada cliente desde nuestros inicios.
                        </p>
                    </div>
                </div>

            </div>
    </section>

    <!-- Servicios Section -->
    <section id="servicios" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Nuestros Servicios</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Experiencias personalizadas para tu estilo único.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if(isset($dataServicios) && !empty($dataServicios)): ?>
                <?php foreach($dataServicios as $service): ?>
                <!-- Card de Servicio Mejorada -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group cursor-default">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-[#c8a951]/10 rounded-lg group-hover:bg-[#c8a951]/20 transition-colors">
                            <i data-lucide="star" class="h-6 w-6 text-[#c8a951]"></i>
                        </div>
                        <span class="text-2xl font-bold text-[#c8a951]">$<?= number_format($service['precio_total'], 0, ',', '.') ?></span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#c8a951] transition-colors"><?= $service['nombre'] ?></h3>
                    <p class="text-gray-600 leading-relaxed text-sm"><?= $service['descripcion'] ?></p>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500 text-lg">No hay servicios disponibles por el momento.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Turnos Section -->
    <section id="turnos" class="py-24 bg-white relative overflow-hidden">
        <!-- Decoración de fondo sutil -->
        <div class="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-white to-gray-50 -z-10"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Reservar Turno</h2>
                <p class="text-xl text-gray-600">Agenda tu cita de forma rápida, sencilla y segura.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10 border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                    <i data-lucide="calendar" class="mr-3 h-6 w-6 text-[#c8a951]"></i>
                    Formulario de Reserva
                </h3>
                <p class="text-gray-500 mb-8 text-sm">Completa tus datos para confirmar tu asistencia.</p>
                
                    <form action="<?= base_url('home/horarios') ?>" method="post" class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-100">
                        <?= csrf_field() ?>
                        <div class="flex flex-col md:flex-row gap-4 items-end">
                            <div class="w-full">
                                <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-2">Selecciona una fecha</label>
                                <input type="date" id="fecha" name="fecha" value="<?= isset($fechaSeleccionada) ? $fechaSeleccionada : '' ?>" required min="<?= date('Y-m-d') ?>" class="w-full p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#c8a951] focus:border-[#c8a951] transition-shadow shadow-sm">
                            </div>
                            <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded-lg transition-all shadow-md hover:shadow-lg whitespace-nowrap">
                                Buscar Horarios
                            </button>
                        </div>
                    </form>

                <form action="<?= base_url('turnos/procesar') ?>" method="POST" class="space-y-6">
                    <?= csrf_field() ?>
                    <input type="hidden" name="fecha" value="<?= isset($fechaSeleccionada) ? $fechaSeleccionada : '' ?>">
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="relative group">
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                            <input type="text" id="nombre" name="nombre" pattern="^[a-zA-Z\s]+$" required class="w-full p-3 pl-10 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#c8a951] focus:border-[#c8a951] transition-all shadow-sm group-hover:border-gray-400" placeholder="Tu nombre">
                            <i data-lucide="user" class="absolute left-3 top-[38px] h-5 w-5 text-gray-400"></i>
                        </div>
                        <div class="relative group">
                            <label for="apellido" class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                            <input type="text" id="apellido" name="apellido" pattern="^[a-zA-Z\s]+$" required class="w-full p-3 pl-10 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#c8a951] focus:border-[#c8a951] transition-all shadow-sm group-hover:border-gray-400" placeholder="Tu apellido">
                            <i data-lucide="user" class="absolute left-3 top-[38px] h-5 w-5 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="relative group">
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" required class="w-full p-3 pl-10 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#c8a951] focus:border-[#c8a951] transition-all shadow-sm group-hover:border-gray-400" placeholder="Ej: 11 1234 5678">
                            <i data-lucide="phone" class="absolute left-3 top-[38px] h-5 w-5 text-gray-400"></i>
                        </div>
                        <div class="relative group">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" required placeholder="ejemplo@gmail.com" class="w-full p-3 pl-10 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#c8a951] focus:border-[#c8a951] transition-all shadow-sm group-hover:border-gray-400">
                            <i data-lucide="mail" class="absolute left-3 top-[38px] h-5 w-5 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="barbero" class="block text-sm font-medium text-gray-700 mb-2">Barbero</label>
                            
                            <?php if(isset($dataBarberos) && count($dataBarberos) === 1): ?>
                                <?php $unicoBarbero = $dataBarberos[0]; ?>
                                <input type="hidden" name="id_barbero" value="<?= $unicoBarbero['id_barbero'] ?>">
                                <div class="relative">
                                    <input type="text" value="<?= $unicoBarbero['nombre'] ?> <?= $unicoBarbero['apellido'] ?>" readonly class="w-full p-3 pl-10 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none shadow-inner">
                                    <i data-lucide="user-check" class="absolute left-3 top-[14px] h-5 w-5 text-gray-400"></i>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i data-lucide="lock" class="h-4 w-4 text-gray-400"></i>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="relative">
                                    <select id="barbero" name="id_barbero" required class="w-full p-3 pl-10 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#c8a951] focus:border-[#c8a951] shadow-sm appearance-none">
                                        <option value="">Selecciona un barbero</option>
                                        <?php if(isset($dataBarberos) && !empty($dataBarberos)): ?>
                                            <?php foreach($dataBarberos as $barbero): ?>
                                                <option value="<?= $barbero['id_barbero'] ?>"><?= $barbero['nombre'] ?> <?= $barbero['apellido'] ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">No hay barberos disponibles</option>
                                        <?php endif; ?>
                                    </select>
                                    <i data-lucide="user-check" class="absolute left-3 top-[14px] h-5 w-5 text-gray-400"></i>
                                    <i data-lucide="chevron-down" class="absolute right-3 top-[14px] h-5 w-5 text-gray-400 pointer-events-none"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="servicio" class="block text-sm font-medium text-gray-700 mb-2">Servicio</label>
                            <div class="relative">
                                <select id="servicio" name="id_servicio" required class="w-full p-3 pl-10 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#c8a951] focus:border-[#c8a951] shadow-sm appearance-none">
                                    <option value="">Selecciona un servicio</option>
                                        <?php if(isset($dataServicios) && !empty($dataServicios)): ?>
                                    <?php foreach($dataServicios as $service): ?>
                                    <option value="<?= $service['id_servicio'] ?>"><?= $service['nombre'] ?> - Total $<?= number_format($service['precio_total'], 0, ',', '.') ?> - Seña $<?= number_format($service['monto_seña'], 0, ',', '.') ?></option>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <option value="">No hay servicios disponibles</option>
                                    <?php endif; ?>
                                </select>
                                <i data-lucide="scissors" class="absolute left-3 top-[14px] h-5 w-5 text-gray-400"></i>
                                <i data-lucide="chevron-down" class="absolute right-3 top-[14px] h-5 w-5 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-6 bg-[#c8a951]/10 rounded-xl border border-[#c8a951]/30" id="horariosDisponiblesContainer">
                        <label class="block text-sm font-bold text-[#8c732d] mb-4 flex items-center">
                            <i data-lucide="clock" class="mr-2 h-4 w-4"></i>
                            Horarios Disponibles
                        </label>
                        <?php if(isset($errorHorario)): ?>
                            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-center mb-4">
                                <div class="flex justify-center mb-2">
                                    <i data-lucide="calendar-off" class="h-6 w-6"></i>
                                </div>
                                <p class="font-bold"><?= $errorHorario ?></p>
                                <p class="text-sm mt-1">Por favor selecciona otra fecha. Este día está bloqueado.</p>
                            </div>
                        <?php endif; ?>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                            <?php if(isset($horariosDisponibles) && !empty($horariosDisponibles)): ?>
                                <?php foreach($horariosDisponibles as $horas): ?>
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="horario" value="<?= $horas['id_horario'] ?>" required class="sr-only peer">
                                        <div class="py-2 px-1 text-sm border border-[#c8a951]/30 bg-white rounded-lg transition-all peer-checked:bg-[#c8a951] peer-checked:text-white peer-checked:border-[#a88c3d] peer-checked:shadow-md hover:bg-[#c8a951]/10 text-center font-medium text-[#8c732d] group-hover:-translate-y-0.5">
                                            <?= substr($horas['horario'],0,5) ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-span-full text-center py-4">
                                    <p class="text-gray-500 italic">Por favor, selecciona una fecha y haz clic en "Ver Horarios" para mostrar la disponibilidad.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#c8a951] hover:bg-[#b09344] text-white font-bold py-4 px-6 rounded-xl text-lg transition-all shadow-lg hover:shadow-[#c8a951]/40 hover:-translate-y-1 mt-4 flex justify-center items-center">
                        <i data-lucide="check-circle" class="mr-2 h-6 w-6"></i>
                        Finalizar y Pagar Seña
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Ubicación Section (CORREGIDA PARA MOBILE) -->
    <section id="ubicacion" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Ubicación</h2>
                <p class="text-xl text-gray-600">Nos encontramos en el corazón de la ciudad.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-[#c8a951]/20 rounded-full">
                                <i data-lucide="map-pin" class="h-6 w-6 text-[#c8a951]"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Dirección</h3>
                        </div>
                        <p class="text-gray-600 text-lg ml-16">
                            Tómas Jofre 993<br>
                            Centro de San Luis, Argentina<br>
                            <span class="text-gray-400 text-sm">CP 5700</span>
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-[#c8a951]/20 rounded-full">
                                <i data-lucide="clock" class="h-6 w-6 text-[#c8a951]"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Horarios</h3>
                        </div>
                        <div class="space-y-2 text-gray-600 text-lg ml-16">
                            <div class="flex justify-between border-b border-gray-50 pb-2"><span>Lunes a Viernes:</span> <span class="font-semibold">9:00 - 19:00</span></div>
                            <div class="flex justify-between border-b border-gray-50 pb-2"><span>Sábados:</span> <span class="font-semibold">9:00 - 18:00</span></div>
                            <div class="flex justify-between text-gray-400"><span>Domingos:</span> <span>Cerrado</span></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-[#c8a951]/20 rounded-full">
                                <i data-lucide="phone" class="h-6 w-6 text-[#c8a951]"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Contacto</h3>
                        </div>
                        <div class="space-y-3 ml-16">
                            <a href="https://wa.me/542665044240" target="_blank" class="flex items-center text-gray-600 hover:text-green-600 transition-colors">
                                <i data-lucide="message-circle" class="h-5 w-5 mr-2"></i> WhatsApp: +54 266 5044240
                            </a>
                            <a href="mailto:info@barbershopelite.com" class="flex items-center text-gray-600 hover:text-[#c8a951] transition-colors">
                                <i data-lucide="mail" class="h-5 w-5 mr-2"></i> Email: info@barbershopelite.com
                            </a>
                        </div>
                    </div>
                </div>

                <!-- MAPA RESPONSIVE (CLASES TAILWIND) -->
                <div class="bg-white p-3 rounded-3xl shadow-lg h-[500px] lg:h-auto relative overflow-hidden group">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2601.0530259070224!2d-66.34207882529029!3d-33.29512078957249!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95d4394e3d252517%3A0xc7d43e06e62153c!2sTom%C3%A1s%20Jofre%20993%2C%20D5702%20San%20Luis!5e1!3m2!1ses-419!2sar!4v1764562751508!5m2!1ses-419!2sar" 
                        class="w-full h-full rounded-2xl grayscale group-hover:grayscale-0 transition-all duration-500" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white py-16 border-t border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-10 mb-10">
                <div class="col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <a href="<?= base_url('/') ?>">
                        <img src="<?= base_url('src/imagenes/logoinicial.png') ?>" alt="" style="width: 7.5em; height: 7.5em;">
                    </a>
                    </div>
                    <p class="text-gray-400 leading-relaxed max-w-sm">
                        Más que un corte, una experiencia. Tradición, calidad y estilo desde 2020, adaptándonos a las tendencias modernas para tu mejor imagen.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-6 text-gray-200">Navegación</h3>
                    <ul class="space-y-3">
                        <li><button onclick="scrollToSection('servicios')" class="text-gray-400 hover:text-[#c8a951] transition-colors flex items-center"><i data-lucide="chevron-right" class="h-4 w-4 mr-1"></i> Servicios</button></li>
                        <li><button onclick="scrollToSection('turnos')" class="text-gray-400 hover:text-[#c8a951] transition-colors flex items-center"><i data-lucide="chevron-right" class="h-4 w-4 mr-1"></i> Reservar Turno</button></li>
                        <li><button onclick="scrollToSection('ubicacion')" class="text-gray-400 hover:text-[#c8a951] transition-colors flex items-center"><i data-lucide="chevron-right" class="h-4 w-4 mr-1"></i> Ubicación</button></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-6 text-gray-200">Síguenos</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.instagram.com/lean_style01/" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-gradient-to-tr hover:from-yellow-500 hover:via-red-500 hover:to-purple-500 transition-all duration-300 group">
                            <i data-lucide="instagram" class="h-5 w-5 text-gray-400 group-hover:text-white transition-colors"></i>
                        </a>
                        <a href="https://www.tiktok.com/@leanbraca" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white transition-all duration-300 group border border-gray-700 hover:border-gray-500">
                            <i data-lucide="music" class="h-5 w-5 text-gray-400 group-hover:text-white transition-colors"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>&copy; 2025 LeanStyle Barber. Todos los derechos reservados.</p>
                <p class="mt-2 md:mt-0 flex items-center">
                    Desarrollado con <i data-lucide="heart" class="h-3 w-3 mx-1 text-red-500 fill-current"></i> en San Luis
                </p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();
        
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
            document.getElementById('mobile-menu').classList.add('hidden');
        }

        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuButton = event.target.closest('[onclick="toggleMobileMenu()"]');
            
            if (!menuButton && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
        
        document.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const navLinks = document.querySelectorAll('.nav-link');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (window.scrollY > 50) {
                navbar.classList.remove('bg-transparent');
                navbar.classList.add('bg-white/90', 'backdrop-blur-md', 'border-b', 'border-gray-200', 'shadow-sm');
                
                if (mobileMenuBtn) mobileMenuBtn.classList.replace('text-white', 'text-gray-800');
                navLinks.forEach(link => {
                    link.classList.remove('text-white', 'hover:text-[#c8a951]');
                    link.classList.add('text-gray-800', 'hover:text-[#c8a951]');
                });
                
                mobileMenu.classList.add('border-t', 'border-gray-100');
                
            } else {
                navbar.classList.add('bg-transparent');
                navbar.classList.remove('bg-white/90', 'backdrop-blur-md', 'border-b', 'border-gray-200', 'shadow-sm');
                
                if (mobileMenuBtn) mobileMenuBtn.classList.replace('text-gray-800', 'text-white');
                navLinks.forEach(link => {
                    link.classList.add('text-white', 'hover:text-[#c8a951]');
                    link.classList.remove('text-gray-800', 'hover:text-[#c8a951]');
                });
                
                mobileMenu.classList.remove('border-t', 'border-gray-100');
            }
        });
    </script>

    <script>
    const inputFecha = document.getElementById('fecha');
    const hoy = new Date().toISOString().split('T')[0];
    inputFecha.setAttribute('min', hoy);

    inputFecha.addEventListener('input', function () {
        const seleccionada = new Date(this.value);
        const diaSemana = seleccionada.getUTCDay(); // 0 = domingo

        if (diaSemana === 0) {
            alert("No se atiende los domingos ni se puede seleccionar una fecha pasada, por favor elegí otra fecha.");
            this.value = ""; 
        }
    });
</script>

<script>
    <?php if(isset($fechaSeleccionada) && !empty($fechaSeleccionada)): ?>
        window.addEventListener('load', function() {
            setTimeout(function() {
                const seccionHorarios = document.getElementById('horariosDisponiblesContainer');
                if (seccionHorarios) {
                    seccionHorarios.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        });
    <?php endif; ?>
</script>

<!-- CAROUSEL SCRIPT (Vanilla JS) -->
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const totalSlides = slides.length;

    function showSlide(index) {
        if (index >= totalSlides) currentSlide = 0;
        else if (index < 0) currentSlide = totalSlides - 1;
        else currentSlide = index;

        slides.forEach((slide, i) => {
            slide.style.opacity = (i === currentSlide) ? '1' : '0';
            slide.style.zIndex = (i === currentSlide) ? '10' : '0';
        });

        dots.forEach((dot, i) => {
            if (i === currentSlide) {
                dot.classList.remove('bg-gray-300', 'hover:bg-[#c8a951]/50');
                dot.classList.add('bg-[#c8a951]');
            } else {
                dot.classList.add('bg-gray-300', 'hover:bg-[#c8a951]/50');
                dot.classList.remove('bg-[#c8a951]');
            }
        });
    }

    function moveSlide(step) {
        showSlide(currentSlide + step);
    }

    function goToSlide(index) {
        showSlide(index);
    }

    // Init
    showSlide(0);
</script>
</body>
</html>