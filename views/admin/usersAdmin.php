<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../app/actions/user/get_users_action.php';
$con = conectar();
admin();

require_once __DIR__ . '/../../app/actions/user/get_users_action.php';

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
    <title>Gestión de Usuarios | CES Gatos Elche</title>
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
                    <li class="nav-item"><a href="jaulasAdmin.php" class="nav-link">Jaulas</a></li>
                    <li class="nav-item"><a href="usersAdmin.php" class="nav-link active">Usuarios</a></li>
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
                <li class="breadcrumb-item active" aria-current="page">Gestión de Usuarios</li>
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
                        <i class="bi bi-people text-primary" style="font-size: 3rem;"></i> Gestión de Usuarios
                    </h1>
                    <p class="text-muted">Administra voluntarios y sus colonias</p>
                </div>
                <div>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#newUserModal">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
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
                        <label class="form-label fw-bold">Filtrar por colonia</label>
                        <select id="filterColony" class="form-select">
                            <option value="">Todas las colonias</option>
                            <?php foreach ($colonies as $colony): ?>
                                <option value="<?= $colony['id'] ?>"><?= htmlspecialchars($colony['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Buscar por nombre</label>
                        <input type="text" id="searchName" class="form-control" placeholder="Nombre del usuario">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button id="clearFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!---- End Filtros y búsqueda -->


        <!-- Tabla de usuarios -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Lista de Usuarios
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash text-primary me-1"></i>ID</th>
                                <th><i class="bi bi-person text-primary me-1"></i>Nombre</th>
                                <th><i class="bi bi-envelope text-primary me-1"></i>Email</th>
                                <th><i class="bi bi-telephone text-primary me-1"></i>Teléfono</th>
                                <th><i class="bi bi-shield text-primary me-1"></i>Rol</th>
                                <th><i class="bi bi-geo-alt text-primary me-1"></i>Colonia</th>
                                <th class="text-center"><i class="bi bi-calendar-check text-primary me-1"></i>Reservas</th>
                                <th class="text-center"><i class="bi bi-box text-primary me-1"></i>Jaulas</th>
                                <th class="text-center"><i class="bi bi-toggle-on text-primary me-1"></i>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <h5>No hay usuarios registrados</h5>
                                            <p class="mb-0">Empieza añadiendo un nuevo usuario.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr data-name="<?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?>" data-colony="<?= $user['colony_id'] ?? '' ?>">
                                        <td class="fw-bold">#<?= htmlspecialchars($user['id']) ?></td>
                                        <td><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['telefono']) ?></td>
                                        <td>
                                            <?php if ($user['rol'] === 'admin'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-shield-fill-check me-1"></i>Admin
                                                </span>
                                            
                                            <?php elseif ($user['rol'] === 'gestor'): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-shield-fill-check me-1"></i>Gestor
                                                </span>    
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark">Voluntario</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($user['colonia_nombre']): ?>
                                                <span class="badge bg-secondary me-1">
                                                    <?= htmlspecialchars($user['colonia_code']) ?>
                                                </span>
                                                <?= htmlspecialchars($user['colonia_nombre']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($user['reservas_activas'] > 0): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <?= $user['reservas_activas'] ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($user['jaulas_prestadas'] > 0): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <?= $user['jaulas_prestadas'] ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($user['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary me-1 editUserBtn" 
                                                    title="Editar"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editUserModal"
                                                    data-user-id="<?= $user['id'] ?>"
                                                    data-user-nombre="<?= htmlspecialchars($user['nombre']) ?>"
                                                    data-user-apellido="<?= htmlspecialchars($user['apellido']) ?>"
                                                    data-user-email="<?= htmlspecialchars($user['email']) ?>"
                                                    data-user-telefono="<?= htmlspecialchars($user['telefono']) ?>"
                                                    data-user-rol="<?= $user['rol'] ?>"
                                                    data-user-colony="<?= $user['colony_id'] ?? '' ?>"
                                                    data-user-activo="<?= $user['activo'] ?>">
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

    </main>

    <!-- Modal Nuevo Usuario -->
    <div class="modal fade" id="newUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="newUserForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <small class="text-muted">Debe ser único en el sistema</small>
                        </div>
                        <div class="mb-3">
                            <label for="pass" class="form-label">Contraseña *</label>
                            <input type="password" class="form-control" id="pass" name="pass" required minlength="4">
                            <small class="text-muted">Mínimo 4 caracteres</small>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono *</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol *</label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="">Selecciona un rol</option>
                                <option value="voluntario">Voluntario</option>
                                <option value ="gestor">Gestor</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                        <div class="mb-3" id="colonySelectContainer">
                            <label for="colony_id" class="form-label">Colonia</label>
                            <select class="form-select" id="colony_id" name="colony_id">
                                <option value="">Sin colonia asignada</option>
                                <?php foreach ($colonies as $colony): ?>
                                    <option value="<?= $colony['id'] ?>">
                                        <?= htmlspecialchars($colony['code']) ?> - <?= htmlspecialchars($colony['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Editar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_apellido" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="edit_apellido" name="apellido" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                            <small class="text-muted">Debe ser único en el sistema</small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_pass" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="edit_pass" name="password" minlength="4">
                            <small class="text-muted">Déjalo vacío para mantener la actual. Mínimo 4 caracteres si cambias.</small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_telefono" class="form-label">Teléfono *</label>
                            <input type="tel" class="form-control" id="edit_telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_rol" class="form-label">Rol *</label>
                            <select class="form-select" id="edit_rol" name="rol" required>
                                <option value="">Selecciona un rol</option>
                                <option value="voluntario">Voluntario</option>
                                <option value="gestor">Gestor</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_colony_id" class="form-label">Colonia</label>
                            <select class="form-select" id="edit_colony_id" name="colony_id">
                                <option value="">Sin colonia asignada</option>
                                <?php foreach ($colonies as $colony): ?>
                                    <option value="<?= $colony['id'] ?>">
                                        <?= htmlspecialchars($colony['code']) ?> - <?= htmlspecialchars($colony['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_activo" class="form-label">Estado *</label>
                            <select class="form-select" id="edit_activo" name="activo" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning text-dark">
                            <i class="bi bi-save me-2"></i>Actualizar Usuario
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
    <script src="../../public/assets/js/userManagement.js"></script>
    <script src="../../public/assets/js/modalEditUser.js"></script>
</body>

</html>
