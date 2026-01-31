<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../app/actions/campaigns/get_campaigns_action.php';
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
    <title>Gestión de Campañas | CES Gatos Elche</title>
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
                    <li class="nav-item"><a href="campaignsAdmin.php" class="nav-link active">Campañas</a></li>
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
    <div class="container-xxl mt-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-dark opacity-30" href="AdminPanel.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gestión de Campañas</li>
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
                        <i class="bi bi-calendar-event text-primary" style="font-size: 3rem;"></i> Gestión de Campañas
                    </h1>
                    <p class="text-muted">Administra las campañas de esterilización y sus fechas</p>
                </div>
                <div>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#newCampaignModal">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Campaña
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
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="filterStatus" id="filterActive" value="1" checked>
                            <label class="btn btn-outline-success" for="filterActive">
                                <i class="bi bi-check-circle"></i> Activas
                            </label>
                            
                            <input type="radio" class="btn-check" name="filterStatus" id="filterInactive" value="0">
                            <label class="btn btn-outline-secondary" for="filterInactive">
                                <i class="bi bi-archive"></i> Finalizadas
                            </label>
                            
                            <input type="radio" class="btn-check" name="filterStatus" id="filterAll" value="">
                            <label class="btn btn-outline-primary" for="filterAll">
                                <i class="bi bi-list"></i> Todas
                            </label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Buscar por nombre</label>
                        <input type="text" id="searchName" class="form-control" placeholder="Nombre de campaña...">
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


        <!-- Tabla de Campañas -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Lista de Campañas
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="campaignsTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash text-primary me-1"></i>ID</th>
                                <th><i class="bi bi-tag text-primary me-1"></i>Nombre</th>
                                <th><i class="bi bi-calendar-check text-primary me-1"></i>Fecha Inicio</th>
                                <th><i class="bi bi-calendar-x text-primary me-1"></i>Fecha Fin</th>
                                <th class="text-center"><i class="bi bi-info-circle text-primary me-1"></i>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($campaigns)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <h5>No hay campañas registradas</h5>
                                            <p class="mb-0">Empieza añadiendo una nueva campaña.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($campaigns as $campaign): ?>
                                    <tr data-name="<?= htmlspecialchars($campaign['nombre']) ?>" 
                                        data-activa="<?= $campaign['activa'] ?>">
                                        <td class="fw-bold">#<?= htmlspecialchars($campaign['id']) ?></td>
                                        <td><?= htmlspecialchars($campaign['nombre']) ?></td>
                                        
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                <?= date('d/m/Y', strtotime($campaign['fecha_inicio'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-calendar-x me-1"></i>
                                                <?= date('d/m/Y', strtotime($campaign['fecha_fin'])) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($campaign['activa']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Activa
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>Finalizada
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary me-1 edit-campaign-btn" 
                                                    title="Editar"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editCampaignModal"
                                                    data-campaign-id="<?= $campaign['id'] ?>"
                                                    data-campaign-nombre="<?= htmlspecialchars($campaign['nombre']) ?>"
                                                    data-campaign-fecha-inicio="<?= $campaign['fecha_inicio'] ?>"
                                                    data-campaign-fecha-fin="<?= $campaign['fecha_fin'] ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <?php if ($campaign['activa']): ?>
                                                <button class="btn btn-sm btn-outline-danger deactivate-campaign-btn" 
                                                        data-campaign-id="<?= $campaign['id'] ?>"
                                                        data-campaign-name="<?= htmlspecialchars($campaign['nombre']) ?>"
                                                        title="Finalizar campaña">
                                                    <i class="bi bi-archive"></i>
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

    <!-- Modal Nueva Campaña -->
    <div class="modal fade" id="newCampaignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Campaña
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="newCampaignForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="newNombre" class="form-label fw-bold">
                                Nombre de la Campaña <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="newNombre" name="nombre" required>
                            <div class="form-text">Ej: Campaña Esterilización 2026</div>
                        </div>
                        

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="newFechaInicio" class="form-label fw-bold">
                                    Fecha Inicio <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="newFechaInicio" name="fecha_inicio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="newFechaFin" class="form-label fw-bold">
                                    Fecha Fin <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="newFechaFin" name="fecha_fin" required>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Los campos marcados con <span class="text-danger">*</span> son obligatorios. La campaña se creará como activa por defecto.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Crear Campaña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Campaña -->
    <div class="modal fade" id="editCampaignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Editar Campaña
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCampaignForm">
                    <input type="hidden" id="editCampaignId" name="campaign_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label fw-bold">
                                Nombre de la Campaña <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>
                        

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editFechaInicio" class="form-label fw-bold">
                                    Fecha Inicio <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="editFechaInicio" name="fecha_inicio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editFechaFin" class="form-label fw-bold">
                                    Fecha Fin <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="editFechaFin" name="fecha_fin" required>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Modificar las fechas puede afectar a los turnos y reservas existentes de esta campaña.
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
    <script src="../../public/assets/js/campaignsManagement.js"></script>
</body>
</html>
