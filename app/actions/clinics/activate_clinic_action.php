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

    // Validar datos
    if (!$data || !isset($data['clinic_id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de clínica no proporcionado.']);
        exit;
    }
    // Sanitizar y validar ID de clínica
    $clinic_id = filter_var($data['clinic_id'], FILTER_VALIDATE_INT);

    if ($clinic_id === false || $clinic_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de clínica inválido.']);
        exit;
    }

    try {
        // Verificar que la clínica existe y está inactiva
        $stmt_check = $con->prepare("SELECT id, nombre, activa FROM clinics WHERE id = :id");
        $stmt_check->execute([':id' => $clinic_id]);
        $clinic = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (!$clinic) {
            echo json_encode(['success' => false, 'message' => 'La clínica no existe.']);
            exit;
        }

        if ($clinic['activa'] == 1) {
            echo json_encode(['success' => false, 'message' => 'La clínica ya está activa.']);
            exit;
        }

        // Activar la clínica
        $stmt_activate = $con->prepare("UPDATE clinics SET activa = 1 WHERE id = :id");
        $stmt_activate->execute([':id' => $clinic_id]);

        $_SESSION['success_message'] = 'Clínica "' . $clinic['nombre'] . '" activada correctamente.';
        echo json_encode(['success' => true, 'message' => 'Clínica activada exitosamente.']);

    } catch (Exception $e) {
        error_log('Error activating clinic: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al activar la clínica.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
