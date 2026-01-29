<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../helpers/auth.php';
admin();

require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

//Datos generales de clínicas y ocupación
$sql_stats = "SELECT 
    c.nombre AS clinic_name,
    c.capacidad_ma,
    c.capacidad_ta,
    COALESCE(SUM(CASE WHEN s.turno = 'M' THEN s.ocupados ELSE 0 END), 0) AS ocupados_ma,
    COALESCE(SUM(CASE WHEN s.turno = 'T' THEN s.ocupados ELSE 0 END), 0) AS ocupados_ta
FROM clinics c
LEFT JOIN shifts s ON c.id = s.clinic_id AND s.fecha = CURDATE()
GROUP BY c.id, c.nombre, c.capacidad_ma, c.capacidad_ta
ORDER BY c.nombre";
$stmt_stats = $con->prepare($sql_stats);
$stmt_stats->execute();
$clinics_stats = $stmt_stats->fetchAll(PDO::FETCH_ASSOC);
