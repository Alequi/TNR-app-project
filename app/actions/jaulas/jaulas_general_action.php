<?php
require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

// Obtener estadísticas generales de jaulas
$sql_stats = "SELECT 
                (SELECT COUNT(*) FROM cage_loans WHERE estado = 'prestado') as total_jaulas_prestadas,
                (SELECT COUNT(*) FROM cages WHERE activo = 1) as total_jaulas_disponibles
              ";
$stmt_stats = $con->prepare($sql_stats);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

//Total de jaulas

$sql_total_jaulas = "SELECT COUNT(*) as total_jaulas FROM cages WHERE activo = 1";
$stmt_total_jaulas = $con->prepare($sql_total_jaulas);
$stmt_total_jaulas->execute();
$total_jaulas = $stmt_total_jaulas->fetch(PDO::FETCH_ASSOC);


//Jaulas prestadas por tipo
$sql_jaulas_por_tipo = "SELECT ct.nombre as tipo_nombre, COUNT(*) as cantidad
                        FROM cage_loans cl
                        JOIN cages c ON cl.cage_id = c.id
                        JOIN cage_types ct ON c.cage_type_id = ct.id
                        WHERE cl.estado = 'prestado'
                        GROUP BY ct.id, ct.nombre";
$stmt_jaulas_por_tipo = $con->prepare($sql_jaulas_por_tipo);
$stmt_jaulas_por_tipo->execute();
$jaulas_por_tipo = $stmt_jaulas_por_tipo->fetchAll(PDO::FETCH_ASSOC);

