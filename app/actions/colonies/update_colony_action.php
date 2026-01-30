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

    //validamos datos
    if (!$data || !isset($data['id']) || !isset($data['code']) || !isset($data['nombre']) || !isset($data['zona']) || !array_key_exists('gestor_id', $data)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    // Sanitizar y validar entradas
    $id = filter_var(trim($data['id']), FILTER_VALIDATE_INT);
    $code = trim($data['code']);
    $nombre = trim($data['nombre']);
    $zona = trim($data['zona']);
    $gestor_id = $data['gestor_id'] !== '' ? filter_var($data['gestor_id'], FILTER_VALIDATE_INT) : null;

    try {
        $con->beginTransaction();

        // Actualizar colonia
        $stmt = $con->prepare("
            UPDATE colonies
            SET code = :code, nombre = :nombre, zona = :zona, gestor_id = :gestor_id
            WHERE id = :id
        ");

        $stmt->execute([
            ':code' => $code,
            ':nombre' => $nombre,
            ':zona' => $zona,
            ':gestor_id' => $gestor_id,
            ':id' => $id
        ]);

        $con->commit();
        $_SESSION['success_message'] = "Colonia actualizada exitosamente.";
        echo json_encode(['success' => true, 'message' => 'Colonia actualizada exitosamente.']);
    } catch (Exception $e) {
        $con->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la colonia: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
