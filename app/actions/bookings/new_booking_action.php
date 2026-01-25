<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

header('Content-Type: application/json');
require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

$user_id = $_SESSION['user_id'];

// Procesar solicitud POST para crear nueva reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validar datos recibidos

    if (!$data || !isset($data['shift_id']) || !isset($data['numero_gatos']) || !isset($data['colony_id'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    // Sanitizar y validar entradas

    $shift_id = filter_var($data['shift_id'], FILTER_VALIDATE_INT);
    $numero_gatos = filter_var($data['numero_gatos'], FILTER_VALIDATE_INT);
    $colony_id = filter_var($data['colony_id'], FILTER_VALIDATE_INT);
    
    // Verificar que los valores sean válidos
    if ($shift_id === false || $numero_gatos === false || $colony_id === false || $numero_gatos < 1) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    try {
        $con->beginTransaction();

        // Verificar disponibilidad del turno
        $stmt_check = $con->prepare("SELECT capacidad, ocupados FROM shifts WHERE id = :shift_id");
        $stmt_check->execute([':shift_id' => $shift_id]);
        $shift = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (!$shift) {
            $con->rollBack();
            echo json_encode(['success' => false, 'message' => 'El turno no existe.']);
            exit;
        }

        $disponibles = $shift['capacidad'] - $shift['ocupados'];
        if ($disponibles < $numero_gatos) {
            $con->rollBack();
            echo json_encode(['success' => false, 'message' => 'No hay suficientes plazas disponibles.']);
            exit;
        }

        // Verificar si ya existe una reserva activa para este usuario en este turno
        $stmt_duplicate = $con->prepare("SELECT id FROM bookings WHERE user_id = :user_id AND shift_id = :shift_id AND estado = 'reservado'");
        $stmt_duplicate->execute([':user_id' => $user_id, ':shift_id' => $shift_id]);
        
        if ($stmt_duplicate->rowCount() > 0) {
            $con->rollBack();
            echo json_encode(['success' => false, 'message' => 'Ya tienes una reserva activa para este turno.']);
            exit;
        }

        // Verificar si existe una reserva cancelada para reactivarla
        $stmt_cancelled = $con->prepare("SELECT id, gatos_count FROM bookings WHERE user_id = :user_id AND shift_id = :shift_id AND colony_id = :colony_id AND estado = 'cancelado'");
        $stmt_cancelled->execute([
            ':user_id' => $user_id, 
            ':shift_id' => $shift_id,
            ':colony_id' => $colony_id
        ]);
        $cancelled_booking = $stmt_cancelled->fetch(PDO::FETCH_ASSOC);

        if ($cancelled_booking) {
            // Reactivar la reserva existente
            $stmt_reactivate = $con->prepare("UPDATE bookings SET estado = 'reservado', gatos_count = :gatos_count, created_at = NOW() WHERE id = :id");
            $stmt_reactivate->execute([
                ':gatos_count' => $numero_gatos,
                ':id' => $cancelled_booking['id']
            ]);
            
            // Actualizar ocupados en shifts (sumar los nuevos gatos)
            $stmt_update = $con->prepare("UPDATE shifts SET ocupados = ocupados + :gatos_count WHERE id = :shift_id");
            $stmt_update->execute([
                ':gatos_count' => $numero_gatos,
                ':shift_id' => $shift_id
            ]);
        } else {
            // Insertar nueva reserva
            $stmt_insert = $con->prepare("INSERT INTO bookings (user_id, shift_id, colony_id, gatos_count, estado, created_at) 
                                           VALUES (:user_id, :shift_id, :colony_id, :gatos_count, 'reservado', NOW())");
            $stmt_insert->execute([
                ':user_id' => $user_id,
                ':shift_id' => $shift_id,
                ':colony_id' => $colony_id,
                ':gatos_count' => $numero_gatos
            ]);
            
            // Actualizar ocupados en shifts
            $stmt_update = $con->prepare("UPDATE shifts SET ocupados = ocupados + :gatos_count WHERE id = :shift_id");
            $stmt_update->execute([
                ':gatos_count' => $numero_gatos,
                ':shift_id' => $shift_id
            ]);
        }

        $con->commit();
        echo json_encode(['success' => true, 'message' => 'Reserva realizada con éxito.']);
        exit;

    } catch (Exception $e) {
        $con->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al procesar la reserva: ' . $e->getMessage()]);
        exit;
    }
}

else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}