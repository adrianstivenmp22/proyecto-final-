<?php
// admin_panel.php

session_start();

// Verificar si es administrador
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: index.php");
    exit;
}

require_once 'includes/conexion.php';

// Obtener estadísticas para el panel
try {
    $stats = [];
    
    // Total estudiantes
    $stmt = $conn->query("SELECT COUNT(*) as total FROM estudiantes");
    $stats['estudiantes'] = $stmt->fetch()['total'];
    
    // Total docentes
    $stmt = $conn->query("SELECT COUNT(*) as total FROM docentes");
    $stats['docentes'] = $stmt->fetch()['total'];
    
    // Total programas
    $stmt = $conn->query("SELECT COUNT(*) as total FROM programas WHERE estado = 'activo'");
    $stats['programas'] = $stmt->fetch()['total'];
    
    // Últimos registros
    $stmt = $conn->query("SELECT p.documento_id, p.nombres, p.apellidos, p.email, p.fecha_registro 
                         FROM personas p 
                         JOIN usuarios u ON p.documento_id = u.persona_id 
                         ORDER BY p.fecha_registro DESC LIMIT 5");
    $ultimos_registros = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error al cargar estadísticas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Institución Uniclaretiana</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-100">
    <!-- Barra de navegación -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="assets/images/logo-uniclaretiana.svg" alt="Logo Uniclaretiana">
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="admin_panel.php" class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Panel
                        </a>
                        <a href="admin_usuarios.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Usuarios
                        </a>
                        <a href="admin_programas.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Programas
                        </a>
                        <a href="admin_reportes.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Reportes
                        </a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="ml-3 relative">
                        <div>
                            <button type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Abrir menú usuario</span>
                                <img class="h-8 w-8 rounded-full" src="<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'assets/images/default-avatar.jpg') ?>" alt="Foto perfil">
                            </button>
                        </div>
                        <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" id="user-menu">
                            <a href="perfil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Tu perfil</a>
                            <a href="procesos/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Cerrar sesión</a>
                        </div>
                    </div>
                </div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Abrir menú principal</span>
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú móvil -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="admin_panel.php" class="bg-blue-50 border-blue-500 text-blue-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Panel</a>
                <a href="admin_usuarios.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Usuarios</a>
                <a href="admin_programas.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Programas</a>
                <a href="admin_reportes.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Reportes</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'assets/images/default-avatar.jpg') ?>" alt="Foto perfil">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800"><?= htmlspecialchars($_SESSION['nombres'] . ' ' . $_SESSION['apellidos']) ?></div>
                        <div class="text-sm font-medium text-gray-500">Administrador</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="perfil.php" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Tu perfil</a>
                    <a href="procesos/logout.php" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Panel de Administración</h1>
                <p class="mt-2 text-sm text-gray-600">Bienvenido, <?= htmlspecialchars($_SESSION['nombres']) ?>. Aquí puedes gestionar toda la plataforma.</p>
            </div>
            
            <?php if(isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Estudiantes</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900"><?= htmlspecialchars($stats['estudiantes'] ?? 0) ?></div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="admin_usuarios.php?rol=estudiante" class="font-medium text-blue-600 hover:text-blue-500">Ver todos</a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Docentes</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900"><?= htmlspecialchars($stats['docentes'] ?? 0) ?></div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="admin_usuarios.php?rol=docente" class="font-medium text-blue-600 hover:text-blue-500">Ver todos</a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <i class="fas fa-graduation-cap text-white text-xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Programas</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900"><?= htmlspecialchars($stats['programas'] ?? 0) ?></div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="admin_programas.php" class="font-medium text-blue-600 hover:text-blue-500">Ver todos</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Últimos registros -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Últimos registros</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($ultimos_registros as $registro): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($registro['nombres'] . ' ' . $registro['apellidos']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($registro['documento_id']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($registro['email']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($registro['fecha_registro'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="admin_usuarios.php" class="font-medium text-blue-600 hover:text-blue-500">Ver todos los usuarios</a>
                    </div>
                </div>
            </div>
            
            <!-- Acciones rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="admin_nuevo_usuario.php" class="bg-white shadow rounded-lg p-6 flex flex-col items-center justify-center hover:bg-blue-50 transition duration-150">
                    <div class="bg-blue-100 p-3 rounded-full mb-3">
                        <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Nuevo Usuario</h3>
                </a>
                
                <a href="admin_nuevo_programa.php" class="bg-white shadow rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-50 transition duration-150">
                    <div class="bg-green-100 p-3 rounded-full mb-3">
                        <i class="fas fa-book text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Nuevo Programa</h3>
                </a>
                
                <a href="admin_asignar_roles.php" class="bg-white shadow rounded-lg p-6 flex flex-col items-center justify-center hover:bg-purple-50 transition duration-150">
                    <div class="bg-purple-100 p-3 rounded-full mb-3">
                        <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Asignar Roles</h3>
                </a>
                
                <a href="admin_reportes.php" class="bg-white shadow rounded-lg p-6 flex flex-col items-center justify-center hover:bg-yellow-50 transition duration-150">
                    <div class="bg-yellow-100 p-3 rounded-full mb-3">
                        <i class="fas fa-chart-bar text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Generar Reportes</h3>
                </a>
            </div>
        </div>
    </main>

    <!-- Pie de página -->
    <footer class="bg-white border-t border-gray-200 mt-10">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; <?= date('Y') ?> Institución Uniclaretiana. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts personalizados -->
    <script src="assets/js/main.js"></script>
    <script>
        // Toggle del menú de usuario
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>