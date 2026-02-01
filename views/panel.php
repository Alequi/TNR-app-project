<?php


require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/actions/jaulas/jaulas_action.php';
require_once __DIR__ . '/../app/actions/user/user_action.php';
require_once __DIR__ . '/../app/actions/bookings/user_bookings_action.php';
require_once __DIR__ . '/../app/actions/campaign_stats_action.php';
require_once __DIR__ . '/../app/actions/weather/weather.php';

login();
isLoggedIn()
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | CES Gatos Elche</title>
    <link rel="icon" type="image/png" href="../public/assets/brand/LOGO-CES-2.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="../public/assets/dist/css/styles.css" rel="stylesheet" />

</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-light border-bottom">
        <div class="container-xxl">

            <!-- LOGO -->
            <a href="/" class="navbar-brand d-inline-flex align-items-center">
                <img src="../public/assets/brand/LOGO-CES-2.png" alt="Logo" width="100" height="auto" />
            </a>

            <!-- BOTÓN HAMBURGUESA -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- CONTENIDO COLAPSABLE -->
            <div class="collapse navbar-collapse " id="mainNavbar">

                <!-- ENLACES DEL MENÚ -->

                 <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="panel.php" class="nav-link active">Panel</a></li>
                    <li class="nav-item"><a href="clinics.php" class="nav-link">Clínicas</a></li>
                    <li class="nav-item"><a href="booking.php" class="nav-link">Turnos</a></li>
                    <li class="nav-item"><a href="userBookings.php" class="nav-link">Mis reservas</a></li>
                    <li class="nav-item"><a href="jaulas.php" class="nav-link">Jaulas</a></li>
                    <li class="nav-item"><a href="userColony.php" class="nav-link">Mi colonia</a></li>
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
                                <a class="dropdown-item d-inline-flex align-items-center gap-2" href="userProfile.php">
                                    <i class="bi bi-gear fs-5 text-secondary"></i> Ajustes de cuenta</a>
                            </li>
                            <li>
                                <a class="dropdown-item d-inline-flex align-items-center gap-2 text-danger" href="../app/actions/auth/logout_action.php">
                                    <i class="bi bi-box-arrow-right fs-5"></i> Cerrar sesión</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Perfil MÓVIL: aparece dentro del menú colapsado -->
                    <div class="d-lg-none ms-2">
                        <a href="userProfile.php" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-person-circle"></i> Perfil</a>

                        <a href="../app/actions/auth/logout_action.php" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-2">
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


    <!--panel de voluntario-->
    
