<?php
// Iniciar sesión para poder gestionar las sesiones del usuario
session_start();

// Incluir el archivo de configuración de la base de datos
require_once('backend/configuracion/base_de_datos.php');

// Incluir los controladores necesarios
require_once('backend/controladores/controladorAutenticacion.php');

// Crear una instancia del controlador de autenticación
$controladorAutenticacion = new ControladorAutenticacion();

// Verificar si el usuario está autenticado
if ($controladorAutenticacion->verificarSesion()) {
    // Si el usuario ya está logueado, lo redirigimos al panel de usuario
    header("Location: backend/vistas/panel.php");
    exit();
} else {
    // Si el usuario no está logueado, mostramos la página de login
    require_once('backend/vistas/login.php');
}
?>