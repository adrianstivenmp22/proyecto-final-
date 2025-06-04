<?php
// procesos/login_proceso.php

session_start();
require_once '../includes/conexion.php';
require_once '../includes/funciones.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = limpiar_input($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    try {
        // Buscar usuario incluyendo información de rol
        $stmt = $conn->prepare("SELECT u.id, u.username, u.password_hash, u.rol_id, r.nombre as rol, 
                               p.nombres, p.apellidos, p.documento_id, p.foto_perfil 
                               FROM usuarios u 
                               JOIN personas p ON u.persona_id = p.documento_id 
                               JOIN roles r ON u.rol_id = r.id
                               WHERE u.username = :username AND u.activo = 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if($stmt->rowCount() === 1) {
            $usuario = $stmt->fetch();
            
            // Verificar contraseña
            if(password_verify($password, $usuario['password_hash'])) {
                // Crear sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['documento_id'] = $usuario['documento_id'];
                $_SESSION['username'] = $usuario['username'];
                $_SESSION['nombres'] = $usuario['nombres'];
                $_SESSION['apellidos'] = $usuario['apellidos'];
                $_SESSION['rol_id'] = $usuario['rol_id'];
                $_SESSION['rol_nombre'] = $usuario['rol'];
                $_SESSION['foto_perfil'] = $usuario['foto_perfil'];
                
                // Recordar usuario si es necesario
                if($remember) {
                    $cookie_value = $usuario['id'] . ':' . hash('sha256', $usuario['password_hash']);
                    setcookie('remember_uniclaretiana', $cookie_value, time() + (86400 * 30), "/");
                }
                            
                            // Redirigir según rol
            // Redirigir según rol
                        if($usuario['rol_id'] == 1) { // Administrador
                header("Location: ../admin_panel.php");
            } elseif($usuario['rol_id'] == 3) { // Docente
                header("Location: ../docente_panel.php");
            } else { // Estudiante
                header("Location: ../estudiante_panel.php");
            }
            exit;
            }
        }
        
        // Si llega aquí es porque falló la autenticación
        header("Location: ../index.php?error=Usuario o contraseña incorrectos");
        exit;
        
    } catch(PDOException $e) {
        error_log("Error en login: " . $e->getMessage());
        header("Location: ../index.php?error=Error en el sistema. Por favor intente más tarde.");
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>