<?php
// procesos/registro_proceso.php

require_once '../includes/conexion.php';
require_once '../includes/funciones.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y limpiar datos
    $tipo_documento_id = (int) limpiar_input($_POST['tipo_documento']);
    $documento_id = limpiar_input($_POST['documento_id']);
    $nombres = limpiar_input($_POST['nombres']);
    $apellidos = limpiar_input($_POST['apellidos']);
    $fecha_nacimiento = limpiar_input($_POST['fecha_nacimiento']);
    $genero = limpiar_input($_POST['genero']);
    $telefono = limpiar_input($_POST['telefono']);
    $email = limpiar_input($_POST['email']);
    $programa_id = (int) limpiar_input($_POST['programa_id']);
    $semestre = limpiar_input($_POST['semestre']);
    $jornada = limpiar_input($_POST['jornada']);
    $modalidad = limpiar_input($_POST['modalidad']);
    $fecha_ingreso = limpiar_input($_POST['fecha_ingreso']);
    $username = limpiar_input($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validaciones básicas
    $errores = [];

    if($password !== $confirm_password) {
        $errores[] = "Las contraseñas no coinciden";
    }

    if(!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial";
    }

    $stmt = $conn->prepare("SELECT documento_id FROM personas WHERE documento_id = :documento_id");
    $stmt->bindParam(':documento_id', $documento_id);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $errores[] = "El número de documento ya está registrado";
    }

    $stmt = $conn->prepare("SELECT email FROM personas WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $errores[] = "El correo electrónico ya está registrado";
    }

    $stmt = $conn->prepare("SELECT username FROM usuarios WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $errores[] = "El nombre de usuario ya está en uso";
    }

    if(!isset($_POST['terminos'])) {
        $errores[] = "Debe aceptar los términos y condiciones";
    }

    if(!empty($errores)) {
        header("Location: ../registro.php?error=" . urlencode(implode(", ", $errores)));
        exit;
    }

    // Iniciar transacción
    $conn->beginTransaction();

    try {
        // 1. Insertar en tabla personas
        $stmt = $conn->prepare("INSERT INTO personas (documento_id, tipo_documento_id, nombres, apellidos, fecha_nacimiento, genero, telefono, email, estado) 
                               VALUES (:documento_id, :tipo_documento_id, :nombres, :apellidos, :fecha_nacimiento, :genero, :telefono, :email, 'activo')");
        $stmt->bindParam(':documento_id', $documento_id);
        $stmt->bindParam(':tipo_documento_id', $tipo_documento_id);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // 2. Insertar en tabla estudiantes
        $codigo_estudiante = date('y') . substr($documento_id, -4);
        
        $stmt = $conn->prepare("INSERT INTO estudiantes (persona_id, programa_id, codigo_estudiante, semestre_actual, jornada, modalidad, fecha_ingreso, estado) 
                               VALUES (:persona_id, :programa_id, :codigo_estudiante, :semestre, :jornada, :modalidad, :fecha_ingreso, 'matriculado')");
        $stmt->bindParam(':persona_id', $documento_id);
        $stmt->bindParam(':programa_id', $programa_id);
        $stmt->bindParam(':codigo_estudiante', $codigo_estudiante);
        $stmt->bindParam(':semestre', $semestre);
        $stmt->bindParam(':jornada', $jornada);
        $stmt->bindParam(':modalidad', $modalidad);
        $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
        $stmt->execute();

        // 3. Insertar en tabla usuarios
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $rol_id = 4; // Rol de estudiante
        
        $stmt = $conn->prepare("INSERT INTO usuarios (persona_id, username, password_hash, rol_id) 
                               VALUES (:persona_id, :username, :password_hash, :rol_id)");
        $stmt->bindParam(':persona_id', $documento_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':rol_id', $rol_id);
        $stmt->execute();

        // Confirmar transacción
        $conn->commit();

        header("Location: ../registro.php?success=Registro completado con éxito. Por favor inicie sesión.");
        exit;

    } catch(PDOException $e) {
        $conn->rollBack();
        error_log("Error en registro: " . $e->getMessage());
        header("Location: ../registro.php?error=Ocurrió un error durante el registro. Por favor intente nuevamente. Error: " . $e->getMessage());
        exit;
    }
} else {
    header("Location: ../registro.php");
    exit;
}
?>