<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . '/../../../config/conexion.php';
$con = conectar();

$user_id = $_SESSION['user_id'];

// Obtener jaulas prestadas actualmente
$sql_jaulas = "SELECT COUNT(*) as jaulas_prestadas 
               FROM cage_loans 
               WHERE user_id = :user_id AND estado = 'prestado'";
$stmt_jaulas = $con->prepare($sql_jaulas);
$stmt_jaulas->execute([':user_id' => $user_id]);
$stats_jaulas = $stmt_jaulas->fetch(PDO::FETCH_ASSOC);

// CANTIDAD DE JAULAS POR TIPO PRESTADAS AL USUARIO
$sql_jaulas_por_tipo = "SELECT ct.nombre as tipo_nombre, COUNT(*) as cantidad
                        FROM cage_loans cl
                        JOIN cages c ON cl.cage_id = c.id
                        JOIN cage_types ct ON c.cage_type_id = ct.id
                        WHERE cl.user_id = :user_id AND cl.estado = 'prestado'
                        GROUP BY ct.id, ct.nombre";
$stmt = $con->prepare($sql_jaulas_por_tipo);
$stmt->execute([':user_id' => $user_id]);
$jaulas_por_tipo = $stmt->fetchAll(PDO::FETCH_ASSOC);


// JAULAS PRESTADAS AL USUARIO LOGEADO
$sql_user_jaulas = "SELECT cl.id as loan_id, c.id as cage_id, c.numero_interno, ct.nombre, cl.fecha_prestamo, cl.fecha_prevista_devolucion, cli.nombre as clinica_nombre
                    FROM cage_loans cl
                    JOIN cages c ON cl.cage_id = c.id
                    JOIN cage_types ct ON c.cage_type_id = ct.id
                    JOIN clinics cli ON cl.from_clinic_id = cli.id
                    WHERE cl.user_id = :user_id AND cl.estado = 'prestado'
                    ORDER BY cl.fecha_prestamo ASC";
$stmt_user_jaulas = $con->prepare($sql_user_jaulas);
$stmt_user_jaulas->execute([':user_id' => $user_id]);
$user_jaulas = $stmt_user_jaulas->fetchAll(PDO::FETCH_ASSOC);

//PAGINACION JAULAS DISPONIBLES
$records_per_page = 15;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

if ($current_page < 1) {
    $current_page = 1;
}

$offset = ($current_page - 1) * $records_per_page;

//Total de jaulas disponibles
$sql_total_jaulas = "SELECT COUNT(*) AS total_jaulas FROM cages c
                     LEFT JOIN cage_loans cl ON c.id = cl.cage_id AND cl.estado = 'prestado'
                     WHERE c.activo = 1 AND cl.id IS NULL";
$stmt_total_jaulas = $con->prepare($sql_total_jaulas);
$stmt_total_jaulas->execute();
$total_jaulas_result = $stmt_total_jaulas->fetch(PDO::FETCH_ASSOC);
$total_jaulas = $total_jaulas_result['total_jaulas'];
$total_pages = ceil($total_jaulas / $records_per_page);


//JAULAS DISPONIBLES PARA RESERVAR
$sql_available_jaulas = "SELECT c.id, c.numero_interno, ct.nombre as tipo_nombre, cli.nombre as clinica_nombre
                         FROM cages c
                         JOIN cage_types ct ON c.cage_type_id = ct.id
                         JOIN clinics cli ON c.clinic_id = cli.id
                         LEFT JOIN cage_loans cl ON c.id = cl.cage_id AND cl.estado = 'prestado'
                         WHERE c.activo = 1 AND cl.id IS NULL
                        LIMIT :limit OFFSET :offset";
$stmt_available_jaulas = $con->prepare($sql_available_jaulas);
$stmt_available_jaulas->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt_available_jaulas->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt_available_jaulas->execute();
$available_jaulas = $stmt_available_jaulas->fetchAll(PDO::FETCH_ASSOC);

