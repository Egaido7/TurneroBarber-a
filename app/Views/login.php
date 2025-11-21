<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BarberShop Elite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="<?= base_url('src/Imagenes/leanbarber.png') ?>">
    
    <!-- SCRIPT DE ALPINE.JS AÑADIDO (para manejar el modal) -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    
    <script>
        tailwind.config = {
            theme: {
// ... (tu config de tailwind sin cambios) ...
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
</head>
<!-- AÑADIMOS x-data PARA MANEJAR EL ESTADO DEL MODAL -->
<body class="min-h-screen bg-gradient-to-br from-gray-800 to-gray-700 flex items-center justify-center p-4" x-data="{ modalOpen: false }">
    <div class="w-full max-w-md">
        <!-- Logo y Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white-600 rounded-full mb-4">
                <img src="<?= base_url('src/Imagenes/leanbarber.png') ?>" alt="" class="w-50 h-50 object-contain">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">LeanBarber</h1>
            <p class="text-gray-300">Panel de Administración</p>
        </div>

        <!-- Formulario de Login -->
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Iniciar Sesión</h2>
            
            <form action="<?= base_url('auth/login') ?>" method="POST" class="space-y-6">
                <?= csrf_field() // Siempre es buena idea añadir el token CSRF ?>
                <div>
                    <label for="usuario" class="block text-sm font-medium text-gray-700 mb-2">
                        Usuario
                    </label>
                    <div class="relative">
<!-- ... (input de usuario sin cambios) ... -->
                        <input 
                            type="text" 
                            id="usuario" 
                            name="usuario" 
                            required 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors"
                            placeholder="Ingresa tu usuario"
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
<!-- ... (input de contraseña sin cambios) ... -->
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors"
                            placeholder="Ingresa tu contraseña"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    
                    <!-- CAMBIO: De <a> a <button> para abrir el modal -->
                    <button 
                        type="button" 
                        @click="modalOpen = true"
                        class="text-sm text-sky-400 hover:text-sky-600 transition-colors cursor-pointer"
                    >
                        ¿Olvidaste tu contraseña?
                    </button>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-sky-400 hover:bg-sky-600 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2"
                >
                    <span>Iniciar Sesión</span>
                    <i data-lucide="log-in" class="h-5 w-5"></i>
                </button>
            </form>

            <!-- Mensaje de error (opcional, mostrar si existe) -->
            <?php if(session()->getFlashdata('error')): ?>
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md flex items-start space-x-2">
                <i data-lucide="alert-circle" class="h-5 w-5 text-red-600 mt-0.5"></i>
                <p class="text-sm text-red-600"><?= session()->getFlashdata('error') ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Mensaje de éxito (para cuando se cambia la contraseña) -->
            <?php if(session()->getFlashdata('success')): ?>
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-md flex items-start space-x-2">
                <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mt-0.5"></i>
                <p class="text-sm text-green-600"><?= session()->getFlashdata('success') ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 
      MODAL PARA CAMBIAR CONTRASEÑA (AÑADIDO)
    -->
    <div x-show="modalOpen" @keydown.escape.window="modalOpen = false" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4" @click.away="modalOpen = false">
            <h3 class="text-2xl font-bold mb-6">Restablecer Contraseña</h3>
            <form action="<?= site_url('login/procesar-olvido') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                
                <p class="text-sm text-gray-600">
                    Ingresa tu usuario y una nueva contraseña. Esta acción es inmediata.
                </p>

                <div>
                    <label for="modal_usuario" class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                    <input type="text" id="modal_usuario" name="usuario" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label for="nueva_password" class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                    <input type="password" id="nueva_password" name="nueva_password" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label for="confirmar_password" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                    <input type="password" id="confirmar_password" name="confirmar_password" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 rounded-lg transition-colors">Actualizar Contraseña</button>
                    <button type="button" @click="modalOpen = false" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-lg transition-colors">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Fin del Modal -->

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>