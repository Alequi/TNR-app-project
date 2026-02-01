<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

isLoggedIn();

$con = conectar();
$user_id = $_SESSION['user_id'];

// Obtener datos del usuario
$stmt_user = $con->prepare("SELECT nombre, apellido, email FROM users WHERE id = :user_id");
$stmt_user->execute([':user_id' => $user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Obtener reservas
$stmt = $con->prepare("
    SELECT 
        b.id,
        b.gatos_count,
        b.estado,
        b.fecha_drop,
        b.turno_drop,
        b.fecha_pick,
        b.turno_pick,
        b.created_at,
        c.nombre AS colonia_nombre,
        cl.nombre AS clinica_nombre,
        s.fecha AS shift_fecha,
        s.turno AS shift_turno
    FROM bookings b
    INNER JOIN colonies c ON b.colony_id = c.id
    INNER JOIN shifts s ON b.shift_id = s.id
    INNER JOIN clinics cl ON s.clinic_id = cl.id
    WHERE b.user_id = :user_id
    ORDER BY b.created_at DESC
");
$stmt->execute([':user_id' => $user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF con TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Configuración del documento
$pdf->SetCreator('CES Gatos Elche');
$pdf->SetAuthor('CES Gatos Elche');
$pdf->SetTitle('Historial de Reservas');

// Quitar encabezado y pie de página predeterminados
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Márgenes
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 15);

// Agregar página
$pdf->AddPage();

// Título - Color verde primary (#198754)
$pdf->SetFont('helvetica', 'B', 20);
$pdf->SetTextColor(25, 135, 84); // RGB de #198754
$pdf->Cell(0, 10, 'Historial de Reservas', 0, 1, 'C');
$pdf->Ln(5);

// Información del usuario
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(248, 249, 250);
$pdf->Cell(0, 8, 'Voluntario: ' . $user['nombre'] . ' ' . $user['apellido'], 0, 1, 'L', true);
$pdf->Cell(0, 8, 'Email: ' . $user['email'], 0, 1, 'L', true);
$pdf->Cell(0, 8, 'Fecha: ' . date('d/m/Y H:i'), 0, 1, 'L', true);
$pdf->Ln(5);

if (!empty($bookings)) {
    // Encabezados de tabla - Color verde primary (#198754)
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetFillColor(25, 135, 84); // RGB de #198754
    $pdf->SetTextColor(255, 255, 255);
    
    $pdf->Cell(15, 8, 'ID', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Colonia', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Clinica', 1, 0, 'C', true);
    $pdf->Cell(28, 8, 'Turno', 1, 0, 'C', true);
    $pdf->Cell(28, 8, 'Entrega', 1, 0, 'C', true);
    $pdf->Cell(28, 8, 'Recogida', 1, 0, 'C', true);
    $pdf->Ln();

    // Datos
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    
    foreach ($bookings as $booking) {
        $turno = $booking['shift_turno'] === 'M' ? 'Manana' : 'Tarde';
        $drop = $booking['turno_drop'] === 'M' ? 'M' : 'T';
        $pick = $booking['turno_pick'] === 'M' ? 'M' : 'T';
        
        $pdf->Cell(15, 7, '#' . $booking['id'], 1, 0, 'C');
        $pdf->Cell(40, 7, substr($booking['colonia_nombre'], 0, 25), 1, 0, 'L');
        $pdf->Cell(40, 7, substr($booking['clinica_nombre'], 0, 25), 1, 0, 'L');
        $pdf->Cell(28, 7, date('d/m/Y', strtotime($booking['shift_fecha'])) . ' ' . substr($turno, 0, 1), 1, 0, 'C');
        $pdf->Cell(28, 7, date('d/m/Y', strtotime($booking['fecha_drop'])) . ' ' . $drop, 1, 0, 'C');
        $pdf->Cell(28, 7, date('d/m/Y', strtotime($booking['fecha_pick'])) . ' ' . $pick, 1, 0, 'C');
        $pdf->Ln();
    }
    
    // Resumen
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(248, 249, 250);
    $pdf->Cell(0, 8, 'Total de reservas: ' . count($bookings), 0, 1, 'L', true);
    $pdf->Cell(0, 8, 'Total de gatos: ' . array_sum(array_column($bookings, 'gatos_count')), 0, 1, 'L', true);
} else {
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'No tienes reservas registradas.', 0, 1, 'C');
}

// Salida
$pdf->Output('reservas_' . date('Ymd_His') . '.pdf', 'D');