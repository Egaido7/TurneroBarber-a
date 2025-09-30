<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BarberShop Elite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="<?= base_url('src/Imagenes/leanbarber.png') ?>">
     
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
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-800 to-gray-700 flex items-center justify-center p-4">
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
                <div>
                    <label for="usuario" class="block text-sm font-medium text-gray-700 mb-2">
                        Usuario
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
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
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
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
                    
                    <a href="#" class="text-sm text-sky-400 hover:text-sky-600 transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
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
            <div class="mt-4 p-4 bg-sky-50 border border-sky-200 rounded-md flex items-start space-x-2">
                <i data-lucide="alert-circle" class="h-5 w-5 text-sky-600 mt-0.5"></i>
                <p class="text-sm text-sky-600"><?= session()->getFlashdata('error') ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Link para volver al inicio -->
        <div class="text-center mt-6">
            <a href="<?= base_url('/') ?>" class="text-gray-300 hover:text-white transition-colors flex items-center justify-center space-x-2">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                <span>Volver al inicio</span>
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
