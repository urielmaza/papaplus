<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: panel.php");  // Si ya está logueado, lo redirigimos al panel
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../controladores/controladorAutenticacion.php');
    $controladorAutenticacion = new ControladorAutenticacion();
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($controladorAutenticacion->login($email, $password)) {
        header("Location: panel.php");  // Redirige a panel si el login es exitoso
    } else {
        $mensaje = "Correo o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Negocio Papas</title>
    <link rel="stylesheet" href="../frontend/estilos.css">
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Iniciar sesión</button>
    </form>

    <?php if (isset($mensaje)) { echo "<p>$mensaje</p>"; } ?>
</body>
</html>
