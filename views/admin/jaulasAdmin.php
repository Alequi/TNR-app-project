<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../config/conexion.php';
$con = conectar();
admin();

require_once __DIR__ . '/../../app/actions/jaulas/get_cages_action.php';

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
    <title>Gestión de Jaulas | CES Gatos Elche</title>
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
                   <li class="nav-item"><a href="adminPanel.php" class="nav-link ">Panel</a></li>
                    <li class="nav-item"><a href="campaignsAdmin.php" class="nav-link">Campañas</a></li>
                    <li class="nav-item"><a href="clinicsAdmin.php" class="nav-link">Clínicas</a></li>
                    <li class="nav-item"><a href="coloniesAdmin.php" class="nav-link">Colonias</a></li>
                    <li class="nav-item"><a href="shiftsAdmin.php" class="nav-link">Turnos</a></li>
                    <li class="nav-item"><a href="bookingAdmin.php" class="nav-link">Reservas</a></li>
                    <li class="nav-item"><a href="jaulasAdmin.php" class="nav-link active">Jaulas</a></li>
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
                <li class="breadcrumb-item active" aria-current="page">Gestión de Jaulas</li>
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
                        <i class="bi bi-box-seam text-primary" style="font-size: 3rem;"></i> Gestión de Jaulas
                    </h1>
                    <p class="text-muted">Administra el inventario de jaulas por clínica</p>
                </div>
                <div>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#newCageModal">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Jaula
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

        <!-- Filtros -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filtrar por tipo</label>
                        <select id="filterType" class="form-select">
                            <option value="">Todos los tipos</option>
                            <?php foreach ($cage_types as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filtrar por clinica</label>
                        <select id="filterClinic" class="form-select">
                            <option value="">Todos las clinicas</option>
                            <?php foreach ($clinics as $clinic): ?>
                                <option value="<?= $clinic['id'] ?>"><?= htmlspecialchars($clinic['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                     <div class="col-md-3">
                        <label class="form-label fw-bold">Filtrar por Disponibilidad</label>
                        <select id="filterAvailability" class="form-select">
                            <option value="">Todas las disponibilidades</option>
                            <option value="disponible">Disponibles</option>
                            <option value="prestada">Prestadas</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="clearFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-2"></i>Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de jaulas -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Inventario de Jaulas
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="cagesTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash text-primary me-1"></i>ID</th>
                                <th><i class="bi bi-hospital text-primary me-1"></i>Clínica</th>
                                <th><i class="bi bi-box text-primary me-1"></i>Tipo</th>
                                <th><i class="bi bi-tag text-primary me-1"></i>Número Interno</th>
                                <th class="text-center"><i class="bi bi-truck text-primary me-1"></i>Prestada</th>
                                <th><i class="bi bi-person text-primary me-1"></i>Voluntario</th>
                                <th><i class="bi bi-geo-alt text-primary me-1"></i>Colonia</th>
                                <th class="text-center"><i class="bi bi-toggle-on text-primary me-1"></i>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($cages)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <h5>No hay jaulas registradas</h5>
                                            <p class="mb-0">Empieza añadiendo una nueva jaula.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cages as $cage): ?>
                                    <tr data-type="<?= $cage['tipo_id'] ?>"  data-clinic="<?= $cage['clinica_id'] ?>" data-state="<?= $cage['prestamo_estado'] ?>">
                                        <td class="fw-bold">#<?= htmlspecialchars($cage['id']) ?></td>
                                        <td><?= htmlspecialchars($cage['clinica_nombre']) ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?= htmlspecialchars($cage['tipo_nombre']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($cage['numero_interno']) ?></td>
                                        <td class="text-center">
                                            <?php if ($cage['prestamo_estado'] === 'prestado'): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-box-arrow-right me-1"></i>Prestada
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark">Disponible</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cage['prestamo_estado'] === 'prestado' && $cage['voluntario_nombre']): ?>
                                                <?= htmlspecialchars($cage['voluntario_nombre'] . ' ' . $cage['voluntario_apellido']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cage['prestamo_estado'] === 'prestado' && $cage['colonia_nombre']): ?>
                                                <span class="badge bg-secondary">
                                                    <?= htmlspecialchars($cage['colonia_code']) ?>
                                                </span>
                                                <?= htmlspecialchars($cage['colonia_nombre']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($cage['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                                <i class="bi bi-pencil"></i>
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
    </main>

    <!-- Modal Nueva Jaula -->
    <div class="modal fade" id="newCageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Jaula
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="newCageForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="clinic_id" class="form-label">Clínica *</label>
                            <select class="form-select" id="clinic_id" name="clinic_id" required>
                                <option value="">Selecciona una clínica</option>
                                <?php foreach ($clinics as $clinic): ?>
                                    <option value="<?= $clinic['id'] ?>"><?= htmlspecialchars($clinic['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cage_type_id" class="form-label">Tipo de Jaula *</label>
                            <select class="form-select" id="cage_type_id" name="cage_type_id" required>
                                <option value="">Selecciona un tipo</option>
                                <?php foreach ($cage_types as $type): ?>
                                    <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="numero_interno" class="form-label">Número Interno *</label>
                            <input type="text" class="form-control" id="numero_interno" name="numero_interno" 
                                   placeholder="Ej: J-001" required>
                            <small class="text-muted">Identificador único de la jaula en la clínica</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Jaula
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
    <script src="../../public/assets/js/close-alerts.js"></script>
    <script src="../../public/assets/js/cageManagement.js"></script>
</body>

</html>
