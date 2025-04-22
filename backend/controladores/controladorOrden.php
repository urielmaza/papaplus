<?php
require_once('../configuracion/base_de_datos.php');
require_once('../modelos/orden.php');

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
?>
