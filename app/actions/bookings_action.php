<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . '/../../config/conexion.php';
$con = conectar();

$user_id = $_SESSION['user_id'];

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