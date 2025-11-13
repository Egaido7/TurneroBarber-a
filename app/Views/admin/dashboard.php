<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - BarberShop Elite</title>
    <link rel="stylesheet" href="<?= base_url('src/output.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
</head>
<body class="bg-gray-50">
    <!-- Estructura flexbox principal para ocupar todo el alto de la pantalla -->
    <div class="flex h-screen">
        <!-- Sidebar Fijo -->
        <nav id="sidebar" class="w-64 bg-sky-800 text-white p-6 overflow-y-auto fixed md:relative h-screen md:h-auto md:flex md:flex-col hidden md:flex left-0 top-0 z-40">
            <div class="flex items-center space-x-2 mb-8">
                <i data-lucide="scissors" class="h-8 w-8 text-sky-300"></i>
                <span class="text-xl font-bold">BarberShop Elite</span>
            </div>

            <div class="space-y-2">
                <a href="<?= base_url('admin?section=turnos') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-sky-700 transition-colors <?= ($section ?? 'turnos') === 'turnos' ? 'bg-sky-700' : '' ?>">
                    <i data-lucide="calendar" class="h-5 w-5"></i>
                    <span>Calendario de Turnos</span>
                </a>

                <a href="<?= base_url('admin?section=peluqueros') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-sky-700 transition-colors <?= ($section ?? 'turnos') === 'peluqueros' ? 'bg-sky-700' : '' ?>">
                    <i data-lucide="users" class="h-5 w-5"></i>
                    <span>Gestión de Peluqueros</span>
                </a>

                <a href="<?= base_url('admin?section=servicios') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-sky-700 transition-colors <?= ($section ?? 'turnos') === 'servicios' ? 'bg-sky-700' : '' ?>">
                    <i data-lucide="scissors" class="h-5 w-5"></i>
                    <span>Gestión de Servicios</span>
                </a>

                <a href="<?= base_url('admin?section=precios') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-sky-700 transition-colors <?= ($section ?? 'turnos') === 'precios' ? 'bg-sky-700' : '' ?>">
                    <i data-lucide="dollar-sign" class="h-5 w-5"></i>
                    <span>Gestión de Precios</span>
                </a>

                <a href="<?= base_url('admin?section=estadisticas') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-sky-700 transition-colors <?= ($section ?? 'turnos') === 'estadisticas' ? 'bg-sky-700' : '' ?>">
                    <i data-lucide="bar-chart-3" class="h-5 w-5"></i>
                    <span>Estadísticas</span>
                </a>
            </div>

            <div class="mt-auto pt-6 border-t border-sky-700">
                <a href="<?= base_url('logout') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-sky-700 transition-colors">
                    <i data-lucide="log-out" class="h-5 w-5"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </nav>

        <!-- Overlay para mobile -->
        <div id="overlay" class="fixed inset-0 bg-black/50 hidden md:hidden z-30" onclick="toggleMobileSidebar()"></div>

        <!-- Mobile Menu Button -->
        <button onclick="toggleMobileSidebar()" class="md:hidden fixed top-4 left-4 z-50 bg-sky-600 text-white p-2 rounded-lg">
            <i data-lucide="menu" class="h-6 w-6"></i>
        </button>

        <!-- Main Content -->
        <!-- Agregado flex-1 y width completo para que el contenido ocupe el espacio restante -->
        <div class="flex-1 flex flex-col w-full md:w-auto overflow-hidden md:ml-0">
            <!-- Top Bar -->
            <div class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Panel de Administración</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Admin</span>
                    <div class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center text-white font-bold">A</div>
                </div>
            </div>

            <!-- Content Area -->
            <!-- Agregado w-full para ocupar todo el ancho disponible -->
            <div class="flex-1 overflow-auto w-full">
                <?php
                $section = isset($_GET['section']) ? $_GET['section'] : 'turnos';

                switch($section) {
                    case 'peluqueros':
                        include 'sections/peluqueros.php';
                        break;
                    case 'servicios':
                        include 'sections/servicios.php';
                        break;
                    case 'precios':
                        include 'sections/precios.php';
                        break;
                    case 'estadisticas':
                        include 'sections/estadisticas.php';
                        break;
                    case 'turnos':
                    default:
                        include 'sections/turnos.php';
                        break;
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('hidden');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>
