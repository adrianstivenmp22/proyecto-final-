<?php
// docente_panel.php
session_start();
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

// Verificar si es docente
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
    header("Location: index.php");
    exit;
}

// Procesar subida de material
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['material'])) {
    $asignatura_id = limpiar_input($_POST['asignatura_id']);
    $titulo = limpiar_input($_POST['titulo']);
    $descripcion = limpiar_input($_POST['descripcion']);
    
    // Validar archivo
    $archivo = $_FILES['material'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $extensionesPermitidas = ['pdf', 'docx', 'ppt', 'pptx', 'xlsx', 'jpg', 'png', 'zip'];
    
    if(in_array($extension, $extensionesPermitidas)) {
        $nombreArchivo = uniqid() . '.' . $extension;
        $rutaDestino = "assets/materiales/" . $nombreArchivo;
        
        if(move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            try {
                $stmt = $conn->prepare("INSERT INTO materiales (asignatura_id, docente_id, titulo, descripcion, ruta_archivo, fecha_subida) 
                                       VALUES (:asignatura_id, :docente_id, :titulo, :descripcion, :ruta_archivo, NOW())");
                $stmt->bindParam(':asignatura_id', $asignatura_id);
                $stmt->bindParam(':docente_id', $_SESSION['usuario_id']);
                $stmt->bindParam(':titulo', $titulo);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':ruta_archivo', $rutaDestino);
                $stmt->execute();
                
                $mensaje_exito = "Material subido correctamente";
            } catch(PDOException $e) {
                $error = "Error al registrar el material en la base de datos";
            }
        } else {
            $error = "Error al subir el archivo";
        }
    } else {
        $error = "Formato de archivo no permitido";
    }
}

// Obtener asignaturas que imparte el docente
try {
    $stmt = $conn->prepare("SELECT a.id, a.nombre 
                           FROM asignaturas a
                           JOIN cursos_matriculados c ON a.id = c.asignatura_id
                           WHERE c.docente_id = :docente_id
                           GROUP BY a.id");
    $stmt->bindParam(':docente_id', $_SESSION['usuario_id']);
    $stmt->execute();
    $asignaturas = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error al cargar asignaturas";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente - Institución Uniclaretiana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-50">
    <!-- Barra de navegación -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img class="h-8 w-auto" src="assets/images/logo-uniclaretiana.svg" alt="Logo">
                    <div class="hidden md:block ml-6">
                        <div class="flex space-x-4">
                            <a href="docente_panel.php" class="text-blue-600 px-3 py-2 font-medium">Inicio</a>
                            <a href="docente_materiales.php" class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium">Mis Materiales</a>
                            <a href="docente_asignaturas.php" class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium">Asignaturas</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="relative ml-3">
                        <div class="flex items-center">
                            <span class="mr-2 text-sm font-medium text-gray-700"><?= htmlspecialchars($_SESSION['nombres']) ?></span>
                            <img class="h-8 w-8 rounded-full" src="<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'assets/images/default-avatar.jpg') ?>" alt="Perfil">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Subir Material Educativo</h1>
                <p class="mt-1 text-sm text-gray-600">Complete el formulario para compartir material con sus estudiantes</p>
            </div>

            <?php if(isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <?php if(isset($mensaje_exito)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?= htmlspecialchars($mensaje_exito) ?>
            </div>
            <?php endif; ?>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <form action="docente_panel.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="asignatura_id" class="block text-sm font-medium text-gray-700">Asignatura *</label>
                                <select id="asignatura_id" name="asignatura_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccione una asignatura</option>
                                    <?php foreach($asignaturas as $asignatura): ?>
                                    <option value="<?= htmlspecialchars($asignatura['id']) ?>"><?= htmlspecialchars($asignatura['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="titulo" class="block text-sm font-medium text-gray-700">Título del Material *</label>
                                <input type="text" id="titulo" name="titulo" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="material" class="block text-sm font-medium text-gray-700">Archivo *</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" id="material" name="material" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Formatos permitidos: PDF, DOCX, PPT, PPTX, XLSX, JPG, PNG, ZIP (Tamaño máximo: 10MB)</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-upload mr-2"></i> Subir Material
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-10">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; <?= date('Y') ?> Institución Uniclaretiana. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>