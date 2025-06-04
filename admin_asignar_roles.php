<?php
// admin_asignar_roles.php
session_start();
require_once '../includes/conexion.php';
require_once '../includes/funciones.php';

// Verificar si es administrador
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: ../index.php");
    exit;
}

// Obtener usuarios para asignar roles
try {
    $stmt = $conn->prepare("SELECT u.id, u.username, p.nombres, p.apellidos, r.nombre as rol 
                           FROM usuarios u
                           JOIN personas p ON u.persona_id = p.documento_id
                           JOIN roles r ON u.rol_id = r.id
                           ORDER BY p.nombres");
    $stmt->execute();
    $usuarios = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error al cargar usuarios: " . $e->getMessage();
}

// Obtener roles disponibles
try {
    $stmt = $conn->query("SELECT id, nombre FROM roles ORDER BY nombre");
    $roles = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error al cargar roles: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Roles - Institución Uniclaretiana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-50">
    <!-- Barra de navegación -->
    <?php include '../includes/admin_navbar.php'; ?>

    <!-- Contenido principal -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Asignar Roles a Usuarios</h1>
                <p class="mt-1 text-sm text-gray-600">Seleccione un usuario y asigne un nuevo rol</p>
            </div>

            <?php if(isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <form method="POST" action="procesos/asignar_rol_proceso.php" class="space-y-6">
                        <div>
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700">Usuario *</label>
                            <select id="usuario_id" name="usuario_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione un usuario...</option>
                                <?php foreach($usuarios as $usuario): ?>
                                <option value="<?= htmlspecialchars($usuario['id']) ?>">
                                    <?= htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos'] . ' (' . $usuario['username'] . ') - ' . $usuario['rol']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="rol_id" class="block text-sm font-medium text-gray-700">Nuevo Rol *</label>
                            <select id="rol_id" name="rol_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione un rol...</option>
                                <?php foreach($roles as $rol): ?>
                                <option value="<?= htmlspecialchars($rol['id']) ?>">
                                    <?= htmlspecialchars($rol['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                <i class="fas fa-user-shield mr-2"></i> Asignar Rol
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>