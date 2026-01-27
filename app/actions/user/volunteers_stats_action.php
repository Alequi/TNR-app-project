<?php
require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

//obtener voluntarios activos y totales
$sql_stats = "SELECT COUNT(*) AS total_voluntarios
              FROM users
              WHERE activo = 1";
$stmt_stats = $con->prepare($sql_stats);
$stmt_stats->execute();
$volunteers_stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);
$total_voluntarios = $volunteers_stats['total_voluntarios'];
