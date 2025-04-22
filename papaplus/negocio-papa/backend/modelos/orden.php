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
?>
