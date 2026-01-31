<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();

$con = conectar();


//Paginación
$records_per_page = 15;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

if ($current_page < 1) {
    $current_page = 1;
}

$offset = ($current_page - 1) * $records_per_page;

// Obtener el total de jaulas
$sql_count = "SELECT COUNT(*) FROM cages";
$stmt_count = $con->prepare($sql_count);
$stmt_count->execute();
$total_records = $stmt_count->fetchColumn();

$total_pages = ceil($total_records / $records_per_page);


try {
    // Obtener todas las jaulas con información de tipo, clínica y préstamos activos
    $stmt = $con->prepare("
        SELECT 
            c.id,
            c.numero_interno,
            c.activo,
            ct.nombre as tipo_nombre,
            ct.id as tipo_id,
            cl.nombre as clinica_nombre,
            cl.id as clinica_id,
            cgl.id as prestamo_id,
            cgl.estado as prestamo_estado,
            u.nombre as voluntario_nombre,
            u.apellido as voluntario_apellido,
            col.nombre as colonia_nombre,
            col.code as colonia_code
        FROM cages c
        INNER JOIN cage_types ct ON c.cage_type_id = ct.id
        INNER JOIN clinics cl ON c.clinic_id = cl.id
        LEFT JOIN cage_loans cgl ON c.id = cgl.cage_id AND cgl.estado = 'prestado'
        LEFT JOIN users u ON cgl.user_id = u.id
        LEFT JOIN colonies col ON cgl.colony_id = col.id
        ORDER BY cl.nombre, ct.nombre, c.numero_interno
        LIMIT :limit OFFSET :offset
        
    ");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $cages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener tipos de jaula para filtros
    $stmt_types = $con->prepare("SELECT id, nombre FROM cage_types ORDER BY nombre");
    $stmt_types->execute();
    $cage_types = $stmt_types->fetchAll(PDO::FETCH_ASSOC);

    // Obtener clínicas para el formulario
    $stmt_clinics = $con->prepare("SELECT id, nombre FROM clinics WHERE activa = 1 ORDER BY nombre");
    $stmt_clinics->execute();
    $clinics = $stmt_clinics->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Error al cargar las jaulas: ' . $e->getMessage();
    $cages = [];
    $cage_types = [];
    $clinics = [];
}
