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
    <title>Registro - Institución Uniclaretiana</title>
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
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3 bg-gradient-to-b from-blue-500 to-purple-600 p-8 flex items-center">
                    <div class="text-white text-center">
                        <h1 class="text-3xl font-bold mb-2">Registro</h1>
                        <p class="opacity-90 mb-6">Únete a nuestra comunidad educativa</p>
                            <img src="./assets/img/logo.png" alt="Educación" class="w-full">
                        <p class="mt-6 text-sm opacity-90">¿Ya tienes una cuenta? <a href="index.php" class="font-bold hover:underline">Inicia sesión</a></p>
                    </div>
                </div>
                <div class="md:w-2/3 p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Crea tu cuenta</h2>
                    
                    <?php if(isset($_GET['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?= htmlspecialchars($_GET['error']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_GET['success'])): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <?= htmlspecialchars($_GET['success']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="procesos/registro_proceso.php" method="POST" id="registroForm">
                        <!-- Paso 1: Información Personal -->
                        <div id="paso1" class="paso">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Información Personal</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="tipo_documento" class="block text-gray-700 text-sm font-bold mb-2">Tipo de Documento *</label>
                                    <select id="tipo_documento" name="tipo_documento" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione...</option>
                                        <option value="1">Cédula de Ciudadanía</option>
                                        <option value="2">Tarjeta de Identidad</option>
                                        <option value="3">Cédula de Extranjería</option>
                                        <option value="4">Pasaporte</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="documento_id" class="block text-gray-700 text-sm font-bold mb-2">Número de Documento *</label>
                                    <input type="text" id="documento_id" name="documento_id" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="nombres" class="block text-gray-700 text-sm font-bold mb-2">Nombres *</label>
                                    <input type="text" id="nombres" name="nombres" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="apellidos" class="block text-gray-700 text-sm font-bold mb-2">Apellidos *</label>
                                    <input type="text" id="apellidos" name="apellidos" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="fecha_nacimiento" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Nacimiento *</label>
                                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="genero" class="block text-gray-700 text-sm font-bold mb-2">Género *</label>
                                    <select id="genero" name="genero" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione...</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                        <option value="OTRO">Otro</option>
                                        <option value="NO_ESPECIFICA">Prefiero no decir</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2">Teléfono/Celular *</label>
                                    <input type="tel" id="telefono" name="telefono" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Correo Electrónico *</label>
                                <input type="email" id="email" name="email" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="button" onclick="siguientePaso(2)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                                    Siguiente
                                </button>
                            </div>
                        </div>
                        
                        <!-- Paso 2: Datos Académicos -->
                        <div id="paso2" class="paso hidden">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Datos Académicos</h3>
                            
                            <div class="mb-4">
                                <label for="programa_id" class="block text-gray-700 text-sm font-bold mb-2">Programa Académico *</label>
                                <select id="programa_id" name="programa_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Seleccione su programa...</option>
                                    <?php
                                    require_once 'includes/conexion.php';
                                    try {
                                        $stmt = $conn->query("SELECT id, nombre FROM programas WHERE estado = 'activo' ORDER BY nombre");
                                        while($programa = $stmt->fetch()) {
                                            echo "<option value='{$programa['id']}'>{$programa['nombre']}</option>";
                                        }
                                    } catch(PDOException $e) {
                                        echo "<option value=''>Error al cargar programas</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="semestre" class="block text-gray-700 text-sm font-bold mb-2">Semestre *</label>
                                    <select id="semestre" name="semestre" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione...</option>
                                        <option value="1">1er Semestre</option>
                                        <option value="2">2do Semestre</option>
                                        <option value="3">3er Semestre</option>
                                        <option value="4">4to Semestre</option>
                                        <option value="5">5to Semestre</option>
                                        <option value="6">6to Semestre</option>
                                        <option value="7">7mo Semestre</option>
                                        <option value="8">8vo Semestre</option>
                                        <option value="9">9no Semestre</option>
                                        <option value="10">10mo Semestre</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="jornada" class="block text-gray-700 text-sm font-bold mb-2">Jornada *</label>
                                    <select id="jornada" name="jornada" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione...</option>
                                        <option value="mañana">Mañana</option>
                                        <option value="tarde">Tarde</option>
                                        <option value="noche">Noche</option>
                                        <option value="fin de semana">Fin de semana</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="modalidad" class="block text-gray-700 text-sm font-bold mb-2">Modalidad *</label>
                                    <select id="modalidad" name="modalidad" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione...</option>
                                        <option value="presencial">Presencial</option>
                                        <option value="virtual">Virtual</option>
                                        <option value="mixta">Mixta</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="fecha_ingreso" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Ingreso *</label>
                                <input type="date" id="fecha_ingreso" name="fecha_ingreso" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="flex justify-between mt-6">
                                <button type="button" onclick="anteriorPaso(1)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150">
                                    Anterior
                                </button>
                                <button type="button" onclick="siguientePaso(3)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                                    Siguiente
                                </button>
                            </div>
                        </div>
                        
                        <!-- Paso 3: Creación de Cuenta -->
                        <div id="paso3" class="paso hidden">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Creación de Cuenta</h3>
                            
                            <div class="mb-4">
                                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Nombre de Usuario *</label>
                                <input type="text" id="username" name="username" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Este será tu nombre para iniciar sesión</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña *</label>
                                    <input type="password" id="password" name="password" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirmar Contraseña *</label>
                                    <input type="password" id="confirm_password" name="confirm_password" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-yellow-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, un número y un carácter especial.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex items-center">
                                <input type="checkbox" id="terminos" name="terminos" required
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="terminos" class="ml-2 block text-sm text-gray-700">
                                    Acepto los <a href="#" class="text-blue-600 hover:text-blue-800">Términos y Condiciones</a> y la <a href="#" class="text-blue-600 hover:text-blue-800">Política de Privacidad</a>
                                </label>
                            </div>
                            
                            <div class="flex justify-between mt-6">
                                <button type="button" onclick="anteriorPaso(2)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150">
                                    Anterior
                                </button>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150">
                                    Registrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para manejar los pasos del formulario -->
    <script>
        function siguientePaso(paso) {
            document.getElementById(`paso${paso-1}`).classList.add('hidden');
            document.getElementById(`paso${paso}`).classList.remove('hidden');
        }
        
        function anteriorPaso(paso) {
            document.getElementById(`paso${paso+1}`).classList.add('hidden');
            document.getElementById(`paso${paso}`).classList.remove('hidden');
        }
        
        // Validación de contraseña
        document.getElementById('registroForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if(password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            // Validar fortaleza de contraseña
            const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if(!regex.test(password)) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>