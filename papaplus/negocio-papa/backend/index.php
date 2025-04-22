<?php
require_once('./controladores/controladorAutenticacion.php');

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/login') {
    $data = json_decode(file_get_contents("php://input"));
    $email = $data->email ?? '';
    $password = $data->password ?? '';

    // Llamar al controlador de autenticación
    $controladorAutenticacion = new ControladorAutenticacion();
    echo $controladorAutenticacion->login($email, $password);
}
?>