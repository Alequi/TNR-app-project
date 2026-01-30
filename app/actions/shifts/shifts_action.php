<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();

$con = conectar();

//Obtener clinicas

$stmt_clinics = $con->prepare("SELECT id, nombre, capacidad_ma, capacidad_ta FROM clinics ORDER BY nombre");
    $stmt_clinics->execute();
    $clinics = $stmt_clinics->fetchAll(PDO::FETCH_ASSOC);


// Obtener campañas activas
 $stmt_campaigns = $con->prepare("SELECT id, nombre FROM campaigns WHERE activa = 1 ORDER BY fecha_inicio DESC");
    $stmt_campaigns->execute();
    $campaigns = $stmt_campaigns->fetchAll(PDO::FETCH_ASSOC);


// Obtener turnos existentes
$sql_shifts = "
        SELECT 
            s.id,
            s.fecha,
            s.turno,
            s.capacidad,
            s.ocupados,
            c.nombre AS clinic_nombre,
            c.id AS clinic_id,
            camp.nombre AS campaign_nombre,
            camp.id AS campaign_id
        FROM shifts s
        INNER JOIN clinics c ON s.clinic_id = c.id
        INNER JOIN campaigns camp ON s.campaign_id = camp.id
        ORDER BY s.fecha DESC, c.nombre, s.turno
    ";
    $stmt = $con->prepare($sql_shifts);
    $stmt->execute();
    $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);