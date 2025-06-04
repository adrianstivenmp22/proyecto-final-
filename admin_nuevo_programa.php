<?php
// admin_nuevo_programa.php
session_start();
require_once '../includes/conexion.php';
require_once '../includes/funciones.php';

// Verificar si es administrador
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: ../index.php");
    exit;
}

// Procesar formulario
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aquí iría el código para procesar el nuevo programa
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Programa - Institución Uniclaretiana</title>
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
                <h1 class="text-2xl font-bold text-gray-900">Crear Nuevo Programa Académico</h1>
                <p class="mt-1 text-sm text-gray-600">Complete el formulario para registrar un nuevo programa en el sistema</p>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <form method="POST" class="space-y-6">
                        <div>
                            <label for="codigo" class="block text-sm font-medium text-gray-700">Código del Programa *</label>
                            <input type="text" id="codigo" name="codigo" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Programa *</label>
                            <input type="text" id="nombre" name="nombre" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="duracion" class="block text-sm font-medium text-gray-700">Duración (semestres) *</label>
                                <input type="number" id="duracion" name="duracion" min="1" max="12" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="nivel" class="block text-sm font-medium text-gray-700">Nivel Académico *</label>
                                <select id="nivel" name="nivel" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccione...</option>
                                    <option value="tecnico">Técnico</option>
                                    <option value="tecnologo">Tecnólogo</option>
                                    <option value="pregrado">Pregrado</option>
                                    <option value="posgrado">Posgrado</option>
                                </select>
                            </div>
                            <div>
                                <label for="facultad" class="block text-sm font-medium text-gray-700">Facultad *</label>
                                <input type="text" id="facultad" name="facultad" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-save mr-2"></i> Guardar Programa
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