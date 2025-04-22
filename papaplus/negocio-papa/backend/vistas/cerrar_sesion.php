<?php
require_once('../controladores/controladorAutenticacion.php');
$controladorAutenticacion = new ControladorAutenticacion();
$controladorAutenticacion->cerrarSesion();
header("Location: login.php");
exit();
?>
