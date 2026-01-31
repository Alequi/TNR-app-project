<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();

$con = conectar();

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data || !isset($data['campaign_id'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    $campaign_id = filter_var($data['campaign_id'], FILTER_VALIDATE_INT);

    if ($campaign_id === false) {
        echo json_encode(['success' => false, 'message' => 'ID de campaña inválido.']);
        exit;
    }

    // Verificar que la campaña existe y está activa
    $stmt_check = $con->prepare("SELECT id, nombre, activa FROM campaigns WHERE id = :id");
    $stmt_check->execute([':id' => $campaign_id]);
    $campaign = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if (!$campaign) {
        echo json_encode(['success' => false, 'message' => 'La campaña no existe.']);
        exit;
    }

    if ($campaign['activa'] == 0) {
        echo json_encode(['success' => false, 'message' => 'La campaña ya está finalizada.']);
        exit;
    }

    // Validar que no haya reservas activas en turnos futuros de esta campaña
    $stmt_bookings = $con->prepare("
        SELECT COUNT(*) as total 
        FROM bookings b
        INNER JOIN shifts s ON b.shift_id = s.id
        WHERE s.campaign_id = :campaign_id 
        AND s.fecha >= CURDATE()
        AND b.estado IN ('reservado', 'entregado_vet', 'listo_recoger')
    ");
    $stmt_bookings->execute([':campaign_id' => $campaign_id]);
    $booking_count = $stmt_bookings->fetch(PDO::FETCH_ASSOC);

    if ($booking_count['total'] > 0) {
        echo json_encode(['success' => false, 'message' => 'No se puede finalizar la campaña porque tiene reservas activas en turnos futuros.']);
        exit;
    }

    //Desactivar la campaña
    $stmt_end = $con->prepare("UPDATE campaigns SET activa = 0 WHERE id = :id");
    $stmt_end->execute([':id' => $campaign_id]);
    $_SESSION['success_message'] = 'Campaña "' . $campaign['nombre'] . '" finalizada correctamente.';
    echo json_encode(['success' => true, 'message' => 'Campaña finalizada exitosamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}