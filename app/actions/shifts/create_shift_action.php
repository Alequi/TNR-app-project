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
    if (!$data || !isset($data['clinic_id']) || !isset($data['campaign_id']) || !isset($data['fecha']) || !isset($data['turno']) || !isset($data['capacidad'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    // Sanitizar y validar entradas
    $clinic_id = filter_var(trim($data['clinic_id']), FILTER_VALIDATE_INT);
    $campaign_id = filter_var(trim($data['campaign_id']), FILTER_VALIDATE_INT);
    $fecha = trim($data['fecha']);
    $turno = strtoupper(trim($data['turno']));

  

    // Obtener capacidad de la clínica según el turno
    $stmt_clinic = $con->prepare("SELECT capacidad_ma, capacidad_ta FROM clinics WHERE id = :id");
    $stmt_clinic->execute([':id' => $clinic_id]);
    $clinic_data = $stmt_clinic->fetch(PDO::FETCH_ASSOC);

    if (!$clinic_data) {
        echo json_encode(['success' => false, 'message' => 'La clínica seleccionada no existe.']);
        exit;
    }

    // La capacidad siempre viene de la clínica, no del formulario
    $capacidad = ($turno === 'M') ? $clinic_data['capacidad_ma'] : $clinic_data['capacidad_ta'];

    if ($capacidad <= 0) {
        echo json_encode(['success' => false, 'message' => 'La clínica no tiene capacidad configurada para este turno.']);
        exit;
    }

    //Verificar si el turno ya existe para la clínica, fecha y turno dados
    $stmt_check = $con->prepare("SELECT COUNT(*) FROM shifts WHERE clinic_id = :clinic_id AND fecha = :fecha AND turno = :turno");
    $stmt_check->execute([
        ':clinic_id' => $clinic_id,
        ':fecha' => $fecha,
        ':turno' => $turno
    ]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'El turno ya existe para la clínica, fecha y turno especificados.']);
        exit;
    }

    //Insertar en la base de datos

    try {
        $stmt = $con->prepare("INSERT INTO shifts (clinic_id, campaign_id, fecha, turno, capacidad) VALUES (:clinic_id, :campaign_id, :fecha, :turno, :capacidad)");
        $stmt->execute([
            ':clinic_id' => $clinic_id,
            ':campaign_id' => $campaign_id,
            ':fecha' => $fecha,
            ':turno' => $turno,
            ':capacidad' => $capacidad
        ]);


        $_SESSION['success_message'] = 'Turno creado con éxito.';
        echo json_encode(['success' => true, 'message' => 'Turno creado exitosamente.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al crear el turno: ' . $e->getMessage()]);
    }






}