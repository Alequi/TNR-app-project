<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../helpers/auth.php';
admin();

require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

//Colonias estadísticas generales
$sql_stats = "SELECT COUNT(*) AS total_colonias
              FROM COLONIES";
$stmt_stats = $con->prepare($sql_stats);
$stmt_stats->execute();
$colonies_stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);
$total_colonias = $colonies_stats['total_colonias'];

