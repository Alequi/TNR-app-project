<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';
login();


$con = conectar();

//Obtener los datos de todas las clínicas
try {
    $sql_clinics = "SELECT 
                    c.id, 
                    c.nombre, 
                    c.direccion, 
                    c.telefono, 
                    c.capacidad_ma, 
                    c.capacidad_ta,
                    c.activa,
                    COALESCE(SUM(cages.cantidad_total), 0) as total_jaulas,
                    COALESCE(SUM(cages.cantidad_prestada), 0) as jaulas_prestadas
                    FROM clinics c
                    LEFT JOIN clinic_cages cages ON c.id = cages.clinic_id
                    GROUP BY c.id, c.nombre, c.direccion, c.telefono, c.capacidad_ma, c.capacidad_ta, c.activa
                    ORDER BY c.nombre";
    $stmt = $con->prepare($sql_clinics);
    $stmt->execute();
    $clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Error al cargar las clínicas: ' . $e->getMessage();
}
