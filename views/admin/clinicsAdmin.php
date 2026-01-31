<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../app/actions/clinics/get_clinics_action.php';
$con = conectar();
admin();

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
    <title>Gestión de Clínicas | CES Gatos Elche</title>
    <link rel="icon" type="image/png" href="../../public/assets/brand/LOGO-CES-2.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="../../public/assets/dist/css/styles.css" rel="stylesheet" />
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-light border-bottom">
        <div class="container-xxl">
            <a href="/" class="navbar-brand d-inline-flex align-items-center">
                <img src="../../public/assets/brand/LOGO-CES-2.png" alt="Logo" width="100" height="auto" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="adminPanel.php" class="nav-link">Panel</a></li>
                    <li class="nav-item"><a href="campaignsAdmin.php" class="nav-link">Campañas</a></li>
                    <li class="nav-item"><a href="clinicsAdmin.php" class="nav-link active">Clínicas</a></li>
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
    <div class="container-xxl mt-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-dark opacity-30" href="AdminPanel.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gestión de Clínicas</li>
            </ol>
        </nav>
    </div>

    <!--MAIN CONTENT-->
    <main class="container-xxl my-4 flex-grow-1 mt-3">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="display-6 fw-bold">
                        <i class="bi bi-hospital text-primary" style="font-size: 3rem;"></i> Gestión de Clínicas
                    </h1>
                    <p class="text-muted">Administra clínicas veterinarias y sus capacidades</p>
                </div>
                <div>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#newClinicModal">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Clínica
                    </button>
                </div>
            </div>
        </div>

        <!--MENSAJES-->
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- End mensajes -->
        <!---- Filtros y búsqueda -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Estado</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="filterStatus" id="filterActive" value="1" checked>
                            <label class="btn btn-outline-success" for="filterActive">
                                <i class="bi bi-check-circle"></i> Activas
                            </label>
                            
                            <input type="radio" class="btn-check" name="filterStatus" id="filterInactive" value="0">
                            <label class="btn btn-outline-secondary" for="filterInactive">
                                <i class="bi bi-archive"></i> Inactivas
                            </label>
                            
                            <input type="radio" class="btn-check" name="filterStatus" id="filterAll" value="">
                            <label class="btn btn-outline-primary" for="filterAll">
                                <i class="bi bi-list"></i> Todas
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Buscar por nombre</label>
                        <input type="text" id="searchName" class="form-control" placeholder="Nombre de clínica...">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="clearFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!---- End Filtros y búsqueda -->


        <!-- Tabla de Clínicas -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Lista de Clínicas Veterinarias
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="clinicsTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash text-primary me-1"></i>ID</th>
                                <th><i class="bi bi-hospital text-primary me-1"></i>Nombre</th>
                                <th><i class="bi bi-geo-alt text-primary me-1"></i>Dirección</th>
                                <th><i class="bi bi-telephone text-primary me-1"></i>Teléfono</th>
                                <th class="text-center"><i class="bi bi-sunrise text-primary me-1"></i>Cap. Mañana</th>
                                <th class="text-center"><i class="bi bi-sunset text-primary me-1"></i>Cap. Tarde</th>
                                <th><i class="bi bi-box text-primary me-1"></i>Jaulas</th>
                                <th><i class="bi bi-box text-primary me-1"></i>Prestadas</th>
                                <th class="text-center"><i class="bi bi-info-circle text-primary me-1"></i>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($clinics)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <h5>No hay clínicas registradas</h5>
                                            <p class="mb-0">Empieza añadiendo una nueva clínica.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($clinics as $clinic): ?>
                                    <tr data-name="<?= htmlspecialchars($clinic['nombre']) ?>" 
                                        data-activa="<?= $clinic['activa'] ?>">
                                        <td class="fw-bold">#<?= htmlspecialchars($clinic['id']) ?></td>
                                        <td><?= htmlspecialchars($clinic['nombre']) ?></td>
                                        <td>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($clinic['direccion'] ?? 'Sin dirección') ?>
                                            </small>
                                        </td>
                                        <td><?= htmlspecialchars($clinic['telefono']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= $clinic['capacidad_ma'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark"><?= $clinic['capacidad_ta'] ?></span>
                                        </td>
                                        <td>
                                            <?php if ($clinic['total_jaulas'] > 0): ?>
                                                <span class="badge bg-secondary"><?= $clinic['total_jaulas'] ?> total</span>
                                            <?php else: ?>
                                                <span class="text-muted">Sin jaulas</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($clinic['jaulas_prestadas'] > 0): ?>
                                                <span class="badge bg-secondary"><?= $clinic['jaulas_prestadas'] ?> prestadas</span>
                                            <?php else: ?>
                                                <span class="text-muted">Ninguna prestada</span>
                                            <?php endif; ?>
                                        <td class="text-center">
                                            <?php if ($clinic['activa']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Activa
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>Inactiva
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary me-1 edit-clinic-btn" 
                                                    title="Editar"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editClinicModal"
                                                    data-clinic-id="<?= $clinic['id'] ?>"
                                                    data-clinic-nombre="<?= htmlspecialchars($clinic['nombre']) ?>"
                                                    data-clinic-direccion="<?= htmlspecialchars($clinic['direccion']) ?>"
                                                    data-clinic-telefono="<?= htmlspecialchars($clinic['telefono']) ?>"
                                                    data-clinic-cap-ma="<?= $clinic['capacidad_ma'] ?>"
                                                    data-clinic-cap-ta="<?= $clinic['capacidad_ta'] ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <?php if ($clinic['activa']): ?>
                                                <button class="btn btn-sm btn-outline-danger deactivate-clinic-btn" 
                                                        data-clinic-id="<?= $clinic['id'] ?>"
                                                        data-clinic-name="<?= htmlspecialchars($clinic['nombre']) ?>"
                                                        title="Desactivar clínica">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-success activate-clinic-btn" 
                                                        data-clinic-id="<?= $clinic['id'] ?>"
                                                        data-clinic-name="<?= htmlspecialchars($clinic['nombre']) ?>"
                                                        title="Activar clínica">
                                                    <i class="bi bi-check-circle"></i>
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
    </main>

    <!-- Modal Nueva Clínica -->
    <div class="modal fade" id="newClinicModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Clínica
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="newClinicForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="newNombre" class="form-label fw-bold">
                                Nombre de la Clínica <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="newNombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="newDireccion" class="form-label fw-bold">Dirección</label>
                            <input type="text" class="form-control" id="newDireccion" name="direccion">
                            <div class="form-text">Ubicación física de la clínica</div>
                        </div>

                        <div class="mb-3">
                            <label for="newTelefono" class="form-label fw-bold">
                                Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control" id="newTelefono" name="telefono" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="newCapacidadMa" class="form-label fw-bold">
                                    Capacidad Mañana <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="newCapacidadMa" name="capacidad_ma" required min="1">
                                <div class="form-text">Número máximo de gatos en turno mañana</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="newCapacidadTa" class="form-label fw-bold">
                                    Capacidad Tarde <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="newCapacidadTa" name="capacidad_ta" required min="1">
                                <div class="form-text">Número máximo de gatos en turno tarde</div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Los campos marcados con <span class="text-danger">*</span> son obligatorios
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Crear Clínica
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Clínica -->
    <div class="modal fade" id="editClinicModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Editar Clínica
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editClinicForm">
                    <input type="hidden" id="editClinicId" name="clinic_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label fw-bold">
                                Nombre de la Clínica <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editDireccion" class="form-label fw-bold">Dirección</label>
                            <input type="text" class="form-control" id="editDireccion" name="direccion">
                            <div class="form-text">Ubicación física de la clínica</div>
                        </div>

                        <div class="mb-3">
                            <label for="editTelefono" class="form-label fw-bold">
                                Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control" id="editTelefono" name="telefono" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editCapacidadMa" class="form-label fw-bold">
                                    Capacidad Mañana <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="editCapacidadMa" name="capacidad_ma" required min="1">
                                <div class="form-text">Número máximo de gatos en turno mañana</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editCapacidadTa" class="form-label fw-bold">
                                    Capacidad Tarde <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="editCapacidadTa" name="capacidad_ta" required min="1">
                                <div class="form-text">Número máximo de gatos en turno tarde</div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Modificar las capacidades afectará a los turnos futuros creados con esta clínica
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/js/clinicManagement.js"></script>
</body>
</html>
