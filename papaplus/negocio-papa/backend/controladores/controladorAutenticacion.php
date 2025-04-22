<?php
require_once('../configuracion/base_de_datos.php');
require_once('../modelos/usuario.php');

class ControladorAutenticacion {

    // Función para realizar el login
    public function login($email, $password) {
        $usuario = new Usuario($GLOBALS['pdo']);
        $resultado = $usuario->verificarUsuario($email, $password);

        if ($resultado) {
            // Aquí podrías generar un token JWT o una sesión de usuario
            session_start();
            $_SESSION['user_id'] = $resultado['id'];  // Guardamos el id del usuario en sesión
            return true;
        } else {
            return false;  // Si las credenciales no son correctas
        }
    }

    // Función para verificar si el usuario está logueado
    public function verificarSesion() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }

    // Función para cerrar sesión
    public function cerrarSesion() {
        session_start();
        session_unset();
        session_destroy();
        return true;
    }
}
?>
