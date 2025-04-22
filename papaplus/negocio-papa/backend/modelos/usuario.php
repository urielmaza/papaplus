<?php
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
?>
