<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../helpers/auth.php';
admin();

require_once __DIR__ . '/../../config/conexion.php';
$con = conectar();

//Campañas estadistidas

$sql_stats = "SELECT * FROM campaigns
WHERE activa = 1";
$stmt_stats = $con->prepare($sql_stats);
$stmt_stats->execute();
$campaigns_stats = $stmt_stats->fetchAll(PDO::FETCH_ASSOC);
$nombre_campaign_active = '';
$fecha_inicio_campaign_active = '';
$fecha_fin_campaign_active = '';

foreach ($campaigns_stats as $campaign_stat) {
    $nombre_campaign_active = $campaign_stat['nombre'];
    $fecha_inicio_campaign_active = $campaign_stat['fecha_inicio'];
    $fecha_fin_campaign_active = $campaign_stat['fecha_fin'];
}