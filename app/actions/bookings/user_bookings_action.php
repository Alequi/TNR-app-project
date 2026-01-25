<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

$user_id = $_SESSION['user_id'];

try {
    // Obtener reservas del usuario con información completa
    $stmt = $con->prepare("
        SELECT 
            b.id,
            b.fecha_drop,
            b.turno_drop,
            b.fecha_pick,
            b.turno_pick,
            b.gatos_count,
            b.estado,
            b.created_at,
            c.nombre as colonia_nombre,
            cl.nombre as clinica_nombre,
            s.fecha as shift_fecha,
            s.turno as shift_turno
        FROM bookings b
        INNER JOIN colonies c ON b.colony_id = c.id
        INNER JOIN shifts s ON b.shift_id = s.id
        INNER JOIN clinics cl ON s.clinic_id = cl.id
        WHERE b.user_id = :user_id
        ORDER BY b.created_at DESC
    ");
    
    $stmt->execute([':user_id' => $user_id]);
    $user_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $user_bookings = [];
    error_log("Error al obtener reservas: " . $e->getMessage());
}

// Cantidad de reservas activas
$active_bookings_count = 0;
foreach ($user_bookings as $booking) {
    if ($booking['estado'] === 'reservado') {
        $active_bookings_count++;
    }
}

//Turnos de mañana
$active_morning_shifts = 0;
foreach ($user_bookings as $booking) {
    if ($booking['estado'] === 'reservado' && $booking['shift_turno'] === 'M') {
        $active_morning_shifts++;
    }
}
//Turnos de tarde
$active_afternoon_shifts = 0;
foreach ($user_bookings as $booking) {
    if ($booking['estado'] === 'reservado' && $booking['shift_turno'] === 'T') {
        $active_afternoon_shifts++;
    }
}


