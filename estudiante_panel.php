<?php
// estudiante_panel.php
session_start();
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

// Verificar si es estudiante
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 4) {
    header("Location: index.php");
    exit;
}

// Procesar envío de tarea
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_tarea'])) {
    $tarea_id = limpiar_input($_POST['tarea_id']);
    $comentarios = limpiar_input($_POST['comentarios']);
    
    // Validar archivo
    $archivo = $_FILES['archivo_tarea'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $extensionesPermitidas = ['pdf', 'docx', 'ppt', 'pptx', 'zip', 'rar'];
    
    if(in_array($extension, $extensionesPermitidas)) {
        $nombreArchivo = 'tarea_' . $_SESSION['documento_id'] . '_' . $tarea_id . '_' . time() . '.' . $extension;
        $rutaDestino = "assets/tareas_entregadas/" . $nombreArchivo;
        
        if(move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            try {
                $conn->beginTransaction();
                
                // Registrar entrega
                $stmt = $conn->prepare("INSERT INTO entregas_tareas (tarea_id, estudiante_id, ruta_archivo, comentarios, fecha_entrega) 
                                       VALUES (:tarea_id, :estudiante_id, :ruta_archivo, :comentarios, NOW())");
                $stmt->bindParam(':tarea_id', $tarea_id);
                $stmt->bindParam(':estudiante_id', $_SESSION['usuario_id']);
                $stmt->bindParam(':ruta_archivo', $rutaDestino);
                $stmt->bindParam(':comentarios', $comentarios);
                $stmt->execute();
                
                // Actualizar estado de la tarea
                $stmt = $conn->prepare("UPDATE tareas_estudiantes 
                                       SET estado = 'entregada', fecha_entrega = NOW() 
                                       WHERE tarea_id = :tarea_id AND estudiante_id = :estudiante_id");
                $stmt->bindParam(':tarea_id', $tarea_id);
                $stmt->bindParam(':estudiante_id', $_SESSION['usuario_id']);
                $stmt->execute();
                
                $conn->commit();
                $mensaje_exito = "Tarea enviada correctamente";
            } catch(PDOException $e) {
                $conn->rollBack();
                $error = "Error al registrar la entrega: " . $e->getMessage();
            }
        } else {
            $error = "Error al subir el archivo";
        }
    } else {
        $error = "Formato de archivo no permitido. Use PDF, DOCX, PPT, ZIP o RAR";
    }
}

// Obtener tareas pendientes del estudiante
try {
    $stmt = $conn->prepare("SELECT t.id, t.titulo, t.descripcion, t.fecha_limite, a.nombre as asignatura 
                           FROM tareas t
                           JOIN asignaturas a ON t.asignatura_id = a.id
                           JOIN cursos_matriculados c ON a.id = c.asignatura_id
                           WHERE c.estudiante_id = :estudiante_id
                           AND t.id NOT IN (
                               SELECT tarea_id FROM entregas_tareas WHERE estudiante_id = :estudiante_id
                           )
                           AND t.fecha_limite > NOW()");
    $stmt->bindParam(':estudiante_id', $_SESSION['usuario_id']);
    $stmt->execute();
    $tareas_pendientes = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error al cargar tareas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Estudiante - Institución Uniclaretiana</title>
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
                            <a href="estudiante_panel.php" class="text-blue-600 px-3 py-2 font-medium">Inicio</a>
                            <a href="estudiante_tareas.php" class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium">Mis Tareas</a>
                            <a href="estudiante_asignaturas.php" class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium">Asignaturas</a>
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
                <h1 class="text-2xl font-bold text-gray-900">Enviar Tarea</h1>
                <p class="mt-1 text-sm text-gray-600">Selecciona una tarea pendiente y sube tu archivo de entrega</p>
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
                    <?php if(!empty($tareas_pendientes)): ?>
                    <form action="estudiante_panel.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div>
                            <label for="tarea_id" class="block text-sm font-medium text-gray-700">Tarea *</label>
                            <select id="tarea_id" name="tarea_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione una tarea</option>
                                <?php foreach($tareas_pendientes as $tarea): ?>
                                <option value="<?= htmlspecialchars($tarea['id']) ?>">
                                    <?= htmlspecialchars($tarea['asignatura']) ?> - <?= htmlspecialchars($tarea['titulo']) ?> 
                                    (Entrega: <?= date('d/m/Y', strtotime($tarea['fecha_limite'])) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="comentarios" class="block text-sm font-medium text-gray-700">Comentarios (opcional)</label>
                            <textarea id="comentarios" name="comentarios" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="archivo_tarea" class="block text-sm font-medium text-gray-700">Archivo de Tarea *</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" id="archivo_tarea" name="archivo_tarea" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Formatos permitidos: PDF, DOCX, PPT, PPTX, ZIP, RAR (Tamaño máximo: 10MB)</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-paper-plane mr-2"></i> Enviar Tarea
                            </button>
                        </div>
                    </form>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">No tienes tareas pendientes</h3>
                        <p class="mt-1 text-sm text-gray-500">Actualmente no hay tareas asignadas o ya has entregado todas.</p>
                    </div>
                    <?php endif; ?>
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