<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../config/conexion.php';

admin();
$con = conectar();

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

// Obtener clínicas
try {
    $stmt_clinics = $con->prepare("SELECT id, nombre, capacidad_ma, capacidad_ta FROM clinics ORDER BY nombre");
    $stmt_clinics->execute();
    $clinics = $stmt_clinics->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $clinics = [];
}

// Obtener campañas activas
try {
    $stmt_campaigns = $con->prepare("SELECT id, nombre FROM campaigns WHERE activa = 1 ORDER BY fecha_inicio DESC");
    $stmt_campaigns->execute();
    $campaigns = $stmt_campaigns->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $campaigns = [];
}

// Obtener turnos
try {
    $sql_shifts = "
        SELECT 
            s.id,
            s.fecha,
            s.turno,
            s.capacidad,
            s.ocupados,
            c.nombre AS clinic_nombre,
            c.id AS clinic_id,
            camp.nombre AS campaign_nombre,
            camp.id AS campaign_id
        FROM shifts s
        INNER JOIN clinics c ON s.clinic_id = c.id
        INNER JOIN campaigns camp ON s.campaign_id = camp.id
        ORDER BY s.fecha DESC, c.nombre, s.turno
    ";
    $stmt = $con->prepare($sql_shifts);
    $stmt->execute();
    $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Error al cargar los turnos: ' . $e->getMessage();
    $shifts = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Turnos | CES Gatos Elche</title>
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
                    <li class="nav-item"><a href="AdminPanel.php" class="nav-link">Panel</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Campañas</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Clínicas</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Turnos</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Jaulas</a></li>
                    <li class="nav-item"><a href="usersAdmin.php" class="nav-link">Usuarios</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center" 
                                data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-4 me-2"></i>
                            <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../userProfile.php">Mi perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="../../app/actions/auth/logout_action.php">
                                Cerrar sesión<i class="bi bi-box-arrow-right"></i>
                            </a></li>
                        </ul>
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
                <li class="breadcrumb-item active" aria-current="page">Gestión de Turnos</li>
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
                        <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i> Gestión de Turnos
                    </h1>
                    <p class="text-muted">Administra los turnos de las clínicas veterinarias</p>
                </div>
                <div>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#newShiftModal">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Turno
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

        <!-- Filtros -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filtrar por clínica</label>
                        <select id="filterClinic" class="form-select">
                            <option value="">Todas las clínicas</option>
                            <?php foreach ($clinics as $clinic): ?>
                                <option value="<?= $clinic['id'] ?>"><?= htmlspecialchars($clinic['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filtrar por turno</label>
                        <select id="filterTurno" class="form-select">
                            <option value="">Todos los turnos</option>
                            <option value="M">Mañana</option>
                            <option value="T">Tarde</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Buscar por fecha</label>
                        <input type="date" id="searchDate" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="clearFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Filtros -->

        <!-- Tabla de turnos -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Lista de Turnos
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="shiftsTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash text-primary me-1"></i>ID</th>
                                <th><i class="bi bi-calendar3 text-primary me-1"></i>Fecha</th>
                                <th><i class="bi bi-clock text-primary me-1"></i>Turno</th>
                                <th><i class="bi bi-hospital text-primary me-1"></i>Clínica</th>
                                <th><i class="bi bi-megaphone text-primary me-1"></i>Campaña</th>
                                <th class="text-center"><i class="bi bi-people text-primary me-1"></i>Capacidad</th>
                                <th class="text-center"><i class="bi bi-check-circle text-primary me-1"></i>Ocupados</th>
                                <th class="text-center"><i class="bi bi-percent text-primary me-1"></i>Ocupación</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($shifts)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <h5>No hay turnos registrados</h5>
                                            <p class="mb-0">Empieza añadiendo un nuevo turno.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($shifts as $shift): ?>
                                    <?php
                                    $ocupacion = $shift['capacidad'] > 0 ? round(($shift['ocupados'] / $shift['capacidad']) * 100) : 0;
                                    $badgeClass = $ocupacion >= 100 ? 'bg-danger' : ($ocupacion >= 75 ? 'bg-warning text-dark' : 'bg-success');
                                    ?>
                                    <tr data-clinic="<?= $shift['clinic_id'] ?>" 
                                        data-turno="<?= $shift['turno'] ?>" 
                                        data-fecha="<?= $shift['fecha'] ?>">
                                        <td class="fw-bold">#<?= htmlspecialchars($shift['id']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($shift['fecha'])) ?></td>
                                        <td>
                                            <?php if ($shift['turno'] === 'M'): ?>
                                                <span class="badge bg-info">
                                                    <i class="bi bi-sunrise me-1"></i>Mañana
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-sunset me-1"></i>Tarde
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($shift['clinic_nombre']) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($shift['campaign_nombre']) ?></span></td>
                                        <td class="text-center fw-bold"><?= $shift['capacidad'] ?></td>
                                        <td class="text-center"><?= $shift['ocupados'] ?></td>
                                        <td class="text-center">
                                            <span class="badge <?= $badgeClass ?>">
                                                <?= $ocupacion ?>%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary me-1 edit-shift-btn" 
                                                    data-shift-id="<?= $shift['id'] ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editShiftModal"
                                                    title="Editar turno">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-shift-btn" 
                                                    data-shift-id="<?= $shift['id'] ?>"
                                                    title="Eliminar turno"
                                                    <?= $shift['ocupados'] > 0 ? 'disabled' : '' ?>>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End tabla de turnos -->
    </main>

    <!-- Modal Nuevo Turno -->
    <div class="modal fade" id="newShiftModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Turno
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="newShiftForm">
                        <div class="mb-3">
                            <label for="newClinic" class="form-label fw-bold">
                                Clínica <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="newClinic" name="clinic_id" required>
                                <option value="">Selecciona una clínica</option>
                                <?php foreach ($clinics as $clinic): ?>
                                    <option value="<?= $clinic['id'] ?>" 
                                            data-cap-ma="<?= $clinic['capacidad_ma'] ?>" 
                                            data-cap-ta="<?= $clinic['capacidad_ta'] ?>">
                                        <?= htmlspecialchars($clinic['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="newCampaign" class="form-label fw-bold">
                                Campaña <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="newCampaign" name="campaign_id" required>
                                <option value="">Selecciona una campaña</option>
                                <?php foreach ($campaigns as $campaign): ?>
                                    <option value="<?= $campaign['id'] ?>">
                                        <?= htmlspecialchars($campaign['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="newFecha" class="form-label fw-bold">
                                Fecha <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="newFecha" name="fecha" required>
                        </div>

                        <div class="mb-3">
                            <label for="newTurno" class="form-label fw-bold">
                                Turno <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="newTurno" name="turno" required>
                                <option value="">Selecciona un turno</option>
                                <option value="M">Mañana</option>
                                <option value="T">Tarde</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="newCapacidad" class="form-label fw-bold">
                                Capacidad <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="newCapacidad" name="capacidad" 
                                   min="1" required>
                            <div class="form-text">Se autocompletará con la capacidad de la clínica seleccionada</div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Los campos marcados con <span class="text-danger">*</span> son obligatorios
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" form="newShiftForm" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Crear Turno
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Nuevo Turno -->

    <!-- Modal Editar Turno -->
    <div class="modal fade" id="editShiftModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-2"></i>Editar Turno
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editShiftForm">
                        <input type="hidden" id="editShiftId" name="shift_id">
                        
                        <div class="mb-3">
                            <label for="editClinic" class="form-label fw-bold">
                                Clínica <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="editClinic" name="clinic_id" required>
                                <option value="">Selecciona una clínica</option>
                                <?php foreach ($clinics as $clinic): ?>
                                    <option value="<?= $clinic['id'] ?>" 
                                            data-cap-ma="<?= $clinic['capacidad_ma'] ?>" 
                                            data-cap-ta="<?= $clinic['capacidad_ta'] ?>">
                                        <?= htmlspecialchars($clinic['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editCampaign" class="form-label fw-bold">
                                Campaña <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="editCampaign" name="campaign_id" required>
                                <option value="">Selecciona una campaña</option>
                                <?php foreach ($campaigns as $campaign): ?>
                                    <option value="<?= $campaign['id'] ?>">
                                        <?= htmlspecialchars($campaign['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editFecha" class="form-label fw-bold">
                                Fecha <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="editFecha" name="fecha" required>
                        </div>

                        <div class="mb-3">
                            <label for="editTurno" class="form-label fw-bold">
                                Turno <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="editTurno" name="turno" required>
                                <option value="">Selecciona un turno</option>
                                <option value="M">Mañana</option>
                                <option value="T">Tarde</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editCapacidad" class="form-label fw-bold">
                                Capacidad <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="editCapacidad" name="capacidad" 
                                   min="1" required>
                            <div class="form-text">No puede ser menor que el número de ocupados actuales</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ocupados actuales</label>
                            <input type="text" class="form-control" id="editOcupados" disabled>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Ten cuidado al editar turnos que ya tienen reservas
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" form="editShiftForm" class="btn btn-warning">
                        <i class="bi bi-check-circle me-1"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Editar Turno -->

    <!-- Footer -->
    <?php require_once __DIR__ . '/../../public/partials/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/js/shiftsManagement.js"></script>
</body>

</html>
