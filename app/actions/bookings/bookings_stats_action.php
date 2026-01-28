<?php

require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

//Reservas activas del dia
$sql_stats = "SELECT 
    COUNT(*) AS total_reservas_activas
   FROM bookings
   WHERE DATE(created_at) = CURDATE() AND estado = 'reservado'";
$stmt_stats = $con->prepare($sql_stats);
$stmt_stats->execute();
$bookings_stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);
$total_reservas_activas = $bookings_stats['total_reservas_activas'];

//Gatos total del mes con reserva recogido
$sql_gatos_mes = "SELECT 
    COALESCE(SUM(gatos_count), 0) AS total_gatos_mes
   FROM bookings
   WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND estado = 'recogido'";
$stmt_gatos_mes = $con->prepare($sql_gatos_mes);
$stmt_gatos_mes->execute();
$gatos_mes_stats = $stmt_gatos_mes->fetch(PDO::FETCH_ASSOC);
$total_gatos_mes = $gatos_mes_stats['total_gatos_mes'];

//Todos los datos de reservas próximos 7 días
$sql_reservas_proximos_dias = "SELECT 
    b.id,
    b.created_at,
    b.estado,
    b.turno_drop AS turno,
    b.gatos_count AS gatos,
    b.fecha_drop AS fecha,
    c.nombre AS clinic_name,
    u.nombre AS volunteer_name,
    co.nombre AS colony_name
    FROM bookings b
    JOIN shifts s ON b.shift_id = s.id
    JOIN clinics c ON s.clinic_id = c.id
    JOIN users u ON b.user_id = u.id
    JOIN colonies co ON b.colony_id = co.id
    WHERE b.fecha_drop BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    AND b.estado = 'reservado'
    ORDER BY b.fecha_drop ASC";
$stmt_reservas_proximos_dias = $con->prepare($sql_reservas_proximos_dias);
$stmt_reservas_proximos_dias->execute();
$reservas_proximos_dias = $stmt_reservas_proximos_dias->fetchAll(PDO::FETCH_ASSOC);
    
// Historial de todas las reservas
$sql_all_bookings = "SELECT 
    b.id,
    b.created_at,
    b.estado,
    b.turno_drop AS turno,
    b.gatos_count AS gatos,
    b.fecha_drop AS fecha,
    c.nombre AS clinic_name,
    u.nombre AS volunteer_name,
    co.nombre AS colony_name
    FROM bookings b
    JOIN shifts s ON b.shift_id = s.id
    JOIN clinics c ON s.clinic_id = c.id
    JOIN users u ON b.user_id = u.id
    JOIN colonies co ON b.colony_id = co.id
    ORDER BY b.created_at DESC";
$stmt_all_bookings = $con->prepare($sql_all_bookings);
$stmt_all_bookings->execute();
$all_bookings = $stmt_all_bookings->fetchAll(PDO::FETCH_ASSOC);

// Calcular estadísticas
$pending_count = 0;
$in_clinic_count = 0;
$completed_count = 0;
$total_gatos = 0;

if (!empty($all_bookings)) {
    foreach ($all_bookings as $booking) {
        switch ($booking['estado']) {
            case 'reservado':
                $pending_count++;
                break;
            case 'entregado_vet':
                $in_clinic_count++;
                break;
            case 'recogido':
                $completed_count++;
                $total_gatos += (int)$booking['gatos'];
                break;
        }
    }
}

