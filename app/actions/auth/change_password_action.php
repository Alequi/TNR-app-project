<?php
session_start();
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../helpers/auth.php';
login();

$con = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar que los campos no estén vacíos
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../../../views/userProfile.php");
        exit();
    }

    // Validar que las nuevas contraseñas coincidan
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Las nuevas contraseñas no coinciden.";
        header("Location: ../../../views/userProfile.php");
        exit();
    }

    // Validar longitud mínima de la nueva contraseña
    if (strlen($new_password) < 6) {
        $_SESSION['error'] = "La nueva contraseña debe tener al menos 6 caracteres.";
        header("Location: ../../../views/userProfile.php");
        exit();
    }

    try {
        // Obtener la contraseña actual del usuario
        $sql = "SELECT pass FROM users WHERE id = :user_id";
        $stmt = $con->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar que la contraseña actual sea correcta
        if (!password_verify($current_password, $user['pass'])) {
            $_SESSION['error'] = "La contraseña actual es incorrecta.";
            header("Location: ../../../views/userProfile.php");
            exit();
        }

        // Hashear la nueva contraseña
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Actualizar la contraseña
        $sql_update = "UPDATE users SET pass = :pass WHERE id = :user_id";
        $stmt_update = $con->prepare($sql_update);
        $stmt_update->execute([
            ':pass' => $hashed_password,
            ':user_id' => $user_id
        ]);

        $_SESSION['success'] = "Contraseña cambiada correctamente.";
        header("Location: ../../../views/userProfile.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al cambiar la contraseña: " . $e->getMessage();
        header("Location: ../../../views/userProfile.php");
        exit();
    }
} else {
    header("Location: ../../../views/userProfile.php");
    exit();
}
?>
