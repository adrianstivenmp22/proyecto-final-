<?php
session_start();

if(!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'includes/conexion.php';

// Obtener información del usuario
try {
    $stmt = $conn->prepare("SELECT p.*, u.username, r.nombre as rol 
                           FROM personas p 
                           JOIN usuarios u ON p.documento_id = u.persona_id 
                           JOIN roles r ON u.rol_id = r.id 
                           WHERE u.id = :usuario_id");
    $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
    $stmt->execute();
    $usuario = $stmt->fetch();
    
    // Si es estudiante, obtener información académica
    if($_SESSION['rol_id'] == 4) { // Rol de estudiante
        $stmt = $conn->prepare("SELECT e.*, p.nombre as programa 
                               FROM estudiantes e 
                               JOIN programas p ON e.programa_id = p.id 
                               WHERE e.persona_id = :documento_id");
        $stmt->bindParam(':documento_id', $_SESSION['documento_id']);
        $stmt->execute();
        $estudiante = $stmt->fetch();
    }
} catch(PDOException $e) {
    // Manejar error
    $error = "Error al cargar información del usuario";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Institución Uniclaretiana</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-50">
    <!-- Barra de navegación -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="assets/images/logo-uniclaretiana.svg" alt="Logo Uniclaretiana">
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="#" class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Inicio
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Perfil
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Académico
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Notas
                        </a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="ml-3 relative">
                        <div>
                            <button type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Abrir menú usuario</span>
                                <img class="h-8 w-8 rounded-full" src="<?= isset($usuario['foto_perfil']) ? htmlspecialchars($usuario['foto_perfil']) : 'assets/images/default-avatar.jpg' ?>" alt="Foto perfil">
                            </button>
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
        <div class="sm:hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="#" class="bg-blue-50 border-blue-500 text-blue-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Inicio</a>
                <a href="#" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Perfil</a>
                <a href="#" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Académico</a>
                <a href="#" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Notas</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="<?= isset($usuario['foto_perfil']) ? htmlspecialchars($usuario['foto_perfil']) : 'assets/images/default-avatar.jpg' ?>" alt="Foto perfil">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800"><?= htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']) ?></div>
                        <div class="text-sm font-medium text-gray-500"><?= htmlspecialchars($usuario['email']) ?></div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Tu perfil</a>
                    <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Configuración</a>
                    <a href="procesos/logout.php" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Panel lateral -->
                <div class="w-full md:w-1/4">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-600 to-purple-600">
                            <h3 class="text-lg font-medium leading-6 text-white">Panel de Usuario</h3>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                            <div class="text-center py-4">
                                <img class="mx-auto h-24 w-24 rounded-full border-4 border-white shadow" src="<?= isset($usuario['foto_perfil']) ? htmlspecialchars($usuario['foto_perfil']) : 'assets/images/default-avatar.jpg' ?>" alt="Foto perfil">
                                <h3 class="mt-2 text-lg font-medium text-gray-900"><?= htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']) ?></h3>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($usuario['rol']) ?></p>
                                
                                <?php if(isset($estudiante)): ?>
                                <div class="mt-4 text-left px-4">
                                    <p class="text-sm text-gray-500"><span class="font-medium">Programa:</span> <?= htmlspecialchars($estudiante['programa']) ?></p>
                                    <p class="text-sm text-gray-500"><span class="font-medium">Código:</span> <?= htmlspecialchars($estudiante['codigo_estudiante']) ?></p>
                                    <p class="text-sm text-gray-500"><span class="font-medium">Semestre:</span> <?= htmlspecialchars($estudiante['semestre_actual']) ?></p>
                                    <p class="text-sm text-gray-500"><span class="font-medium">Jornada:</span> <?= ucfirst(htmlspecialchars($estudiante['jornada'])) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="px-4 py-4 bg-gray-50">
                            <a href="procesos/logout.php" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                            </a>
                        </div>
                    </div>
                    
                    <!-- Menú rápido -->
                    <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Accesos rápidos</h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <ul class="divide-y divide-gray-200">
                                <li>
                                    <a href="#" class="block px-4 py-4 hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                                </div>
                                                <div class="min-w-0 flex-1 px-4">
                                                    <p class="text-sm font-medium text-gray-900 truncate">Horario de clases</p>
                                                </div>
                                            </div>
                                            <div>
                                                <i class="fas fa-chevron-right text-gray-400"></i>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-4 hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-book text-green-600"></i>
                                                </div>
                                                <div class="min-w-0 flex-1 px-4">
                                                    <p class="text-sm font-medium text-gray-900 truncate">Mis materias</p>
                                                </div>
                                            </div>
                                            <div>
                                                <i class="fas fa-chevron-right text-gray-400"></i>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-4 hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-file-invoice-dollar text-purple-600"></i>
                                                </div>
                                                <div class="min-w-0 flex-1 px-4">
                                                    <p class="text-sm font-medium text-gray-900 truncate">Pagos y finanzas</p>
                                                </div>
                                            </div>
                                            <div>
                                                <i class="fas fa-chevron-right text-gray-400"></i>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-4 hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-envelope text-yellow-600"></i>
                                                </div>
                                                <div class="min-w-0 flex-1 px-4">
                                                    <p class="text-sm font-medium text-gray-900 truncate">Mensajes <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full">3</span></p>
                                                </div>
                                            </div>
                                            <div>
                                                <i class="fas fa-chevron-right text-gray-400"></i>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Contenido principal -->
                <div class="w-full md:w-3/4">
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-600 to-purple-600">
                            <h3 class="text-lg font-medium leading-6 text-white">Bienvenido, <?= htmlspecialchars($usuario['nombres']) ?></h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="alert alert-info bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            Bienvenido al sistema académico de la Institución Uniclaretiana. Aquí podrás gestionar toda tu información académica.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if(isset($estudiante)): ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                    <h4 class="text-sm font-medium text-blue-800 mb-1">Programa académico</h4>
                                    <p class="text-lg font-semibold text-blue-900"><?= htmlspecialchars($estudiante['programa']) ?></p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                    <h4 class="text-sm font-medium text-green-800 mb-1">Semestre actual</h4>
                                    <p class="text-lg font-semibold text-green-900"><?= htmlspecialchars($estudiante['semestre_actual']) ?></p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                                    <h4 class="text-sm font-medium text-purple-800 mb-1">Estado</h4>
                                    <p class="text-lg font-semibold text-purple-900"><?= ucfirst(htmlspecialchars($estudiante['estado'])) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Notificaciones recientes</h4>
                                <ul class="divide-y divide-gray-200">
                                    <li class="py-3">
                                        <div class="flex space-x-3">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-calendar-check text-green-500"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm text-gray-800">
                                                    <span class="font-medium">Inicio de clases:</span> El próximo lunes inicia el semestre académico 2023-2.
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">Hace 2 días</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3">
                                        <div class="flex space-x-3">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-book text-blue-500"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm text-gray-800">
                                                    <span class="font-medium">Nuevo material:</span> Se ha subido nuevo material para la asignatura de Matemáticas Discretas.
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">Hace 5 días</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3">
                                        <div class="flex space-x-3">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-user-graduate text-purple-500"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm text-gray-800">
                                                    <span class="font-medium">Bienvenida:</span> Te damos la bienvenida al sistema académico de la Institución Uniclaretiana.
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">Hace 1 semana</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Próximas actividades -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Próximas actividades</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asignatura</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividad</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrega</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15/03/2023</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Matemáticas Discretas</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Taller #2 - Lógica proposicional</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">23:59</td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">17/03/2023</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Programación I</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Proyecto parcial - Entrega 1</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">23:59</td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20/03/2023</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Bases de Datos</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Quiz #1 - Modelo relacional</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Durante la clase</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
</body>
</html>