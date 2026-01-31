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

    if (!$data || !isset($data['clinic_id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de clínica no proporcionado.']);
        exit;
    }

    $clinic_id = filter_var($data['clinic_id'], FILTER_VALIDATE_INT);

    if ($clinic_id === false || $clinic_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de clínica inválido.']);
        exit;
    }

    try {
        $con->beginTransaction();

        // Verificar que la clínica existe y está activa
        $stmt_check = $con->prepare("SELECT id, nombre, activa FROM clinics WHERE id = :id");
        $stmt_check->execute([':id' => $clinic_id]);
        $clinic = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (!$clinic) {
            $con->rollBack();
            echo json_encode(['success' => false, 'message' => 'La clínica no existe.']);
            exit;
        }

        if ($clinic['activa'] == 0) {
            $con->rollBack();
            echo json_encode(['success' => false, 'message' => 'La clínica ya está desactivada.']);
            exit;
        }

        // Verificar si tiene reservas activas en turnos futuros
        $stmt_bookings = $con->prepare("
            SELECT COUNT(*) as active_bookings
            FROM bookings b
            INNER JOIN shifts s ON b.shift_id = s.id
            WHERE s.clinic_id = :clinic_id 
            AND s.fecha >= CURDATE()
            AND b.estado IN ('reservado', 'entregado_vet', 'listo_recoger')
        ");
        $stmt_bookings->execute([':clinic_id' => $clinic_id]);
        $result = $stmt_bookings->fetch(PDO::FETCH_ASSOC);

        if ($result['active_bookings'] > 0) {
            $con->rollBack();
            echo json_encode([
                'success' => false, 
                'message' => 'No se puede desactivar la clínica. Tiene ' . $result['active_bookings'] . ' reserva(s) activa(s) en turnos futuros.'
            ]);
            exit;
        }

        // Desactivar la clínica
        $stmt_deactivate = $con->prepare("UPDATE clinics SET activa = 0 WHERE id = :id");
        $stmt_deactivate->execute([':id' => $clinic_id]);

        $con->commit();

        $_SESSION['success_message'] = 'Clínica "' . $clinic['nombre'] . '" desactivada correctamente.';
        echo json_encode(['success' => true, 'message' => 'Clínica desactivada exitosamente.']);

    } catch (Exception $e) {
        $con->rollBack();
        error_log('Error al desactivar la clínica ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al desactivar la clínica.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}