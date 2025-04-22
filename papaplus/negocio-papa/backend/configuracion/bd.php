<?php

$host = 'localhost';            // Dirección del servidor de base de datos
$dbname = 'negocio_papas';      // Nombre de la base de datos
$username = 'root';             // Nombre de usuario para la base de datos
$password = '';                 // Contraseña para la base de datos

try {
    // Crear una nueva conexión PDO a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurar el PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si ocurre un error, mostrar el mensaje
    die("Error de conexión: " . $e->getMessage());
}
?>