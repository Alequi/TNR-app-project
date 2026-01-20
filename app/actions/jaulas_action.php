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





?>

