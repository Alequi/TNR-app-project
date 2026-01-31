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
    if (!$data || !isset($data['nombre']) || !isset($data['telefono']) || !isset($data['capacidad_ma']) || !isset($data['capacidad_ta'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    $nombre = trim($data['nombre']);
    $direccion = isset($data['direccion']) ? trim($data['direccion']) : null;
    $telefono = trim($data['telefono']);
    $capacidad_ma = filter_var($data['capacidad_ma'], FILTER_VALIDATE_INT);
    $capacidad_ta = filter_var($data['capacidad_ta'], FILTER_VALIDATE_INT);

    // Convertir dirección vacía a NULL
    if ($direccion === '') {
        $direccion = null;
    }

    if (empty($nombre) || empty($telefono) || $capacidad_ma === false || $capacidad_ta === false || $capacidad_ma < 1 || $capacidad_ta < 1) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos. Las capacidades deben ser números mayores a 0.']);
        exit;
    }

    try {
        // Insertar nueva clínica
        $stmt = $con->prepare("
            INSERT INTO clinics (nombre, direccion, telefono, capacidad_ma, capacidad_ta, activa)
            VALUES (:nombre, :direccion, :telefono, :capacidad_ma, :capacidad_ta, 1)
        ");
        
        $stmt->execute([
            ':nombre' => $nombre,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':capacidad_ma' => $capacidad_ma,
            ':capacidad_ta' => $capacidad_ta
        ]);

        $_SESSION['success_message'] = "Clínica creada correctamente.";

        echo json_encode(['success' => true, 'message' => 'Clínica creada exitosamente.']);

    } catch (Exception $e) {
        error_log('Error creating clinic: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al crear la clínica.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}