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
    // Sanitizar y validar entradas
    $colony_id = filter_var($data['id'], FILTER_VALIDATE_INT);

    if ($colony_id === false) {
        echo json_encode(['success' => false, 'message' => 'ID de colonia inválido.']);
        exit;
    }

    try {
        $con->beginTransaction();

        // Verificar si hay reservas activas/pendientes
        $stmt_check = $con->prepare("
            SELECT COUNT(*) as total 
            FROM bookings 
            WHERE colony_id = :colony_id 
            AND estado IN ('reservado', 'entregado_vet', 'listo_recoger')
        ");
        $stmt_check->execute([':colony_id' => $colony_id]);
        $active_bookings = $stmt_check->fetch(PDO::FETCH_ASSOC)['total'];

        if ($active_bookings > 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'No se puede desactivar la colonia porque tiene ' . $active_bookings . ' reserva(s) activa(s).'
            ]);
            exit;
        }

        // Desactivar la colonia (baja lógica)
        $stmt_deactivate = $con->prepare("UPDATE colonies SET activa = 0 WHERE id = :id");
        $stmt_deactivate->execute([':id' => $colony_id]);

        // Desasignar voluntarios de la colonia inactiva
        $stmt_unassign = $con->prepare("UPDATE users SET colony_id = NULL WHERE colony_id = :colony_id");
        $stmt_unassign->execute([':colony_id' => $colony_id]);

        $con->commit();
        $_SESSION['success_message'] = "Colonia desactivada correctamente.";
        echo json_encode(['success' => true, 'message' => 'Colonia desactivada correctamente.']);

    } catch (Exception $e) {
        $con->rollBack();
        error_log('Error deactivating colony: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al desactivar la colonia.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}