<div class="container-xxl my-3 flex-grow-1">
        <div class="text-center mb-4">
            <h3 class="mb-2 fw-bold">Este es tu resumen, <?php echo $_SESSION['nombre']; ?>🐱</h3>
            <p class="text-muted">Sigue y gestiona tus reservas de turnos y prestamos de jaulas</p>
        </div>

         <div class="card shadow-sm border-0 mb-4">
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
        <!-- cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class=" bg-primary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-calendar-check text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">Próximas reservas</h5>
                                <p class="text-muted small mb-0"><?php echo $active_bookings_count; ?>
                                <?php echo $active_bookings_count === 1 ? ' Reserva activa' : '
                                Reservas activas'; ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Turnos mañana</span>
                                <span class="badge bg-success"><?php echo $active_morning_shifts; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Turnos tarde</span>
                                <span class="badge bg-primary"><?php echo $active_afternoon_shifts; ?></span>
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <a href="userBookings.php" class="btn btn-primary w-100">
                                Ver mis turnos <i class="bi bi-arrow-right-circle me-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 p-3 me-3">
                                <i class="bi bi-box text-success fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">Jaulas prestadas</h5>
                                <p class="text-muted small mb-0"><?php echo htmlspecialchars($stats_jaulas['jaulas_prestadas']); ?> Jaulas prestadas</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">

                        <?php foreach ($jaulas_por_tipo as $jaula): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted"><?php echo htmlspecialchars($jaula['tipo_nombre']); ?></span>
                                <span class="badge bg-success"><?php echo htmlspecialchars($jaula['cantidad']); ?></span>
                            </div>
                        <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-auto">
                            <a href="jaulas.php" class="btn btn-success w-100">
                                Ver mis jaulas <i class="bi bi-arrow-right-circle me-2"></i>
                            </a>
                        </div>
                    </div>      
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 p-3 me-3">
                                <i class="bi bi-geo-alt text-info fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">Colonias asignadas</h5>
                                <p class="text-muted small mb-0"><?php echo htmlspecialchars($colonies_quantity); ?>
                                <?php echo $colonies_quantity === 1 ? ' Colonia asignada' : ' Colonias asignadas'; ?></p>
                                
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Colonia <?php echo htmlspecialchars($user_colony); ?></span>
                                <i class="bi bi-check-circle text-success"></i>
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <a href="userColony.php" class="btn btn-info w-100">
                                Más información <i class="bi bi-arrow-right-circle me-2"></i>
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    

    <!-- Card de Tips y Guía Rápida -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-info bg-opacity-10 p-3 me-3">
                                <i class="bi bi-lightbulb-fill text-info fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">Guía Rápida</h5>
                                <p class="text-muted small mb-0">Tips para aprovechar al máximo la plataforma</p>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <!-- TIP 1 -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start p-3 bg-light rounded">
                                    <div class="me-3">
                                        <i class="bi bi-calendar-plus text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Reserva con antelación</h6>
                                        <p class="text-muted small mb-0">Reserva tus turnos con al menos 24h de antelación para garantizar disponibilidad.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- TIP 2 -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start p-3 bg-light rounded">
                                    <div class="me-3">
                                        <i class="bi bi-box-seam text-success fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Devuelve las jaulas a tiempo</h6>
                                        <p class="text-muted small mb-0">Asegúrate de devolver las jaulas en la fecha prevista para que otros voluntarios puedan usarlas.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- TIP 3 -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start p-3 bg-light rounded">
                                    <div class="me-3">
                                        <i class="bi bi-bell text-warning fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Activa las notificaciones</h6>
                                        <p class="text-muted small mb-0">Mantente al día con recordatorios de turnos y devoluciones de jaulas.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- TIP 4 -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start p-3 bg-light rounded">
                                    <div class="me-3">
                                        <i class="bi bi-phone text-danger fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Contacto de emergencia</h6>
                                        <p class="text-muted small mb-0">Ante cualquier imprevisto, contacta con la asociación al <strong>966 123 456</strong></p>
                                    </div>
                                </div>
                            </div>

                            <!-- TIP 5 - Clima -->
                            <div class="col-12">
                                <div class="d-flex align-items-start p-3 bg-light rounded">
                                    <div class="me-3">
                                        <i class="bi bi-cloud-sun text-info fs-5"></i>
                                    </div>
                                    <div class="w-100">
                                        <h6 class="fw-semibold mb-3">Consulta el tiempo antes de intentar una captura</h6>
                                        
                                        <?php if (isset($weather)): ?>
                                        <div class="row align-items-center">
                                            <!-- Clima actual -->
                                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                                <div class="p-3 bg-white rounded shadow-sm">
                                                    <p class="text-muted small mb-2">Ahora en <?php echo htmlspecialchars($weather['city']); ?></p>
                                                    <img src="http://openweathermap.org/img/wn/<?php echo htmlspecialchars($weather['icon']); ?>@2x.png" 
                                                         alt="Clima" width="60" height="60">
                                                    <h3 class="fw-bold mb-1"><?php echo round($weather['temperature']); ?>°C</h3>
                                                    <p class="text-muted small mb-0 text-capitalize"><?php echo htmlspecialchars($weather['description']); ?></p>
                                                </div>
                                            </div>
                                            
                                            <!-- Previsión del día -->
                                            <div class="col-md-8">
                                                <p class="text-muted small mb-2">Previsión para hoy</p>
                                                <div class="d-flex gap-2 overflow-auto">
                                                    <?php 
                                                    $count = 0;
                                                    foreach ($forecast as $item): 
                                                        if ($count >= 6) break; // Mostrar solo 6 intervalos
                                                        $count++;
                                                    ?>
                                                    <div class="text-center p-2 bg-white rounded shadow-sm" style="min-width: 80px;">
                                                        <p class="small mb-1 fw-semibold"><?php echo htmlspecialchars($item['time']); ?></p>
                                                        <img src="http://openweathermap.org/img/wn/<?php echo htmlspecialchars($item['icon']); ?>.png" 
                                                             alt="Clima" width="40" height="40">
                                                        <p class="mb-0 fw-bold"><?php echo $item['temperature']; ?>°C</p>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div class="text-center text-muted py-3">
                                            <i class="bi bi-exclamation-circle fs-3 mb-2"></i>
                                            <p class="mb-0">No se pudo obtener la información del clima</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botón de ayuda adicional -->
                        <div class="text-center mt-4">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bi bi-question-circle me-2"></i>¿Necesitas más ayuda?
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <!-- end cards -->



    <!-- FOOTER -->

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container d-flex flex-column flex-md-row flex-wrap 
              justify-content-center justify-content-md-between 
              align-items-center text-center text-md-start">

            <div
                class="col-md-4 d-flex align-items-center justify-content-center justify-content-md-start mb-3 mb-md-0">
                <a href="/" class="mb-3 me-2 mb-md-0 text-light text-decoration-none lh-1">
                    <img src="../public/assets/brand/LOGO-CES-2.png" alt="Logo" width="50" height="auto" />
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