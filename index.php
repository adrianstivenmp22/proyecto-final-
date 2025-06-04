<?php
session_start();
if(isset($_SESSION['usuario_id'])) {
    header("Location: bienvenido.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Institución Uniclaretiana</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gradient-to-r from-blue-50 to-purple-50 min-h-screen flex items-center">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl my-10">
            <div class="md:flex">
                <div class="md:w-1/2 bg-gradient-to-b from-blue-500 to-purple-600 p-8 flex items-center">
                    <div class="text-white">
                        <h1 class="text-3xl font-bold mb-2">Institución Uniclaretiana</h1>
                        <p class="opacity-90">Sistema de gestión académica</p>
                        <div class="mt-10 hidden md:block">
                            <img src="./assets/img/logo.png" alt="Educación" class="w-full">
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2 p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Iniciar Sesión</h2>
                    
                    <?php if(isset($_GET['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?= htmlspecialchars($_GET['error']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="procesos/login_proceso.php" method="POST">
                        <div class="mb-4">
                            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Usuario</label>
                            <input type="text" id="username" name="username" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                            <input type="password" id="password" name="password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">Recordarme</label>
                            </div>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">¿Olvidó su contraseña?</a>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                            Ingresar
                        </button>
                    </form>
                    <div class="mt-4 text-center">
                        <p class="text-gray-600">¿No tienes una cuenta? <a href="registro.php" class="text-blue-600 hover:text-blue-800">Regístrate</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>