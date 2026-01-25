<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

$user_id = $_SESSION['user_id'];

try {
    // Obtener la colonia del usuario logueado
    $stmt_user = $con->prepare("SELECT colony_id FROM users WHERE id = :user_id");
    $stmt_user->execute([':user_id' => $user_id]);
    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
    
    $colony_id = $user['colony_id'];
    
    if ($colony_id) {
        // Obtener todos los voluntarios de la misma colonia
        $stmt_volunteers = $con->prepare("
            SELECT 
                u.id,
                u.nombre,
                u.email,
                u.telefono,
                u.rol,
                u.created_at
            FROM users u
            WHERE u.colony_id = :colony_id
            ORDER BY u.nombre ASC
        ");
        
        $stmt_volunteers->execute([':colony_id' => $colony_id]);
        $colony_volunteers = $stmt_volunteers->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $colony_volunteers = [];
    }
    
} catch (Exception $e) {
    $colony_volunteers = [];
    error_log("Error al obtener voluntarios: " . $e->getMessage());
}

// Contar voluntarios por rol
$volunteers_count = count($colony_volunteers);
$gestores_count = count(array_filter($colony_volunteers, fn($v) => $v['rol'] === 'gestor'));
$voluntarios_count = count(array_filter($colony_volunteers, fn($v) => $v['rol'] === 'voluntario'));
?>