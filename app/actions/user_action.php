<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . '/../../config/conexion.php';
$con = conectar();
$user_id = $_SESSION['user_id'];


$sql_user = "SELECT u.*, c.nombre as colonia_nombre, c.code as colonia_code, c.id as colonia_id
             FROM users u 
             LEFT JOIN colonies c ON u.colony_id = c.id 
             WHERE u.id = :user_id";
$stmt_user = $con->prepare($sql_user);
$stmt_user->execute([':user_id' => $user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
$user_colony = $user['colonia_nombre'] ? $user['colonia_nombre'] . ' (' . $user['colonia_code'] . ')' : 'No asignada';
$user_colony_id = $user['colonia_id'];
