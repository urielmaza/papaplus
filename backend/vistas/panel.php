<?php

class Orden {

    private $pdo;

    // Constructor recibe la conexión a la base de datos
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Crear una nueva orden en la base de datos
    public function crearOrden($user_id, $branch_id, $total, $discount) {
        try {
            // Preparamos la consulta SQL para insertar la orden
            $stmt = $this->pdo->prepare('INSERT INTO orders (user_id, branch_id, total, discount, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
            $stmt->execute([$user_id, $branch_id, $total, $discount]);

            // Retornamos el ID de la nueva orden creada
            return $this->pdo->lastInsertId(); 
        } catch (PDOException $e) {
            // Si ocurre un error, se captura y se devuelve un mensaje
            return null;
        }
    }

    // Obtener todas las órdenes de la base de datos
    public function obtenerTodasLasOrdenes() {
        try {
            // Preparamos la consulta para obtener todas las órdenes
            $stmt = $this->pdo->query('SELECT * FROM orders ORDER BY created_at DESC');
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retornamos todas las órdenes
        } catch (PDOException $e) {
            // Si ocurre un error, se captura y se devuelve un mensaje
            return null;
        }
    }

    // Obtener todas las órdenes de un usuario específico
    public function obtenerOrdenesPorUsuario($user_id) {
        try {
            // Preparamos la consulta para obtener las órdenes del usuario especificado
            $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retornamos las órdenes del usuario
        } catch (PDOException $e) {
            // Si ocurre un error, se captura y se devuelve un mensaje
            return null;
        }
    }

    // Obtener una orden por su ID
    public function obtenerOrdenPorId($order_id) {
        try {
            // Preparamos la consulta para obtener una orden específica por su ID
            $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = ?');
            $stmt->execute([$order_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC); // Retornamos la orden
        } catch (PDOException $e) {
            // Si ocurre un error, se captura y se devuelve un mensaje
            return null;
        }
    }

    // Actualizar el estado de una orden (por ejemplo, si está completada o en proceso)
    public function actualizarEstadoOrden($order_id, $estado) {
        try {
            // Preparamos la consulta para actualizar el estado de la orden
            $stmt = $this->pdo->prepare('UPDATE orders SET estado = ?, updated_at = NOW() WHERE id = ?');
            $stmt->execute([$estado, $order_id]);
            return true; // Si todo salió bien, retornamos verdadero
        } catch (PDOException $e) {
            // Si ocurre un error, se captura y se devuelve un mensaje
            return false;
        }
    }
}

class ControladorOrden {

    public function crearOrden($user_id, $branch_id, $total, $discount) {
        $orden = new Orden($GLOBALS['pdo']);
        $resultado = $orden->crearOrden($user_id, $branch_id, $total, $discount);
        if ($resultado) {
            return json_encode(['message' => 'Orden creada exitosamente', 'orden_id' => $resultado]);
        } else {
            http_response_code(500);
            return json_encode(['message' => 'Error al crear la orden']);
        }
    }

    public function obtenerOrdenes($user_id = null) {
        $orden = new Orden($GLOBALS['pdo']);
        if ($user_id) {
            $resultado = $orden->obtenerOrdenesPorUsuario($user_id);
        } else {
            $resultado = $orden->obtenerTodasLasOrdenes();
        }

        if ($resultado) {
            return json_encode($resultado);
        } else {
            return json_encode(['message' => 'No se encontraron órdenes']);
        }
    }
}

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
