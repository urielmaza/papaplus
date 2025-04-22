<?php
// Configuración de base de datos
$host = 'localhost';
$dbname = 'papaplus';
$username = 'papaplus';  // Cambia por tu usuario de base de datos
$password = 'n-Fohq8/vVYyvKPL';      // Cambia por tu contraseña de base de datos

try {
    // Establecemos la conexión con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
    // Si hay un error en la conexión, mostramos el mensaje
    die("No se pudo conectar a la base de datos: " . $e->getMessage());
}
?>