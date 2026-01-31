<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../app/actions/colonies/get_colony_action.php';

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


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Colonias | CES Gatos Elche</title>
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
                    <li class="nav-item"><a href="campaignsAdmin.php" class="nav-link">Campañas</a></li>
                    <li class="nav-item"><a href="clinicsAdmin.php" class="nav-link">Clínicas</a></li>
                    <li class="nav-item"><a href="coloniesAdmin.php" class="nav-link active">Colonias</a></li>
                    <li class="nav-item"><a href="shiftsAdmin.php" class="nav-link">Turnos</a></li>
                    <li class="nav-item"><a href="bookingAdmin.php" class="nav-link">Reservas</a></li>
                    <li class="nav-item"><a href="jaulasAdmin.php" class="nav-link">Jaulas</a></li>
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
                <li class="breadcrumb-item active" aria-current="page">Gestión de Colonias</li>
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
                        <i class="bi bi-geo-alt text-primary" style="font-size: 3rem;"></i> Gestión de Colonias
                    </h1>
                    <p class="text-muted">Administra las colonias felinas y sus gestores</p>
                </div>
                <div>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#newColonyModal">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Colonia
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
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Filtrar por zona</label>
                        <input type="text" id="filterZona" class="form-control" placeholder="Buscar zona...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Buscar por nombre</label>
                        <input type="text" id="searchName" class="form-control" placeholder="Nombre de colonia...">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button id="clearFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Filtros -->

        <!-- Tabla de colonias -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Lista de Colonias
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="coloniesTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash text-primary me-1"></i>ID</th>
                                <th><i class="bi bi-upc text-primary me-1"></i>Código</th>
                                <th><i class="bi bi-geo-alt text-primary me-1"></i>Nombre</th>
                                <th><i class="bi bi-map text-primary me-1"></i>Zona</th>
                                <th><i class="bi bi-info-circle text-primary me-1"></i>Estado actual</th>
                                <th><i class="bi bi-person-badge text-primary me-1"></i>Gestor</th>
                                <th><i class="bi bi-people text-primary me-1"></i>Voluntarios</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($colonies)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <h5>No hay colonias registradas</h5>
                                            <p class="mb-0">Empieza añadiendo una nueva colonia.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($colonies as $colony): ?>
                                    <tr data-name="<?= htmlspecialchars($colony['nombre']) ?>" 
                                        data-zona="<?= htmlspecialchars($colony['zona']) ?>">
                                        <td class="fw-bold">#<?= htmlspecialchars($colony['id']) ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= htmlspecialchars($colony['code']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($colony['nombre']) ?></td>
                                        <td><?= htmlspecialchars($colony['zona']) ?></td>
                                        <td class="text-center">
                                            <?php if ($colony['activa']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Activa
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>Inactiva
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($colony['gestor_id']): ?>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-person-circle text-primary fs-5"></i>
                                                    <div>
                                                        <div><?= htmlspecialchars($colony['gestor_nombre']) ?></div>
                                                        <small class="text-muted">
                                                            <span class="badge badge-sm bg-info">Gestor</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="bi bi-person-x"></i> Sin gestor
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" >
                                           <span class="badge bg-secondary"><?= htmlspecialchars($colony['num_voluntarios']) ?></span>
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary me-1 edit-colony-btn" 
                                                    data-colony-id="<?= $colony['id'] ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editColonyModal"
                                                    data-colony-code="<?= htmlspecialchars($colony['code']) ?>"
                                                    data-colony-name="<?= htmlspecialchars($colony['nombre']) ?>"
                                                    data-colony-zone="<?= htmlspecialchars($colony['zona']) ?>"
                                                    data-colony-gestor="<?= htmlspecialchars($colony['gestor_id']) ?>"

                                                    title="Editar colonia">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <?php if ($colony['activa']): ?>
                                            <button class="btn btn-sm btn-outline-danger deactivate-colony-btn" 
                                                    data-colony-id="<?= $colony['id'] ?>"
                                                    data-colony-name="<?= htmlspecialchars($colony['nombre']) ?>"
                                                    title="Desactivar colonia">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                            <?php else: ?>
                                            <button class="btn btn-sm btn-outline-success activate-colony-btn" 
                                                    data-colony-id="<?= $colony['id'] ?>"
                                                    data-colony-name="<?= htmlspecialchars($colony['nombre']) ?>"
                                                    title="Activar colonia">
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
        <!-- End tabla de colonias -->
    </main>

    <!-- Modal Nueva Colonia -->
    <div class="modal fade" id="newColonyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Colonia
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="newColonyForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="newCode" class="form-label fw-bold">
                                Código <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="newCode" name="code" required>
                            <div class="form-text">Código único de identificación de la colonia</div>
                        </div>

                        <div class="mb-3">
                            <label for="newNombre" class="form-label fw-bold">
                                Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="newNombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="newZona" class="form-label fw-bold">
                                Zona <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="newZona" name="zona" required>
                            <div class="form-text">Ubicación geográfica de la colonia</div>
                        </div>

                        <div class="mb-3">
                            <label for="newGestor" class="form-label fw-bold">
                                Gestor Responsable
                            </label>
                            <select class="form-select" id="newGestor" name="gestor_id">
                                <option value="">Sin gestor asignado</option>
                                <?php foreach ($voluntarios as $voluntario): ?>
                                    <option value="<?= $voluntario['id'] ?>">
                                        <?= htmlspecialchars($voluntario['nombre'] . ' ' . $voluntario['apellido']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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
                            <i class="bi bi-check-circle me-1"></i>Crear Colonia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Nueva Colonia -->

    <!-- Modal Editar Colonia -->
    <div class="modal fade" id="editColonyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Editar Colonia
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editColonyForm">
                    <input type="hidden" id="editColonyId" name="colony_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editCode" class="form-label fw-bold">
                                Código <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="editCode" name="code" required>
                            <div class="form-text">Código único de identificación de la colonia</div>
                        </div>

                        <div class="mb-3">
                            <label for="editNombre" class="form-label fw-bold">
                                Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="editZona" class="form-label fw-bold">
                                Zona <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="editZona" name="zona" required>
                            <div class="form-text">Ubicación geográfica de la colonia</div>
                        </div>

                        <div class="mb-3">
                            <label for="editGestor" class="form-label fw-bold">
                                Gestor Responsable
                            </label>
                            <select class="form-select" id="editGestor" name="gestor_id">
                                <option value="">Sin gestor asignado</option>
                                <?php foreach ($voluntarios as $voluntario): ?>
                                    <option value="<?= $voluntario['id'] ?>">
                                        <?= htmlspecialchars($voluntario['nombre'] . ' ' . $voluntario['apellido']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                             
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Si seleccionas un gestor que ya está asignado a otra colonia, se reasignará automáticamente.
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
    <!-- End Modal Editar Colonia -->

    <!-- Footer -->
    <?php require_once __DIR__ . '/../../public/partials/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/js/colonyManagement.js"></script>
</body>

</html>
