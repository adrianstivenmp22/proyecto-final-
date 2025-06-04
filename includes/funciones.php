<?php
// includes/funciones.php

function limpiar_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generarCodigoEstudiante($documento_id) {
    return date('y') . substr($documento_id, -4);
}

function esta_logueado() {
    return isset($_SESSION['usuario_id']);
}

function es_admin() {
    return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1;
}

function redirigir_si_no_logueado() {
    if(!esta_logueado()) {
        header("Location: index.php");
        exit;
    }
}

function redirigir_si_no_admin() {
    redirigir_si_no_logueado();
    if(!es_admin()) {
        header("Location: bienvenido.php");
        exit;
    }
}
// includes/funciones.php

function es_administrador() {
    return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1;
}

function redirigir_si_no_administrador() {
    if(!es_administrador()) {
        header("Location: index.php");
        exit;
    }
}
// includes/funciones.php (agregar estas funciones)

function es_docente() {
    return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 3;
}

function redirigir_si_no_docente() {
    if(!es_docente()) {
        header("Location: index.php");
        exit;
    }
}

function obtener_extension_archivo($nombreArchivo) {
    return strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
}
// includes/funciones.php (agregar estas funciones)

function es_estudiante() {
    return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 4;
}

function redirigir_si_no_estudiante() {
    if(!es_estudiante()) {
        header("Location: index.php");
        exit;
    }
}

function formatear_fecha($fecha, $formato = 'd/m/Y') {
    return date($formato, strtotime($fecha));
}
?>