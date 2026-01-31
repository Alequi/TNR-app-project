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

    if (!$data || !isset($data['campaign_id']) || !isset($data['nombre']) || !isset($data['fecha_inicio']) || !isset($data['fecha_fin'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }
    // Sanitizar y validar datos
    $campaign_id = filter_var($data['campaign_id'], FILTER_VALIDATE_INT);
    $nombre = trim($data['nombre']);
    $fecha_inicio = trim($data['fecha_inicio']);
    $fecha_fin = trim($data['fecha_fin']);

    if ($campaign_id === false || empty($nombre) || empty($fecha_inicio) || empty($fecha_fin)) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }
    // Validar que la fecha de fin no sea anterior a la fecha de inicio
    if (strtotime($fecha_fin) < strtotime($fecha_inicio)) {
        echo json_encode(['success' => false, 'message' => 'La fecha de fin no puede ser anterior a la fecha de inicio.']);
        exit;
    }

    try {
        // Verificar que la campaña existe
        $stmt_check = $con->prepare("SELECT id, nombre FROM campaigns WHERE id = :id");
        $stmt_check->execute([':id' => $campaign_id]);
        $campaign = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (!$campaign) {
            echo json_encode(['success' => false, 'message' => 'La campaña no existe.']);
            exit;
        }

        // Actualizar la campaña
        $stmt_update = $con->prepare("
            UPDATE campaigns 
            SET nombre = :nombre, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin 
            WHERE id = :id
        ");
        $stmt_update->execute([
            ':nombre' => $nombre,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':id' => $campaign_id
        ]);

        $_SESSION['success_message'] = 'Campaña "' . $nombre . '" actualizada correctamente.';
        echo json_encode(['success' => true, 'message' => 'Campaña actualizada exitosamente.']);

    } catch (Exception $e) {
        error_log('Error updating campaign: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la campaña.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}