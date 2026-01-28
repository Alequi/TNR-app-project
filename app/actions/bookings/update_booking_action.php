<?php
require_once __DIR__ . '/../../helpers/auth.php';

header('Content-Type: application/json');

admin();
require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validar datos recibidos
    if (!$data || !isset($data['bookingId']) || !isset($data['newStatus'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    // Sanitizar y validar entradas
    $booking_id = filter_var($data['bookingId'], FILTER_VALIDATE_INT);
    $new_estado = filter_var($data['newStatus']);

    // Validar estados permitidos
    $estados_validos = ['reservado', 'entregado_vet', 'listo_recoger', 'recogido', 'cancelado'];
    if (!in_array($new_estado, $estados_validos)) {
        echo json_encode(['success' => false, 'message' => 'Estado no válido.']);
        exit;
    }

    try {
        $stmt = $con->prepare("UPDATE bookings SET estado = :new_estado WHERE id = :booking_id");
        $stmt->execute([':new_estado' => $new_estado, ':booking_id' => $booking_id]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'No se encontró la reserva o no hubo cambios.']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Reserva actualizada exitosamente.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la reserva: ' . $e->getMessage()]);
    }


}