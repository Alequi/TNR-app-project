<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../helpers/auth.php';
login();

$con = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($telefono) || empty($email)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../../views/userProfile.php");
        exit();
    }

    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El formato del email no es válido.";
        header("Location: ../../views/userProfile.php");
        exit();
    }

    try {
        // Verificar si el email ya está en uso por otro usuario
        $sql_check = "SELECT id FROM users WHERE email = :email AND id != :user_id";
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->execute([
            ':email' => $email,
            ':user_id' => $user_id
        ]);

        if ($stmt_check->rowCount() > 0) {
            $_SESSION['error'] = "El email ya está en uso por otro usuario.";
            header("Location: ../../views/userProfile.php");
            exit();
        }

        // Actualizar información del usuario
        $sql = "UPDATE users 
                SET nombre = :nombre, 
                    apellido = :apellido, 
                    telefono = :telefono, 
                    email = :email 
                WHERE id = :user_id";
        
        $stmt = $con->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':telefono' => $telefono,
            ':email' => $email,
            ':user_id' => $user_id
        ]);

        // Actualizar la sesión
        $_SESSION['nombre'] = $nombre;
        $_SESSION['email'] = $email;

        $_SESSION['success'] = "Perfil actualizado correctamente.";
        header("Location: ../../views/userProfile.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al actualizar el perfil: " . $e->getMessage();
        header("Location: ../../views/userProfile.php");
        exit();
    }
} else {
    header("Location: ../../views/userProfile.php");
    exit();
}
?>
