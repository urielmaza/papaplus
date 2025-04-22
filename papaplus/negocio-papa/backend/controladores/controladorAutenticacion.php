<?php
require_once('../configuracion/base_de_datos.php');
require_once('../modelos/usuario.php');
require_once('../vendor/autoload.php'); // Cargar la librería para JWT

use \Firebase\JWT\JWT;

class ControladorAutenticacion {

    private $key = 'secreto'; // Llave secreta para codificar los JWT

    public function login($email, $password) {
        $usuario = new Usuario($GLOBALS['pdo']);
        $datos_usuario = $usuario->getUsuarioPorEmail($email);

        if ($datos_usuario && password_verify($password, $datos_usuario['password'])) {
            // Generar el token JWT con los datos del usuario
            $payload = array(
                "id" => $datos_usuario['id'],
                "role_id" => $datos_usuario['role_id']
            );

            $jwt = JWT::encode($payload, $this->key);
            return json_encode(['token' => $jwt]);
        } else {
            http_response_code(401); // Código de error 401: No autorizado
            return json_encode(['message' => 'Credenciales inválidas']);
        }
    }
}
?>
