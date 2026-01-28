<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../config/conexion.php';
$con = conectar();
admin();

require_once __DIR__ . '/../../app/actions/bookings/bookings_stats_action.php';

$error = null;
$success = null;

if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
if (isset($_SESSION['success_message'])) {
    $success = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas | CES Gatos Elche</title>
    <link rel="icon" type="image/png" href="../../public/assets/brand/LOGO-CES-2.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="../../public/assets/dist/css/styles.css" rel="stylesheet" />
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-light border-bottom">
        <div class="container-xxl">
            <!-- LOGO -->
            <a href="/" class="navbar-brand d-inline-flex align-items-center">
                <img src="../../public/assets/brand/LOGO-CES-2.png" alt="Logo" width="100" height="auto" />
            </a>

            <!-- BOTÓN HAMBURGUESA -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- CONTENIDO COLAPSABLE -->
            <div class="collapse navbar-collapse" id="mainNavbar">


                <!--MENU PRINCIPAL VOLUNTARIO-->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="AdminPanel.php" class="nav-link">Panel</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Campañas</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Clínicas</a></li>
                    <li class="nav-item"><a href="booking.php" class="nav-link">Turnos</a></li>
                    <li class="nav-item"><a href="userBookings.php" class="nav-link active">Reservas</a></li>
                    <li class="nav-item"><a href="jaulas.php" class="nav-link">Jaulas</a></li>
                    <li class="nav-item"><a href="userColony.php" class="nav-link">Mi colonia</a></li>
                </ul>


                <!-- BOTÓN PERFIL -->
                <div class="d-flex align-items-center">
                    <!-- Perfil ESCRITORIO -->
                    <div class="dropdown d-none d-lg-block">
                        <button class="btn btn-primary d-flex gap-2" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle fs-6"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item d-inline-flex align-items-center gap-2" href="userProfile.php">
                                    <i class="bi bi-gear fs-5 text-secondary"></i> Ajustes de cuenta
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-inline-flex align-items-center gap-2 text-danger" href="../../app/actions/auth/logout_action.php">
                                    <i class="bi bi-box-arrow-right fs-5"></i> Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Perfil MÓVIL -->
                    <div class="d-lg-none ms-2">
                        <a href="userProfile.php" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-person-circle"></i> Perfil
                        </a>
                        <a href="../../app/actions/auth/logout_action.php" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-2">
                            Cerrar sesión<i class="bi bi-box-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End navbar -->

    <!-- Breadcrumb -->
    <div class="container-xxl mt-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-dark opacity-30" href="panel.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mis Reservas</li>
            </ol>
        </nav>
    </div>
    <!-- End Breadcrumb -->
    <!--MAIN CONTENT-->
    <main class="container-xxl my-4 flex-grow-1 mt-3">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6 fw-bold">
                        <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i> Reservas
                    </h1>
                    <p class="text-muted">Historial de reservas de turnos</p>
                </div>
                <div>
                    <a href="booking.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Reserva
                    </a>
                </div>
            </div>
        </div>

        <!--MENSAJES DE ERROR / ÉXITO-->
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <!--END MENSAJES DE ERROR / ÉXITO-->

        <!-- Tabla de reservas -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Reservas
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash text-primary me-1"></i>ID</th>
                                <th><i class="bi bi-clock text-primary me-1"></i>Fecha Creación</th>
                                <th><i class="bi bi-calendar-check text-primary me-1"></i>Fecha Turno</th>
                                <th><i class="bi bi-hospital text-primary me-1"></i>Clínica</th>
                                <th><i class="bi bi-geo-alt-fill text-primary me-1"></i>Colonia</th>
                                <th><i class="bi bi-person text-primary me-1"></i>Voluntario</th>
                                <th class="text-center"><i class="bi bi-cat text-primary me-1"></i>Gatos</th>
                                <th class="text-center"><i class="bi bi-info-circle text-primary me-1"></i>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($all_bookings)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <h5>No hay reservas registradas</h5>
                                            <p class="mb-0">Aún no han realizado ninguna reserva de turno.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($all_bookings as $booking): ?>
                                    <tr>
                                        <td class="fw-bold">#<?= htmlspecialchars($booking['id']) ?></td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($booking['fecha'])) ?>
                                            <span class="badge text-dark bg-light ms-1">
                                                <?= $booking['turno'] === 'M' ? '☀️ Mañana' : '🌙 Tarde' ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($booking['clinic_name']) ?></td>
                                        <td><?= htmlspecialchars($booking['colony_name']) ?></td>
                                        <td><?= htmlspecialchars($booking['volunteer_name']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-dark fs-6">
                                                <?= (int)$booking['gatos'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $badge_class = match ($booking['estado']) {
                                                'reservado' => 'bg-warning text-dark',
                                                'entregado_vet' => 'bg-info text-dark',
                                                'listo_recoger' => 'bg-success',
                                                'recogido' => 'bg-secondary',
                                                'cancelado' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };

                                            $estado_text = match ($booking['estado']) {
                                                'reservado' => 'Reservado',
                                                'entregado_vet' => 'En Clínica',
                                                'listo_recoger' => 'Listo',
                                                'recogido' => 'Completado',
                                                'cancelado' => 'Cancelado',
                                                default => $booking['estado']
                                            };
                                            ?>
                                            <select class="form-select form-select-sm badge <?= $badge_class ?> changeStatusBtn"
                                                style="width: auto; display: inline-block; border: none; padding: 0.5rem 2rem 0.5rem 0.5rem;"
                                                data-booking-id="<?= htmlspecialchars($booking['id']) ?>">
                                                <option value="reservado" <?= $booking['estado'] === 'reservado' ? 'selected' : '' ?>>Reservado</option>
                                                <option value="entregado_vet" <?= $booking['estado'] === 'entregado_vet' ? 'selected' : '' ?>>En Clínica</option>
                                                <option value="listo_recoger" <?= $booking['estado'] === 'listo_recoger' ? 'selected' : '' ?>>Listo</option>
                                                <option value="recogido" <?= $booking['estado'] === 'recogido' ? 'selected' : '' ?>>Completado</option>
                                                <option value="cancelado" <?= $booking['estado'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($booking['estado'] === 'reservado'): ?>
                                                <button class="btn btn-sm btn-outline-danger cancelBookingBtn"
                                                    data-booking-id="<?= htmlspecialchars($booking['id']) ?>"
                                                    data-action-url="../../app/actions/bookings/cancel_booking_action.php">
                                                    <i class="bi bi-x-circle"></i> Cancelar
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Resumen estadístico -->
        <?php if (!empty($all_bookings)): ?>
            <div class="row mt-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-check text-primary fs-1"></i>
                            <h3 class="mt-2 mb-0"><?= $pending_count ?></h3>
                            <p class="text-muted mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-hospital text-info fs-1"></i>
                            <h3 class="mt-2 mb-0"><?= $in_clinic_count ?></h3>
                            <p class="text-muted mb-0">En Clínica</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle text-success fs-1"></i>
                            <h3 class="mt-2 mb-0"><?= $completed_count ?></h3>
                            <p class="text-muted mb-0">Completadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-cat text-warning fs-1"></i>
                            <h3 class="mt-2 mb-0"><?= $total_gatos ?></h3>
                            <p class="text-muted mb-0">Total Gatos</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </main>
    <!--END MAIN CONTENT-->

    <!-- FOOTER -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container d-flex flex-column flex-md-row flex-wrap 
              justify-content-center justify-content-md-between 
              align-items-center text-center text-md-start">
            <div class="col-md-4 d-flex align-items-center justify-content-center justify-content-md-start mb-3 mb-md-0">
                <a href="/" class="mb-3 me-2 mb-md-0 text-light text-decoration-none lh-1">
                    <img src="../../public/assets/brand/LOGO-CES-2.png" alt="Logo" width="50" height="auto" />
                </a>
                <span class="mb-3 mb-md-0">&copy; 2025 CES Gatos Elche</span>
            </div>
            <ul class="nav justify-content-center justify-content-md-end list-unstyled d-flex">
                <li class="ms-3">
                    <a class="text-light" href="#" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-instagram" viewBox="0 0 16 16">
                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                        </svg>
                    </a>
                </li>
                <li class="ms-3">
                    <a class="text-light" href="#" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-facebook" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/js/close-alerts.js"></script>
    <script src="../../public/assets/js/cancelBooking.js"></script>
    <script src="../../public/assets/js/updateBooking.js"></script>
</body>

</html>