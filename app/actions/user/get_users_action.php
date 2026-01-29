<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();

$con = conectar();

//Obtener todos los usuarios con sus reservas activas y prestamos activos
try {

$sql_users = "
    SELECT 
        u.id,
        u.nombre,
        u.apellido,
        u.email,
        u.rol,
        u.telefono,
        u.activo,
        col.nombre AS colonia_nombre,
        col.code AS colonia_code,
        COUNT(DISTINCT CASE WHEN b.estado IN ('reservado', 'entregado_vet', 'listo_recoger') THEN b.id END) AS reservas_activas,
        COUNT(DISTINCT CASE WHEN cl.estado = 'prestado' THEN cl.id END) AS jaulas_prestadas
    FROM users u
    LEFT JOIN colonies col ON u.colony_id = col.id
    LEFT JOIN bookings b ON u.id = b.user_id
    LEFT JOIN cage_loans cl ON u.id = cl.user_id
    GROUP BY u.id, u.nombre, u.apellido, u.email, u.rol, u.telefono, u.activo, col.nombre, col.code
    ORDER BY u.nombre
";
$stmt = $con->prepare($sql_users);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Error al cargar los usuarios: ' . $e->getMessage();
    $users = [];
}

// Obtener todas las colonias para el select del modal
try {
    $stmt_colonies = $con->prepare("SELECT id, code, nombre FROM colonies ORDER BY code");
    $stmt_colonies->execute();
    $colonies = $stmt_colonies->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $colonies = [];
}