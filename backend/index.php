<?php

// Iniciar sesión para poder gestionar las sesiones del usuario


// Incluir el archivo de configuración de la base de datos
require_once('./configuracion/base_de_datos.php');

// Incluir los controladores necesarios
class Usuario {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Verificar si el correo y la contraseña son correctos
    public function verificarUsuario($email, $password) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario; // Retornamos los datos del usuario si la autenticación es exitosa
        }
        return null; // Retornamos null si no es válido
    }
}

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

// Crear una instancia del controlador de autenticación
$controladorAutenticacion = new ControladorAutenticacion();

// Verificar si el usuario está autenticado
if ($controladorAutenticacion->verificarSesion()) {
    // Si el usuario ya está logueado, lo redirigimos al panel de usuario
    header("Location: ./vistas/panel.php");
    exit();
} else {
    // Si el usuario no está logueado, mostramos la página de login
    require_once('./vistas/login.php');
}
?>