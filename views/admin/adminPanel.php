<?php
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../app/actions/jaulas/jaulas_general_action.php';
require_once __DIR__ . '/../../app/actions/clinics/general_clinics_action.php';
require_once __DIR__ . '/../../app/actions/bookings/bookings_stats_action.php';
require_once __DIR__ . '/../../app/actions/user/volunteers_stats_action.php';
require_once __DIR__ . '/../../app/actions/user/colonies_stats_action.php';
require_once __DIR__ . '/../../app/actions/campaign_stats_action.php';

admin();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | CES Gatos Elche</title>
    <link rel="icon" type="image/png" href="../../public/assets/brand/LOGO-CES-2.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
            <div class="collapse navbar-collapse " id="mainNavbar">


                <!-- MENÚ PRINCIPAL -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="adminPanel.php" class="nav-link active">Panel</a></li>
                    <li class="nav-item"><a href="campaignsAdmin.php" class="nav-link">Campañas</a></li>
                    <li class="nav-item"><a href="clinicsAdmin.php" class="nav-link">Clínicas</a></li>
                    <li class="nav-item"><a href="coloniesAdmin.php" class="nav-link">Colonias</a></li>
                    <li class="nav-item"><a href="shiftsAdmin.php" class="nav-link">Turnos</a></li>
                    <li class="nav-item"><a href="bookingAdmin.php" class="nav-link">Reservas</a></li>
                    <li class="nav-item"><a href="jaulasAdmin.php" class="nav-link">Jaulas</a></li>
                    <li class="nav-item"><a href="usersAdmin.php" class="nav-link">Usuarios</a></li>
                </ul>

                
                <!-- BOTÓN PERFIL -->
                <div class="d-flex align-items-center">

                    <!-- Perfil ESCRITORIO -->
                    <div class="dropdown d-none d-lg-block">
                        <button class="btn btn-primary d-flex  gap-2" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle fs-6"></i></button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item d-inline-flex align-items-center gap-2" href="../userProfile.php">
                                    <i class="bi bi-gear fs-5 text-secondary"></i> Ajustes de cuenta</a>
                            </li>
                            <li>
                                <a class="dropdown-item d-inline-flex align-items-center gap-2 text-danger" href="../../app/actions/auth/logout_action.php">
                                    <i class="bi bi-box-arrow-right fs-5"></i> Cerrar sesión</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Perfil MÓVIL: aparece dentro del menú colapsado -->
                    <div class="d-lg-none ms-2">
                        <a href="../userProfile.php" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-person-circle"></i> Perfil</a>

                        <a href="../../app/actions/auth/logout_action.php" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-2">
                            Cerrar sesión<i class="bi bi-box-arrow-right "></i></a>

                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- End navbar -->
    <!-- Breadcrumb -->
    <div class="container-xxl my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-dark opacity-30" href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Panel</li>
            </ol>
        </nav>
    </div>

    <!-- End Breadcrumb -->


    <div class="container-xxl my-4 flex-grow-1 mt-3">
        <div class="text-center mb-4">
            <h3 class="mb-2 fw-bold">Hola, <?php echo $_SESSION['nombre']; ?> 🐱</h3>
            <p class="text-muted">Gestiona y mantente informado sobre el estado de la Campaña CER</p>
        </div>


        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 me-3 rounded">
                        <i class="bi bi-calendar-event text-primary fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Campaña activa: <?php echo htmlspecialchars($nombre_campaign_active); ?></h5>
                        <p class="text-muted mb-0">
                            <i class="bi bi-calendar-range me-1"></i>
                            <?php echo htmlspecialchars($fecha_inicio_campaign_active); ?> - <?php echo htmlspecialchars($fecha_fin_campaign_active); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <!--CARDS-->
        <div class="row g-4 mt-2">
            <div class="col-md-5">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 p-3 me-3">
                                <i class="bi bi-box text-success fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">Jaulas prestadas</h5>
                                <p class="text-muted small mb-0"><?php echo $stats['total_jaulas_prestadas']; ?>/<?php echo $total_jaulas['total_jaulas']; ?> Jaulas prestadas</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <?php foreach ($jaulas_por_tipo as $jaula_tipo): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted"><?php echo htmlspecialchars($jaula_tipo['tipo_nombre']); ?></span>
                                    <span class="badge bg-success"><?php echo htmlspecialchars($jaula_tipo['cantidad']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-auto">
                            <a href="jaulasAdmin.php" class="btn btn-success w-100">
                                Ver Jaulas <i class="bi bi-arrow-right-circle ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <!--CARD 2-->
            <div class="col-md-7">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-hospital text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">Ocupación Clínicas</h5>
                                <p class="text-muted small mb-0">Estado actual de las clínicas</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <?php foreach ($clinics_stats as $clinic): ?>
                                <!-- CLÍNICAS -->
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-building text-primary me-2"></i>
                                            <p class="fw-semibold mb-0"><?php echo htmlspecialchars($clinic['clinic_name']); ?></p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-muted small">☀️ Turno mañana</span>
                                            <?php if ($clinic['ocupados_ma'] / $clinic['capacidad_ma'] == 1): ?>
                                                <span class="badge bg-danger"><?php echo htmlspecialchars($clinic['ocupados_ma']); ?>/<?php echo htmlspecialchars($clinic['capacidad_ma']); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><?php echo htmlspecialchars($clinic['ocupados_ma']); ?>/<?php echo htmlspecialchars($clinic['capacidad_ma']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">🌙 Turno tarde</span>
                                            <?php if ($clinic['ocupados_ta'] / $clinic['capacidad_ta'] == 1): ?>
                                                <span class="badge bg-danger"><?php echo htmlspecialchars($clinic['ocupados_ta']); ?>/<?php echo htmlspecialchars($clinic['capacidad_ta']); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($clinic['ocupados_ta']); ?>/<?php echo htmlspecialchars($clinic['capacidad_ta']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                        <div class="mt-auto">
                            <a href="bookingAdmin.php" class="btn btn-primary w-100">
                                Ver reservas <i class="bi bi-arrow-right-circle ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row mt-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check text-primary fs-1"></i>
                        <h3 class="mt-2 mb-0"><?php echo htmlspecialchars($total_reservas_activas); ?></h3>
                        <p class="text-muted mb-0">Reservas Hoy</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people text-success fs-1"></i>
                        <h3 class="mt-2 mb-0"><?php echo htmlspecialchars($total_voluntarios); ?></h3>
                        <p class="text-muted mb-0">Voluntarios</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-geo-alt text-info fs-1"></i>
                        <h3 class="mt-2 mb-0"><?php echo htmlspecialchars($total_colonias); ?></h3>
                        <p class="text-muted mb-0">Colonias</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-cat text-warning fs-1">🐈</i>
                        <h3 class="mt-2 mb-0"><?php echo htmlspecialchars($total_gatos_mes); ?></h3>
                        <p class="text-muted mb-0">Gatos castrados este mes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de reservas -->
    <div class="container-xxl mt-4 mb-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-calendar-event me-2"></i>Próximas Reservas de Turnos (7 días)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-clock text-primary me-1"></i>Fecha</th>
                                <th><i class="bi bi-hospital text-primary me-1"></i>Clínica</th>
                                <th><i class="bi bi-sun text-primary me-1"></i>Turno</th>
                                <th><i class="bi bi-geo-alt-fill text-primary me-1"></i>Colonia</th>
                                <th><i class="bi bi-person text-primary me-1"></i>Voluntario</th>
                                <th class="text-center"><i class="bi bi-cat text-primary me-1"></i>Gatos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reservas_proximos_dias)): ?>
                                <tr></tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="mb-0 text-muted">No hay reservas próximas en los próximos 7 días.</p>
                                </td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($reservas_proximos_dias as $reserva): ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($reserva['fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['clinic_name']); ?></td>
                                    <td><span class="badge text-dark bg-light"><?php echo htmlspecialchars($reserva['turno']); ?></span></td>
                                    <td><?php echo htmlspecialchars($reserva['colony_name']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['volunteer_name']); ?></td>
                                    <td class="text-center"><span class="badge bg-info text-dark fs-6"><?php echo htmlspecialchars($reserva['gatos']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
                <!-- ============ PAGINACIÓN  ============ -->
        <?php if ($total_pages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    
                    <!-- Botón Anterior -->
                    <?php if ($current_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $current_page - 1 ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <!-- Números de página -->
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Botón Siguiente -->
                    <?php if ($current_page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $current_page + 1 ?>">Siguiente</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </nav>
        <?php endif; ?>
        <!-- ============ END PAGINACIÓN ============ -->
        </div>
    </div>
    <!-- FOOTER -->

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container d-flex flex-column flex-md-row flex-wrap 
              justify-content-center justify-content-md-between 
              align-items-center text-center text-md-start">

            <div
                class="col-md-4 d-flex align-items-center justify-content-center justify-content-md-start mb-3 mb-md-0">
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
                            <path
                                d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                        </svg>
                    </a>
                </li>
                <li class="ms-3">
                    <a class="text-light" href="#" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-facebook" viewBox="0 0 16 16">
                            <path
                                d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                        </svg>
                    </a>
                </li>
            </ul>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>