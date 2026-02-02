<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../helpers/auth.php';

header('Content-Type: application/json');

admin();
require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validar datos recibidos
    if (!$data || !isset($data['user_id']) || !isset($data['nombre']) || !isset($data['apellido']) || !isset($data['email']) || !isset($data['telefono']) || !isset($data['rol']) || !isset($data['colony_id']) || !isset($data['activo'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    // Sanitizar y validar entradas
    $user_id = filter_var($data['user_id'], FILTER_VALIDATE_INT);
    $nombre = trim($data['nombre']);
    $apellido = trim($data['apellido']);
    $email = trim($data['email']);
    $telefono = trim($data['telefono']);
    $rol = trim($data['rol']);
    $colony_id = $data['colony_id'] !== '' ? filter_var($data['colony_id'], FILTER_VALIDATE_INT) : null;
    $activo = ($data['activo'] == '1' || $data['activo'] === 1 || $data['activo'] === true) ? 1 : 0;
    $password = isset($data['password']) ? trim($data['password']) : '';

    // Evitar que un usuario cambie su propio rol
    if ($user_id == $_SESSION['user_id']) {
        // Obtener el rol actual del usuario
        $stmt_rol = $con->prepare("SELECT rol FROM users WHERE id = :user_id");
        $stmt_rol->execute([':user_id' => $user_id]);
        $current_rol = $stmt_rol->fetchColumn();
        
        if ($current_rol !== $rol) {
            echo json_encode(['success' => false, 'message' => 'No puedes cambiar tu propio rol.']);
            exit;
        }
    }

    // Validar email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido.']);
        exit;
    }

    //Comprobacion si email ya existe en otro usuario
    $stmt_check = $con->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id != :user_id");
    $stmt_check->execute([':email' => $email, ':user_id' => $user_id]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado en otro usuario.']);
        exit;
    }

    // Hash de contraseña si se proporciona una nueva
    $password_hash = null;
    if (!empty($password)) {
        if (strlen($password) < 4) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 4 caracteres.']);
            exit;
        }
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
    }

    try{
        // Iniciar transacción para mantener consistencia
        $con->beginTransaction();

        // Actualizar datos del usuario
        if ($password_hash) {
            $stmt = $con->prepare("UPDATE users SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono, rol = :rol, colony_id = :colony_id, activo = :activo, pass = :pass WHERE id = :user_id");
            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':email' => $email,
                ':telefono' => $telefono,
                ':rol' => $rol,
                ':colony_id' => $colony_id,
                ':activo' => $activo,
                ':pass' => $password_hash,
                ':user_id' => $user_id
            ]);
        } else {
            $stmt = $con->prepare("UPDATE users SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono, rol = :rol, colony_id = :colony_id, activo = :activo WHERE id = :user_id");
            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':email' => $email,
                ':telefono' => $telefono,
                ':rol' => $rol,
                ':colony_id' => $colony_id,
                ':activo' => $activo,
                ':user_id' => $user_id
            ]);
        }

        // Gestión de la relación gestor-colonia
        if ($rol === 'gestor' && $colony_id) {
            // Limpiar gestor_id de todas las colonias que tenía este usuario
            $stmt_clear = $con->prepare("UPDATE colonies SET gestor_id = NULL WHERE gestor_id = :user_id");
            $stmt_clear->execute([':user_id' => $user_id]);

            // Asignar este usuario como gestor de la nueva colonia
            $stmt_assign = $con->prepare("UPDATE colonies SET gestor_id = :user_id WHERE id = :colony_id");
            $stmt_assign->execute([':user_id' => $user_id, ':colony_id' => $colony_id]);
        } else {
            // Si el rol NO es gestor o no hay colonia asignada, limpiar cualquier asignación previa
            $stmt_clear = $con->prepare("UPDATE colonies SET gestor_id = NULL WHERE gestor_id = :user_id");
            $stmt_clear->execute([':user_id' => $user_id]);
        }

        
        $con->commit();
        $_SESSION['success_message'] = 'Usuario actualizado exitosamente.';
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente.']);
    } catch (PDOException $e) {
        $con->rollBack();
        error_log('Error al actualizar usuario: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario. Por favor, inténtalo de nuevo.']);
        exit;
    }
}