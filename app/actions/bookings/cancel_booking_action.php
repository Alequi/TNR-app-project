<?php

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || !isset($data['booking_id'])) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}

$booking_id = filter_var($data['booking_id'], FILTER_VALIDATE_INT);
if ($booking_id === false) {
    echo json_encode(['success' => false, 'message' => 'ID de reserva inválido.']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {

    $con->beginTransaction();

    // Verificar que la reserva pertenece al usuario
    $stmt_verify = $con->prepare("SELECT id FROM bookings WHERE id = :booking_id AND user_id = :user_id AND estado = 'reservado'");
    $stmt_verify->execute([':booking_id' => $booking_id, ':user_id' => $user_id]);
    
    if ($stmt_verify->rowCount() === 0) {
        $con->rollBack();
        echo json_encode(['success' => false, 'message' => 'No se pudo cancelar la reserva. Puede que ya esté cancelada, no exista o no te pertenezca.']);
        exit;
    }

    // Actualizar el estado de la reserva a 'cancelado'
    $stmt = $con->prepare("UPDATE bookings SET estado = 'cancelado' WHERE id = :booking_id");
    $stmt->execute([':booking_id' => $booking_id]);

    //Actualizar el campo ocupados en shifts
    $stmt_shift = $con->prepare("
        UPDATE shifts 
        SET ocupados = ocupados - (
            SELECT gatos_count 
            FROM bookings 
            WHERE id = :booking_id
        )
        WHERE id = (
            SELECT shift_id 
            FROM bookings 
            WHERE id = :booking_id
        )
    ");
    $stmt_shift->execute([':booking_id' => $booking_id]);

    // Confirmar transacción
    $con->commit();
    
    $_SESSION['success_message'] = 'Reserva cancelada con éxito.';
    echo json_encode(['success' => true, 'message' => 'Reserva cancelada con éxito.']);

} catch (Exception $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al cancelar la reserva: ' . $e->getMessage()]);
}