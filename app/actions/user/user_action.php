<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . '/../../../config/conexion.php';
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

// Obtener cantidad de colonias asociadas al usuario
$colonies_quantity = 0;
if ($user_colony_id) {
    $stmt_colonies = $con->prepare("SELECT COUNT(*) as qty FROM colonies WHERE id = :colony_id");
    $stmt_colonies->execute([':colony_id' => $user_colony_id]);
    $result = $stmt_colonies->fetch(PDO::FETCH_ASSOC);
    $colonies_quantity = $result ? (int)$result['qty'] : 0;
}else {
    $colonies_quantity = 0;
}

// Obtener estadísticas de reservas del usuario
$sql_reservas = "SELECT COUNT(*) as total_reservas FROM bookings WHERE user_id = :user_id";
$stmt_reservas = $con->prepare($sql_reservas);
$stmt_reservas->execute([':user_id' => $user_id]);
$stats_reservas = $stmt_reservas->fetch(PDO::FETCH_ASSOC);

// Obtener reservas activas
$sql_activas = "SELECT COUNT(*) as activas 
                FROM bookings 
                WHERE user_id = :user_id AND estado IN ('reservado', 'entregado_vet', 'listo_recoger')";
$stmt_activas = $con->prepare($sql_activas);
$stmt_activas->execute([':user_id' => $user_id]);
$stats_activas = $stmt_activas->fetch(PDO::FETCH_ASSOC);

