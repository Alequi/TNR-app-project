<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();

$con = conectar();

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validar datos obligatorios
    if (!$data || !isset($data['nombre']) || !isset($data['fecha_inicio']) || !isset($data['fecha_fin'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    // Sanitizar y validar datos
    $nombre = trim($data['nombre']);
    $fecha_inicio = trim($data['fecha_inicio']);
    $fecha_fin = trim($data['fecha_fin']);

    if (empty($nombre) || empty($fecha_inicio) || empty($fecha_fin)) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    // Validar que la fecha de fin no sea anterior a la fecha de inicio
    if (strtotime($fecha_fin) < strtotime($fecha_inicio)) {
        echo json_encode(['success' => false, 'message' => 'La fecha de fin no puede ser anterior a la fecha de inicio.']);
        exit;
    }

    try {
        // Insertar nueva campaña
        $stmt = $con->prepare("
            INSERT INTO campaigns (nombre, fecha_inicio, fecha_fin, activa)
            VALUES (:nombre, :fecha_inicio, :fecha_fin, 1)
        ");
        
        $stmt->execute([
            ':nombre' => $nombre,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin
        ]);

        $_SESSION['success_message'] = 'Campaña "' . $nombre . '" creada correctamente.';

        echo json_encode(['success' => true, 'message' => 'Campaña creada exitosamente.']);

    } catch (Exception $e) {
        error_log('Error creating campaign: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al crear la campaña.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}