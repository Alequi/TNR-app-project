<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();


$con = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validar datos
    if (!$data || !isset($data['clinic_id']) || !isset($data['cage_type_id']) || !isset($data['numero_interno'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    $clinic_id = filter_var($data['clinic_id'], FILTER_VALIDATE_INT);
    $cage_type_id = filter_var($data['cage_type_id'], FILTER_VALIDATE_INT);
    $numero_interno = trim($data['numero_interno']);

    if ($clinic_id === false || $cage_type_id === false || empty($numero_interno)) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    try {
        $con->beginTransaction();

        // Verificar que no exista una jaula con el mismo número en la misma clínica
        $stmt_check = $con->prepare("SELECT id FROM cages WHERE clinic_id = :clinic_id AND numero_interno = :numero_interno");
        $stmt_check->execute([
            ':clinic_id' => $clinic_id,
            ':numero_interno' => $numero_interno
        ]);

        if ($stmt_check->rowCount() > 0) {
            $con->rollBack();
            echo json_encode(['success' => false, 'message' => 'Ya existe una jaula con ese número interno en esta clínica.']);
            exit;
        }

        // Insertar la nueva jaula
        $stmt = $con->prepare("
            INSERT INTO cages (clinic_id, cage_type_id, numero_interno, activo)
            VALUES (:clinic_id, :cage_type_id, :numero_interno, 1)
        ");
        
        $stmt->execute([
            ':clinic_id' => $clinic_id,
            ':cage_type_id' => $cage_type_id,
            ':numero_interno' => $numero_interno
        ]);

        //Actualizar tabla clinic_cages
        $stmt_update = $con->prepare("
            UPDATE clinic_cages 
            SET cantidad_total = cantidad_total + 1 
            WHERE clinic_id = :clinic_id AND cage_type_id = :cage_type_id
        ");
        $stmt_update->execute([
            ':clinic_id' => $clinic_id,
            ':cage_type_id' => $cage_type_id
        ]);

        // Verificar que se actualizó al menos 1 fila
        if ($stmt_update->rowCount() === 0) {
            // Si no existe el registro en clinic_cages, crearlo
            $stmt_insert = $con->prepare("
                INSERT INTO clinic_cages (clinic_id, cage_type_id, cantidad_total, cantidad_prestada)
                VALUES (:clinic_id, :cage_type_id, 1, 0)
            ");
            $stmt_insert->execute([
                ':clinic_id' => $clinic_id,
                ':cage_type_id' => $cage_type_id
            ]);
        }

        $con->commit();

        $_SESSION['success_message'] = 'Jaula creada con éxito.';
        echo json_encode(['success' => true, 'message' => 'Jaula creada con éxito.']);
        exit;

    } catch (PDOException $e) {
        $con->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al crear la jaula: ' . $e->getMessage()]);
        exit;
    }
}
