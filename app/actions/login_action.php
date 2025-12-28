<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';

$con= conectar();

$email = $_POST["email"];
$password = $_POST["password"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $con->prepare($sql);
        $stmt -> execute([
            ":email" => $email
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($usuario && password_verify($password, $usuario['pass'])) {
            $_SESSION['user_id'] = $usuario['id_user'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['nombre'] = $usuario['nombre'];

            if ($_SESSION['rol'] === 'admin') {
                header("Location: ../../views/panelAdmin/adminDashboard.php");
            } else {
                header("Location: ../../views/panelUsuario/userDashboard.php");
            }
        } else {
            echo "Credenciales incorrectas.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}else{
    header("Location: ../public/login.php");
    exit();
}
?>