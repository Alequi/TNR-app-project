<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();

$con = conectar();

//Paginacion
$records_per_page = 15;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

if ($current_page < 1) {
    $current_page = 1;
}

$offset = ($current_page - 1) * $records_per_page;

//Total de usuarios
$sql_count = "SELECT COUNT(*) FROM users";
$stmt_count = $con->prepare($sql_count);
$stmt_count->execute();
$total_records = $stmt_count->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);



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
        u.colony_id,
        col.nombre AS colonia_nombre,
        col.code AS colonia_code,
        COUNT(DISTINCT CASE WHEN b.estado IN ('reservado', 'entregado_vet', 'listo_recoger') THEN b.id END) AS reservas_activas,
        COUNT(DISTINCT CASE WHEN cl.estado = 'prestado' THEN cl.id END) AS jaulas_prestadas
    FROM users u
    LEFT JOIN colonies col ON u.colony_id = col.id
    LEFT JOIN bookings b ON u.id = b.user_id
    LEFT JOIN cage_loans cl ON u.id = cl.user_id
    GROUP BY u.id, u.nombre, u.apellido, u.email, u.rol, u.telefono, u.activo, u.colony_id, col.nombre, col.code
    ORDER BY u.nombre
    LIMIT :limit OFFSET :offset
";
$stmt = $con->prepare($sql_users);
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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