<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';

admin();

$con = conectar();

//Obtener los datos de todas las colonias
try {

      $sql_colony = "SELECT 
                    c.id, 
                    c.code, 
                    c.nombre,
                    c.zona, 
                    c.gestor_id,
                    gestor.nombre AS gestor_nombre,
                    COUNT(DISTINCT voluntarios.id) AS num_voluntarios
                    FROM colonies c
                    LEFT JOIN users gestor ON c.gestor_id = gestor.id
                    LEFT JOIN users voluntarios ON c.id = voluntarios.colony_id
                    GROUP BY c.id, c.code, c.nombre, c.zona, c.gestor_id, gestor.nombre
                    ORDER BY c.nombre";
    $stmt = $con->prepare($sql_colony);
    $stmt->execute();
    $colonies = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Error al cargar las colonias: ' . $e->getMessage();
    }


// Obtener gestores para el dropdown de coloniesAdmin.php
$stmt_gestores = $con->prepare("SELECT id, nombre, apellido FROM users WHERE rol IN ('gestor', 'admin') ORDER BY nombre");
$stmt_gestores->execute();
$gestores = $stmt_gestores->fetchAll(PDO::FETCH_ASSOC);

//Obtener voluntarios  para asignar colonia
$stmt_voluntarios = $con->prepare("SELECT id, nombre, apellido FROM users  ORDER BY nombre");
$stmt_voluntarios->execute();
$voluntarios = $stmt_voluntarios->fetchAll(PDO::FETCH_ASSOC);
