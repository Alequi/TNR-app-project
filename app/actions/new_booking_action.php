<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/conexion.php';
$con = conectar();

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$shift_id = filter_var($data['shift_id'], FILTER_VALIDATE_INT);
$numero_gatos = filter_var($data['numero_gatos'], FILTER_VALIDATE_INT);
$colony_id = filter_var($data['colony_id'], FILTER_VALIDATE_INT);

if ($shift_id === false || $numero_gatos === false || $numero_gatos <= 0 || $colony_id === false) {
    echo json_encode(['success' => false, 'message' => 'Datos de reserva inválidos.']);
    exit;
}

try {
    $con->beginTransaction();

    // Verificar que la colonia pertenece al usuario
    $stmt_colony = $con->prepare("SELECT id FROM users WHERE id = :user_id AND colony_id = :colony_id");
    $stmt_colony->execute([':user_id' => $user_id, ':colony_id' => $colony_id]);
    if (!$stmt_colony->fetch()) {
        $con->rollBack();
        echo json_encode(['success' => false, 'message' => 'La colonia no pertenece a este usuario.']);
        exit;
    }

    // Verificar que no exista ya una reserva para este usuario en este turno
    $stmt_duplicate = $con->prepare("SELECT id FROM bookings WHERE user_id = :user_id AND shift_id = :shift_id AND estado != 'cancelado'");
    $stmt_duplicate->execute([':user_id' => $user_id, ':shift_id' => $shift_id]);
    if ($stmt_duplicate->fetch()) {
        $con->rollBack();
        echo json_encode(['success' => false, 'message' => 'Ya tienes una reserva activa para este turno.']);
        exit;
    }

    // Obtener información del turno
    $stmt_check = $con->prepare("SELECT capacidad, ocupados, fecha, turno FROM shifts WHERE id = :shift_id FOR UPDATE");
    $stmt_check->bindValue(':shift_id', $shift_id, PDO::PARAM_INT);
    $stmt_check->execute();
    $shift = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$shift || ($shift['capacidad'] - $shift['ocupados']) < $numero_gatos) {
        $con->rollBack();
        echo json_encode(['success' => false, 'message' => 'No hay suficiente capacidad en el turno seleccionado.']);
        exit;
    }

    // Calcular fechas de entrega y recogida
    $fecha_drop = $shift['fecha'];
    $turno_drop = $shift['turno'];

    if ($turno_drop === 'M') {
        // Si deja por la mañana, recoge por la tarde del mismo día
        $fecha_pick = $fecha_drop;
        $turno_pick = 'T';
    } else { // $turno_drop === 'T'
        // Si deja por la tarde, recoge por la mañana del día siguiente
        $fecha_pick = date('Y-m-d', strtotime($fecha_drop . ' +1 day'));
        $turno_pick = 'M';
    }


    // Insertar booking
    $stmt_insert = $con->prepare("
        INSERT INTO bookings (colony_id, user_id, shift_id, fecha_drop, turno_drop, fecha_pick, turno_pick, gatos_count) 
        VALUES (:colony_id, :user_id, :shift_id, :fecha_drop, :turno_drop, :fecha_pick, :turno_pick, :gatos_count)
    ");
    $stmt_insert->execute([
        ':colony_id' => $colony_id,
        ':user_id' => $user_id,
        ':shift_id' => $shift_id,
        ':fecha_drop' => $fecha_drop,
        ':turno_drop' => $turno_drop,
        ':fecha_pick' => $fecha_pick,
        ':turno_pick' => $turno_pick,
        ':gatos_count' => $numero_gatos
    ]);

    // Actualizar ocupados
    $stmt_update = $con->prepare("UPDATE shifts SET ocupados = ocupados + :numero_gatos WHERE id = :shift_id");
    $stmt_update->execute([
        ':numero_gatos' => $numero_gatos,
        ':shift_id' => $shift_id
    ]);

    $con->commit();

    $_SESSION['success_message'] = 'Reserva realizada con éxito.';
    echo json_encode(['success' => true, 'message' => 'Reserva realizada con éxito.']);
} catch (Exception $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al procesar la reserva: ' . $e->getMessage()]);
}
