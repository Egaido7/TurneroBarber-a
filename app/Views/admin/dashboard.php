<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="<?= base_url('src/output.css') ?>">
    <link rel="icon" href="<?= base_url('src/Imagenes/leanbarber.png') ?>" type="image/png">
</head>
<body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: false }">

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-30" 
         :class="{'translate-x-0': sidebarOpen}">
        
        <div class="p-4">
            <a href="<?= base_url('admin') ?>" class="flex items-center space-x-2">
                <img src="<?= base_url('src/imagenes/logoinicial.png') ?>" alt="Logo" class="h-10 w-10">
                <span class="text-xl font-bold">LeanBarber Admin</span>
            </a>
        </div>

        <nav class="mt-8">
            <a href="<?= site_url('admin?section=turnos') ?>" class="flex items-center px-4 py-3 <?= ($section === 'turnos') ? 'bg-gray-700' : 'hover:bg-gray-700' ?>">
                <i data-lucide="calendar" class="h-5 w-5 mr-3"></i> Turnos
            </a>
            <a href="<?= site_url('admin?section=servicios') ?>" class="flex items-center px-4 py-3 <?= ($section === 'servicios') ? 'bg-gray-700' : 'hover:bg-gray-700' ?>">
                <i data-lucide="scissors" class="h-5 w-5 mr-3"></i> Servicios
            </a>
            <a href="<?= site_url('admin?section=peluqueros') ?>" class="flex items-center px-4 py-3 <?= ($section === 'peluqueros') ? 'bg-gray-700' : 'hover:bg-gray-700' ?>">
                <i data-lucide="users" class="h-5 w-5 mr-3"></i> Peluqueros
            </a>
            <a href="<?= site_url('admin?section=estadisticas') ?>" class="flex items-center px-4 py-3 <?= ($section === 'estadisticas') ? 'bg-gray-700' : 'hover:bg-gray-700' ?>">
                <i data-lucide="bar-chart-2" class="h-5 w-5 mr-3"></i> Estadísticas
            </a>
            <a href="<?= site_url('admin?section=precios') ?>" class="flex items-center px-4 py-3 <?= ($section === 'precios') ? 'bg-gray-700' : 'hover:bg-gray-700' ?>">
                <i data-lucide="dollar-sign" class="h-5 w-5 mr-3"></i> Precios y Señas
            </a>
            <!-- Nuevo enlace para Días Bloqueados -->
            <a href="<?= site_url('admin?section=dias_bloqueados') ?>" class="flex items-center px-4 py-3 <?= ($section === 'dias_bloqueados') ? 'bg-gray-700' : 'hover:bg-gray-700' ?>">
                <i data-lucide="calendar-off" class="h-5 w-5 mr-3"></i> Días Bloqueados
            </a>
            <a href="<?= site_url('logout') ?>" class="flex items-center px-4 py-3 hover:bg-gray-700 mt-4">
                <i data-lucide="log-out" class="h-5 w-5 mr-3"></i> Cerrar Sesión
            </a>
        </nav>
    </div>

    <!-- Overlay para Sidebar (Mobile) -->
    <div class="fixed inset-0 z-20 bg-black opacity-50 md:hidden" x-show="sidebarOpen" @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 md:ml-64">
        
        <!-- Header -->
        <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200 fixed w-full md:w-auto md:static px-4 z-10">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none focus:text-gray-700 md:hidden mr-2">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-semibold text-gray-800">Panel de Administración</h1>
            </div>
            
            <div class="flex items-center pr-4">
                <span class="text-gray-700">Hola, <?= session()->get('username') ?? 'Admin' ?></span>
            </div>
        </div>
        
        <!-- Content -->
        <main class="flex-1 p-6 mt-16 md:mt-0">
            <!-- Mensaje de éxito -->
            <?php if (session()->getFlashdata('mensaje')): ?>
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                    <?= session()->getFlashdata('mensaje') ?>
                </div>
            <?php endif; ?>

            <!-- Mensaje de error -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Dynamic Content -->
            <?php
                // Cargar la sección correspondiente
                if (isset($section)) {
                    // Validar $section para evitar LFI (Local File Inclusion)
                    // --- AQUÍ ESTABA EL ERROR: Faltaba 'dias_bloqueados' ---
                    $allowedSections = ['turnos', 'servicios', 'peluqueros', 'estadisticas', 'precios', 'dias_bloqueados'];
                    
                    if (in_array($section, $allowedSections)) {
                        // IMPORTANTE: Pasamos 'get_defined_vars()' para que las variables del controlador ($turnos, $dias, etc.) 
                        // lleguen a la vista hija. CodeIgniter view() a veces aísla el alcance.
                        echo view('admin/sections/' . $section, get_defined_vars());
                    } else {
                        // Si la sección no es válida, por defecto cargamos turnos (y necesitamos sus variables por defecto si falla)
                        echo view('admin/sections/turnos', get_defined_vars()); 
                    }
                } else {
                    echo view('admin/sections/turnos', get_defined_vars()); 
                }
            ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>