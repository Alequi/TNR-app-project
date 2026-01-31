<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();

$con = conectar();

//Obtener los datos de todas las campañas
try {
    $sql_campaigns = "SELECT 
                    c.id, 
                    c.nombre, 
                    c.fecha_inicio, 
                    c.fecha_fin,
                    c.activa
                    FROM campaigns c
                    ORDER BY c.fecha_inicio DESC";
    $stmt = $con->prepare($sql_campaigns);
    $stmt->execute();
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Error al cargar las campañas: ' . $e->getMessage();
}