<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . '/../../config/conexion.php';
$con = conectar();

$user_id = $_SESSION['user_id'];

// Obtener jaulas prestadas actualmente
$sql_jaulas = "SELECT COUNT(*) as jaulas_prestadas 
               FROM cage_loans 
               WHERE user_id = :user_id AND estado = 'prestado'";
$stmt_jaulas = $con->prepare($sql_jaulas);
$stmt_jaulas->execute([':user_id' => $user_id]);
$stats_jaulas = $stmt_jaulas->fetch(PDO::FETCH_ASSOC);


// JAULAS PRESTADAS AL USUARIO LOGEADO
$sql_user_jaulas = "SELECT cl.id, c.id, c.numero_interno, ct.nombre, cl.fecha_prestamo, cl.fecha_devolucion, cli.nombre as clinica_nombre
                    FROM cage_loans cl
                    JOIN cages c ON cl.cage_id = c.id
                    JOIN cage_types ct ON c.cage_type_id = ct.id
                    JOIN clinics cli ON cl.from_clinic_id = cli.id
                    WHERE cl.user_id = :user_id AND cl.estado = 'prestado'";
$stmt_user_jaulas = $con->prepare($sql_user_jaulas);
$stmt_user_jaulas->execute([':user_id' => $user_id]);
$user_jaulas = $stmt_user_jaulas->fetchAll(PDO::FETCH_ASSOC);

//JAULAS DISPONIBLES PARA RESERVAR
$sql_available_jaulas = "SELECT c.id, c.numero_interno, ct.nombre as tipo_nombre, cli.nombre as clinica_nombre
                         FROM cages c
                         JOIN cage_types ct ON c.cage_type_id = ct.id
                         JOIN clinics cli ON c.clinic_id = cli.id
                         LEFT JOIN cage_loans cl ON c.id = cl.cage_id AND cl.estado = 'prestado'
                         WHERE c.activo = 1 AND cl.id IS NULL";
$stmt_available_jaulas = $con->prepare($sql_available_jaulas);
$stmt_available_jaulas->execute();
$available_jaulas = $stmt_available_jaulas->fetchAll(PDO::FETCH_ASSOC);





?>

