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

    // Validar datos obligatorios
    if (!$data || !isset($data['clinic_id']) || !isset($data['nombre']) || !isset($data['telefono']) || !isset($data['capacidad_ma']) || !isset($data['capacidad_ta'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    // Sanitizar y validar datos
    $clinic_id = filter_var($data['clinic_id'], FILTER_VALIDATE_INT);
    $nombre = trim($data['nombre']);
    $direccion = isset($data['direccion']) ? trim($data['direccion']) : null;
    $telefono = trim($data['telefono']);
    $capacidad_ma = filter_var($data['capacidad_ma'], FILTER_VALIDATE_INT);
    $capacidad_ta = filter_var($data['capacidad_ta'], FILTER_VALIDATE_INT);

    // Convertir dirección vacía a NULL
    if ($direccion === '') {
        $direccion = null;
    }

    if ($clinic_id === false || $clinic_id <= 0 || empty($nombre) || empty($telefono) || $capacidad_ma === false || $capacidad_ta === false || $capacidad_ma < 1 || $capacidad_ta < 1) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos. Las capacidades deben ser números mayores a 0.']);
        exit;
    }

    try {
        // Verificar que la clínica existe
        $stmt_check = $con->prepare("SELECT id, nombre FROM clinics WHERE id = :id");
        $stmt_check->execute([':id' => $clinic_id]);
        $clinic = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (!$clinic) {
            echo json_encode(['success' => false, 'message' => 'La clínica no existe.']);
            exit;
        }

        // Actualizar la clínica
        $stmt_update = $con->prepare("
            UPDATE clinics 
            SET nombre = :nombre, direccion = :direccion, telefono = :telefono, capacidad_ma = :capacidad_ma, capacidad_ta = :capacidad_ta 
            WHERE id = :id  
        ");
        $stmt_update->execute([
            ':nombre' => $nombre,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':capacidad_ma' => $capacidad_ma,
            ':capacidad_ta' => $capacidad_ta,
            ':id' => $clinic_id
        ]);
        $_SESSION['success_message'] = 'Clínica "' . $nombre . '" actualizada correctamente.';
        echo json_encode(['success' => true, 'message' => 'Clínica actualizada exitosamente.']);
    } catch (Exception $e) {
        error_log('Error updating clinic: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la clínica.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido
.']);
}