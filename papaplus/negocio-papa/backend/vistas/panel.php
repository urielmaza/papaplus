<?php
require_once('../controladores/controladorAutenticacion.php');
$controladorAutenticacion = new ControladorAutenticacion();

if (!$controladorAutenticacion->verificarSesion()) {
    header("Location: login.php");  // Redirige a login si no está logueado
    exit();
}

// Obtener las órdenes del usuario
require_once('../controladores/controladorOrden.php');
$controladorOrden = new ControladorOrden();
$user_id = $_SESSION['user_id'];
$ordenes = json_decode($controladorOrden->obtenerOrdenes($user_id), true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario - Negocio Papas</title>
    <link rel="stylesheet" href="../frontend/estilos.css">
</head>
<body>
    <h2>Bienvenido al Panel de Usuario</h2>
    <h3>Órdenes:</h3>
    <ul>
        <?php foreach ($ordenes as $orden) { ?>
            <li>Orden ID: <?php echo $orden['id']; ?> - Total: $<?php echo $orden['total']; ?></li>
        <?php } ?>
    </ul>

    <a href="cerrar_sesion.php">Cerrar sesión</a>
</body>
</html>
