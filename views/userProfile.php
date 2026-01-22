<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/actions/user_action.php';
require_once __DIR__ . '/../app/actions/bookings_action.php';
require_once __DIR__ . '/../app/actions/jaulas_action.php';
login();

$con = conectar();
$user_id = $_SESSION['user_id'];


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | CES Gatos Elche</title>
    <link rel="icon" type="image/png" href="../public/assets/brand/LOGO-CES-2.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/assets/dist/css/styles.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-light border-bottom">
        <div class="container-xxl">

            <!-- LOGO -->
            <a href="../index.html" class="navbar-brand d-inline-flex align-items-center">
                <img src="../public/assets/brand/LOGO-CES-2.png" alt="Logo" width="100" height="auto" />
            </a>

            <!-- BOTÓN HAMBURGUESA -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- CONTENIDO COLAPSABLE -->
            <div class="collapse navbar-collapse" id="mainNavbar">

                <!-- MENÚ PRINCIPAL -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="panel.php" class="nav-link">Panel</a></li>
                    <li class="nav-item"><a href="jaulas.php" class="nav-link">Jaulas</a></li>
                    <li class="nav-item"><a href="userProfile.php" class="nav-link active">Mi Perfil</a></li>
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
                                    <i class="bi bi-person fs-5 text-secondary"></i> Mi Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-inline-flex align-items-center gap-2 text-danger"
                                    href="../app/actions/logout_action.php">
                                    <i class="bi bi-box-arrow-right fs-5"></i> Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Perfil MÓVIL -->
                    <div class="d-lg-none ms-2">
                        <a href="userProfile.php"
                            class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-person-circle"></i> Perfil
                        </a>

                        <a href="../app/actions/logout_action.php"
                            class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-2">
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
                <li class="breadcrumb-item"><a class="text-dark opacity-30" href="panel.php">Panel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mi Perfil</li>
            </ol>
        </nav>
    </div>
    <!-- End Breadcrumb -->

    <!-- Mensajes de éxito/error -->
    <div class="container-xxl mt-3">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php 
                    echo htmlspecialchars($_SESSION['success']); 
                    unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
    <!-- End mensajes -->

    <!-- Contenido Principal -->
    <div class="container-xxl my-4 flex-grow-1 mt-3">
        
        <!-- Título -->
        <div class="text-center mb-4">
            <h3 class="mb-0 fw-bold">Mi Perfil</h3>
            <p class="lead mb-4">Información personal y estadísticas</p>
        </div>

        <div class="row gy-4">
            
            <!-- Columna Izquierda: Información Personal -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 120px; height: 120px;">
                                <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                        
                        <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></h4>
                        <p class="text-muted mb-3">
                            <i class="bi bi-shield-check me-1"></i>
                            <?php echo $user['rol'] === 'admin' ? 'Administrador' : 'Voluntario'; ?>
                        </p>

                        <hr class="my-3">

                        <div class="text-start">
                            <p class="mb-2">
                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                <strong>Email:</strong><br>
                                <span class="ms-4"><?php echo htmlspecialchars($user['email']); ?></span>
                            </p>
                            
                            <p class="mb-2">
                                <i class="bi bi-telephone-fill text-primary me-2"></i>
                                <strong>Teléfono:</strong><br>
                                <span class="ms-4"><?php echo htmlspecialchars($user['telefono']); ?></span>
                            </p>

                            <?php if ($user['colonia_nombre']): ?>
                            <p class="mb-2">
                                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                <strong>Colonia asignada:</strong><br>
                                <span class="ms-4"><?php echo htmlspecialchars($user['colonia_code'] . ' - ' . $user['colonia_nombre']); ?></span>
                            </p>
                            <?php endif; ?>

                            <p class="mb-2">
                                <i class="bi bi-calendar-check-fill text-primary me-2"></i>
                                <strong>Miembro desde:</strong><br>
                                <span class="ms-4"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></span>
                            </p>

                            <p class="mb-0">
                                <i class="bi bi-circle-fill <?php echo $user['activo'] ? 'text-success' : 'text-danger'; ?> me-2" style="font-size: 0.6rem;"></i>
                                <strong>Estado:</strong>
                                <span class="badge bg-<?php echo $user['activo'] ? 'success' : 'secondary'; ?> ms-2">
                                    <?php echo $user['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </p>
                        </div>

                        <hr class="my-3">

                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil-square me-2"></i>Editar Perfil
                        </button>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Estadísticas y Actividad -->
            <div class="col-lg-8">
                
                <!-- Estadísticas -->
                <div class="row gy-3 mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-check text-success" style="font-size: 2.5rem;"></i>
                                <h3 class="fw-bold mt-2 mb-1"><?php echo $stats_reservas['total_reservas']; ?></h3>
                                <p class="text-muted mb-0">Reservas Totales</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-clock-history text-primary" style="font-size: 2.5rem;"></i>
                                <h3 class="fw-bold mt-2 mb-1"><?php echo $stats_activas['activas']; ?></h3>
                                <p class="text-muted mb-0">Reservas Activas</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-box text-warning" style="font-size: 2.5rem;"></i>
                                <h3 class="fw-bold mt-2 mb-1"><?php echo $stats_jaulas['jaulas_prestadas']; ?></h3>
                                <p class="text-muted mb-0">Jaulas Prestadas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">
                            <i class="bi bi-info-circle me-2"></i>Información de Cuenta
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="fw-semibold text-primary mb-2">Accesos Rápidos</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <a href="panel.php" class="text-decoration-none">
                                                <i class="bi bi-house-door me-2"></i>Panel de Usuario
                                            </a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="panel.php" class="text-decoration-none">
                                                <i class="bi bi-box me-2"></i>Gestión de Jaulas
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="fw-semibold text-primary mb-2">Seguridad</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                <i class="bi bi-key me-2"></i>Cambiar Contraseña
                                            </a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="../app/actions/logout_action.php" class="text-decoration-none text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <?php if ($user['rol'] === 'voluntario' && $user['colonia_nombre']): ?>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>Colonia Asignada:</strong> Eres el gestor de la colonia 
                            <strong><?php echo htmlspecialchars($user['colonia_code']); ?></strong>. 
                            Puedes gestionar las reservas y jaulas relacionadas con esta colonia.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Perfil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Editar Perfil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" method="POST" action="../app/actions/update_profile_action.php">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" 
                                   value="<?php echo htmlspecialchars($user['apellido']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" 
                                   value="<?php echo htmlspecialchars($user['telefono']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="editProfileForm" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Cambiar Contraseña -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">
                        <i class="bi bi-key me-2"></i>Cambiar Contraseña
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" method="POST" action="../app/actions/change_password_action.php">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="changePasswordForm" class="btn btn-primary">Cambiar Contraseña</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
        <?php include_once '../public/partials/footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="../public/assets/js/validation.js"></script>

</body>

</html>