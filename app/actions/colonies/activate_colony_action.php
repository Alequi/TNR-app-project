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

    if (!$data || !isset($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    $colony_id = filter_var($data['id'], FILTER_VALIDATE_INT);

    if ($colony_id === false) {
        echo json_encode(['success' => false, 'message' => 'ID de colonia inválido.']);
        exit;
    }

    try {
        // Activar la colonia
        $stmt_activate = $con->prepare("UPDATE colonies SET activa = 1 WHERE id = :id");
        $stmt_activate->execute([':id' => $colony_id]);

        $_SESSION['success_message'] = "Colonia activada exitosamente.";
        echo json_encode(['success' => true, 'message' => 'Colonia activada exitosamente.']);

    } catch (Exception $e) {
        error_log('Error activating colony: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al activar la colonia.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
