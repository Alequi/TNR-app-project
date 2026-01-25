<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

//SELECT CLINICAS
$stmt = $con->prepare("SELECT * FROM clinics");
$stmt->execute();
$clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener turnos disponibles según filtros
$fecha = $_GET['fecha'] ?? '';
$clinic_id = filter_input(INPUT_GET, 'clinic_id', FILTER_VALIDATE_INT);
if ($clinic_id === false) {
    $clinic_id = null;
}

$sql_shifts = "SELECT sh.id, sh.turno, sh.capacidad, sh.ocupados, 
                      (sh.capacidad - sh.ocupados) as disponibles,
                      cli.nombre as clinica_nombre, sh.fecha
               FROM shifts sh
               JOIN clinics cli ON sh.clinic_id = cli.id
               WHERE sh.fecha = :fecha 
               AND (:clinic_id IS NULL OR sh.clinic_id = :clinic_id)
               AND sh.capacidad > sh.ocupados
               ORDER BY sh.turno ASC";

$stmt_shifts = $con->prepare($sql_shifts);
$stmt_shifts->bindValue(':fecha', $fecha, PDO::PARAM_STR);
$stmt_shifts->bindValue(':clinic_id', $clinic_id, PDO::PARAM_INT);
$stmt_shifts->execute();
$available_shifts = $stmt_shifts->fetchAll(PDO::FETCH_ASSOC);

