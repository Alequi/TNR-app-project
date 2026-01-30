<?php

if(session_status() === PHP_SESSION_NONE){
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

    //validamos datos
    if (!$data || !isset($data['shift_id'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    // Sanitizar y validar entradas
    $shift_id = filter_var(trim($data['shift_id']), FILTER_VALIDATE_INT);

    if ($shift_id === false) {
        
        echo json_encode(['success' => false, 'message' => 'ID de turno inválido.']);
        exit;
    }

    //Comprobar si el turno tiene reservas asociadas
    $stmt_check = $con->prepare("SELECT COUNT(*) FROM bookings WHERE shift_id = :shift_id");
    $stmt_check->execute([':shift_id' => $shift_id]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'No se puede eliminar el turno porque tiene reservas asociadas.']);
        exit;
    }


    try {
        // Eliminar el turno
        $stmt_delete = $con->prepare("DELETE FROM shifts WHERE id = :shift_id");
        $stmt_delete->execute([':shift_id' => $shift_id]);

        $_SESSION['success_message'] = 'Turno eliminado correctamente.';
        echo json_encode(['success' => true, 'message' => 'Turno eliminado correctamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el turno.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}