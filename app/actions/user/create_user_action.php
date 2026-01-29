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
    if (!$data || !isset($data['nombre']) || !isset($data['apellido']) || !isset($data['email']) || !isset($data['password']) || !isset($data['telefono']) || !isset($data['rol'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    // Sanitizar y validar entradas
    $nombre = trim($data['nombre']);
    $apellido = trim($data['apellido']);
    $email = trim($data['email']);
    $password = trim($data['password']);
    $telefono = trim($data['telefono']);
    $rol = trim($data['rol']);
    $colony_id = isset($data['colony_id']) && $data['colony_id'] !== '' ? 
                 filter_var($data['colony_id'], FILTER_VALIDATE_INT) : null;

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido.']);
        exit;
    }

    // Validar contraseña
    if (strlen($password) < 4) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 4 caracteres.']);
        exit;
    }

    // Validar que el usuario no exista ya en la base de datos
    $stmt_check = $con->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt_check->execute([':email' => $email]);
    
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado.']);
        exit;
    }

    // Hash de contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $con->prepare("INSERT INTO users (nombre, apellido, email, pass, rol, telefono, colony_id, activo) 
        VALUES (:nombre, :apellido, :email, :pass, :rol, :telefono, :colony_id, 1)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':pass' => $password_hash,
            ':rol' => $rol,
            ':telefono' => $telefono,
            ':colony_id' => $colony_id
        ]);

        $_SESSION['success_message'] = 'Usuario creado con éxito.';
        echo json_encode(['success' => true, 'message' => 'Usuario creado con éxito.']);
        exit;

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al crear el usuario: ' . $e->getMessage()]);
        exit;
    }
}