//PRESTAMOS JAULA

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jaula_id'])) {
    $jaula_id = $_POST['jaula_id'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_devolucion = $_POST['fecha_devolucion'];

    try {
        // Verificar si la jaula está disponible
        $sql_check = "SELECT COUNT(*) as count FROM cage_loans WHERE cage_id = :jaula_id AND estado = 'prestado'";
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->execute([':jaula_id' => $jaula_id]);
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($result_check['count'] == 0) {
            // Insertar el préstamo - from_clinic_id es la clínica de la jaula
            $sql_insert = "INSERT INTO cage_loans (cage_id, from_clinic_id, user_id, colony_id, fecha_prestamo, fecha_prevista_devolucion, estado) 
                           VALUES (:jaula_id, (SELECT clinic_id FROM cages WHERE id = :jaula_id), :user_id, NULL, :fecha_prestamo, :fecha_devolucion, 'prestado')";
            $stmt_insert = $con->prepare($sql_insert);
            $stmt_insert->execute([
                ':jaula_id' => $jaula_id, 
                ':user_id' => $user_id, 
                ':fecha_prestamo' => $fecha_prestamo, 
                ':fecha_devolucion' => $fecha_devolucion
            ]);

            // Actualizar el stock de la jaula en las clínicas
            $sql_update_stock = "UPDATE clinic_cages 
                                 SET cantidad_prestada = cantidad_prestada + 1, cantidad_total = cantidad_total - 1
                                 WHERE clinic_id = (SELECT clinic_id FROM cages WHERE id = :jaula_id)
                                   AND cage_type_id = (SELECT cage_type_id FROM cages WHERE id = :jaula_id)";
            $stmt_update_stock = $con->prepare($sql_update_stock);
            $stmt_update_stock->execute([':jaula_id' => $jaula_id]);

            $_SESSION['success_message'] = "Jaula reservada con éxito.";
            header("Location: ../../../views/jaulas.php");
            exit();
        } else {
            $_SESSION['error_message'] = "La jaula ya está reservada.";
            header("Location: ../../../views/jaulas.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error al reservar la jaula: " . $e->getMessage();
        header("Location: ../../../views/listadoJaulas.php");
        exit();
    }
}

// DEVOLVER JAULA
if (isset($_GET['action']) && $_GET['action'] === 'return' && isset($_GET['id'])) {
    $loan_id = $_GET['id'];

    // Obtener información del préstamo
    $sql_get_loan = "SELECT cage_id FROM cage_loans WHERE id = :loan_id AND user_id = :user_id AND estado = 'prestado'";
    $stmt_get_loan = $con->prepare($sql_get_loan);
    $stmt_get_loan->execute([':loan_id' => $loan_id, ':user_id' => $user_id]);
    $loan = $stmt_get_loan->fetch(PDO::FETCH_ASSOC);

    if ($loan) {
        $cage_id = $loan['cage_id'];
        
        try {
            // Actualizar el estado del préstamo a 'devuelto' y fecha
            $sql_update_loan = "UPDATE cage_loans SET estado = 'devuelto', fecha_devolucion = NOW() WHERE id = :loan_id";
            $stmt_update_loan = $con->prepare($sql_update_loan);
            $stmt_update_loan->execute([':loan_id' => $loan_id]);

            // Actualizar el stock de la jaula en las clínicas
            $sql_update_stock = "UPDATE clinic_cages 
                                 SET cantidad_prestada = cantidad_prestada - 1, cantidad_total = cantidad_total + 1
                                 WHERE clinic_id = (SELECT clinic_id FROM cages WHERE id = :cage_id)
                                   AND cage_type_id = (SELECT cage_type_id FROM cages WHERE id = :cage_id)";
            $stmt_update_stock = $con->prepare($sql_update_stock);
            $stmt_update_stock->execute([':cage_id' => $cage_id]);

            $_SESSION['success_message'] = "Jaula devuelta con éxito.";
            header("Location: ../../../views/jaulas.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al devolver la jaula: " . $e->getMessage();
            header("Location: ../../../views/jaulas.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Préstamo no encontrado.";
        header("Location: ../../../views/jaulas.php");
        exit();
    }
}





?>

