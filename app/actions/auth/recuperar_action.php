<?php
session_start();

require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../validaciones.php';

$con = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $email = trim($_POST["email"]);

    $validarMail= validarMailCompleto($email);

    if(!$validarMail){


   $_SESSION['error_mail'] = "El correo electrónico no es válido.";
        header("Location: ../../../public/recuperar_pass.php");
        exit();
    }else{

        $sql =  "SELECT * FROM users WHERE  email = :email";
        $stmt = $con->prepare($sql);
        $stmt -> execute([
            ':email'  => $email
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if($usuario){
            $nuevaClave = 'temp' . rand(1000, 9999);
            $claveHash = password_hash($nuevaClave, PASSWORD_DEFAULT);

            $sqlActualizar = "UPDATE users SET pass = :pass WHERE email = :email";
            $stmtActualizar = $con->prepare($sqlActualizar);
            $stmtActualizar->execute([
                ':pass' => $claveHash,
                ':email' => $email
            ]);

            if($stmtActualizar->rowCount() > 0){
                $_SESSION['mensajeOk'] = "Su nueva contraseña es: " . $nuevaClave . ". Por favor, cambie su contraseña después de iniciar sesión.";
                header("Location: ../../../public/pass_temporal.php");
                exit();
            }else{
                $_SESSION['mensajeError'] = "Error al actualizar la contraseña. Por favor, intente nuevamente.";
                header("Location: ../../../public/pass_temporal.php");
                exit();
            }
        }else{
            $_SESSION['mensajeError'] = "El correo electrónico no está registrado.";
            header("Location: ../../../public/pass_temporal.php");
            exit();
        }
    }
    }
