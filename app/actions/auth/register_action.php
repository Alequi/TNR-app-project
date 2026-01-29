<?php
session_start();

require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../validaciones.php';

$con= conectar();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $telefono = trim($_POST['telefono']);

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Validar datos
    
    $validarMail = validarMailCompleto($email);
    
    if(!$validarMail){
       
        $_SESSION['error_mail'] = "El correo electrónico no es válido.";
        header("Location: ../../../public/registro.php");
        exit();
    }

    try {
    
        $stmt = $con->prepare("INSERT INTO users (nombre, apellido, email, pass, telefono) VALUES (:nombre, :apellido, :email, :pass, :telefono)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellidos);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $password_hash);
        $stmt->bindParam(':telefono', $telefono);

        $stmt->execute();

        $_SESSION['registro_exitoso'] = "Registro completado con éxito. Ahora puedes iniciar sesión.";
        header("Location: ../../../public/login.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['error_mail'] = "El correo electrónico ya está registrado.";
        } else {
            $_SESSION['error_general'] = "Error al registrar el usuario. Por favor, inténtalo de nuevo.";
        }
        header("Location: ../../../public/registro.php");
        exit();
    }

    
}

