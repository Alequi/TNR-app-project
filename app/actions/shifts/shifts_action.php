<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();

$con = conectar();

//Obtener clinicas

$stmt_clinics = $con->prepare("SELECT id, nombre, capacidad_ma, capacidad_ta FROM clinics WHERE activa = 1 ORDER BY nombre");
    $stmt_clinics->execute();
    $clinics = $stmt_clinics->fetchAll(PDO::FETCH_ASSOC);


// Obtener campañas activas
 $stmt_campaigns = $con->prepare("SELECT id, nombre FROM campaigns WHERE activa = 1 ORDER BY fecha_inicio DESC");
    $stmt_campaigns->execute();
    $campaigns = $stmt_campaigns->fetchAll(PDO::FETCH_ASSOC);


//Paginacion de resultados

$records_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

if ($current_page < 1) {
    $current_page = 1;
}

$offset = ($current_page - 1) * $records_per_page;

// Obtener el total de turnos
$sql_count = "SELECT COUNT(*) FROM shifts";
$stmt_count = $con->prepare($sql_count);
$stmt_count->execute();
$total_records = $stmt_count->fetchColumn();

$total_pages = ceil($total_records / $records_per_page);

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
        LIMIT :limit OFFSET :offset
    ";
    $stmt = $con->prepare($sql_shifts);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);