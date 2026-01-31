<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();
$con = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener los datos JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);


    try {

        // Validar que se hayan recibido los datos requeridos
        if (!isset($data['cage_id']) || !isset($data['numero_interno']) || !isset($data['activo'])) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
            exit;
        }

        $cage_id = filter_var($data['cage_id'], FILTER_VALIDATE_INT);
        $numero_interno = trim($data['numero_interno']);
        $activo = filter_var($data['activo'], FILTER_VALIDATE_INT);

        // Validar que los datos sean válidos
        if ($cage_id === false || empty($numero_interno) || ($activo !== 0 && $activo !== 1)) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            exit;
        }

        // Verificar que la jaula existe
        $stmt = $con->prepare("SELECT id, clinic_id, numero_interno FROM cages WHERE id = :id");
        $stmt->execute(['id' => $cage_id]);
        $cage = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cage) {
            echo json_encode(['success' => false, 'message' => 'La jaula no existe']);
            exit;
        }

        // Verificar que la jaula NO esté prestada actualmente
        $stmt_loan = $con->prepare("SELECT id FROM cage_loans WHERE cage_id = :cage_id AND estado = 'prestado'");
        $stmt_loan->execute(['cage_id' => $cage_id]);
        $active_loan = $stmt_loan->fetch(PDO::FETCH_ASSOC);

        if ($active_loan) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede modificar la jaula porque está actualmente prestada. Debe ser devuelta primero.'
            ]);
            exit;
        }

        // Verificar que no exista otra jaula con el mismo número interno en la misma clínica
        if ($numero_interno !== $cage['numero_interno']) {
            $stmt = $con->prepare("SELECT id FROM cages WHERE numero_interno = :numero_interno AND clinic_id = :clinic_id AND id != :id");
            $stmt->execute(['numero_interno' => $numero_interno, 'clinic_id' => $cage['clinic_id'], 'id' => $cage_id]);
            $duplicate = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($duplicate) {
                echo json_encode(['success' => false, 'message' => 'Ya existe una jaula con ese número interno en esta clínica']);
                exit;
            }
        }

        // Actualizar la jaula (solo numero_interno y activo)
        $stmt = $con->prepare("UPDATE cages SET numero_interno = :numero_interno, activo = :activo WHERE id = :id");
        $stmt->execute(['numero_interno' => $numero_interno, 'activo' => $activo, 'id' => $cage_id]);

        $_SESSION['success_message'] = 'Jaula "' . $numero_interno . '" actualizada correctamente.';
        echo json_encode([
            'success' => true,
            'message' => 'Jaula actualizada correctamente'
        ]);
    } catch (PDOException $e) {
        error_log("Error al actualizar jaula: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la jaula']);
    }
}
