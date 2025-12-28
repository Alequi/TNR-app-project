<?php
session_start();
require_once __DIR__ . '/../../app/helpers/auth.php';
admin();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | CES Gatos Elche</title>
    <link rel="icon" type="image/png" href="../public/assets/brand/LOGO-CES-2.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="../public/assets/dist/css/styles.css" rel="stylesheet" />

</head>

<body class="bg-light">

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

                <!-- MENÚ PRINCIPAL -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="" class="nav-link active">Panel</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Campañas</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Clínicas</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Colonias</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Turnos</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Reservas</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Jaulas</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Usuarios</a></li>
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
                                <a class="dropdown-item d-inline-flex align-items-center gap-2" href="ajustes.html">
                                    <i class="bi bi-gear fs-5 text-secondary"></i> Ajustes de cuenta</a>
                            </li>
                            <li>
                                <a class="dropdown-item d-inline-flex align-items-center gap-2 text-danger" href="index.html">
                                    <i class="bi bi-box-arrow-right fs-5"></i> Cerrar sesión</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Perfil MÓVIL: aparece dentro del menú colapsado -->
                    <div class="d-lg-none ms-2">
                        <a href="perfil.html" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-person-circle"></i> Perfil</a>

                        <a href="index.html" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-2">
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
            <li class="breadcrumb-item"><a class="text-dark opacity-30" href="#">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Panel</li>
        </ol>
    </nav>
</div>

    <!-- End Breadcrumb -->

    <!-- cards -->

    <div class="container-xxl">
        <div class="text-center">
            <h3 class="mb-0 fw-bold">Panel de Administración</h3>
            <p class="lead mb-4">Sigue y gestiona tus reservas de turnos y prestamos de jaulas</p>
        </div>

        <!--CARD 1 -->
        <div class="row gy-4 mt-4">
            <div class="col-md-5">
                <div class="card mb-4 shadow-sm  h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-semibold mb-1">Jaulas prestadas</h5>
                        <p class="card-text ">Administra las jaulas prestadas a los voluntarios.</p>
                        <p class="fs-5 mb-2">8/25 Jaulas</p>

                        <p class="mb-1">
                            <span class="text-success fw-semibold">6</span>
                            <span class="text-muted">Jaulas verdes</span>
                        </p>
                        <p class="mb-3">
                            <span class="text-primary fw-semibold">2</span>
                            <span class="text-muted">Jaulas grises</span>
                        </p>

                        <div class="justify-content-center d-flex mt-auto">
                            <button class="btn btn-primary  w-50 mt-auto">Ver Jaulas</button>
                        </div>
                    </div>
                </div>
            </div>


            <!--CARD 2 -->

            <div class="col-md-7">
                <div class="card mb-4 shadow-sm  h-100">
                    <div class="card-body d-flex flex-column ">
                        <h5 class="fw-semibold mb-1">Ocupación Clinicas</h5>
                        <p class="card-text">Administra la ocupación de las clínicas asociadas</p>
                        <div class="row mt-3">

                            <!-- CLÍNICA 1 -->
                            <div class="col-md-6 mb-3">
                                <p class="fw-semibold mb-1">Clínica Linneo</p>
                                <p class="mb-1">
                                    <span class="text-success fw-semibold">6/8</span>
                                    <span class="text-muted">Turno mañana</span>
                                </p>
                                <p class="mb-0">
                                    <span class="text-primary fw-semibold">7/8</span>
                                    <span class="text-muted">Turno tarde</span>
                                </p>
                            </div>

                            <!-- CLÍNICA 2 -->
                            <div class="col-md-6 mb-3">
                                <p class="fw-semibold mb-1">Clínica Elxveterinaria</p>
                                <p class="mb-1">
                                    <span class="text-danger fw-semibold">0/8</span>
                                    <span class="text-muted">Turno mañana</span>
                                </p>
                                <p class="mb-0">
                                    <span class="text-success fw-semibold">7/8</span>
                                    <span class="text-muted">Turno tarde</span>
                                </p>
                            </div>

                        </div>
                        <div class="justify-content-center d-flex mt-auto">
                            <button class="btn btn-primary  w-50 mt-auto">Ver clínicas</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end cards -->

    <!-- Tabla de informacion -->

    <div class="table-responsive container-xxl mt-4 mb-5">
        <h4 class="mb-3 fw-bold d-inline-flex align-items-center gap-2">
            <i class="bi bi-calendar-event"></i>
            Próximas Reservas de Turnos
        </h4>
        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th scope="col">Hora</th>
                    <th scope="col">Clinica</th>
                    <th scope="col">Turno</th>
                    <th scope="col">Colonia</th>
                    <th scope="col">Voluntario</th>
                    <th scope="col">Gatos</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">09:00</th>
                    <td>Clínica San Roque</td>
                    <td><span class="badge bg-success">Mañana</span></td>
                    <td>Colonia El Parque</td>
                    <td>María López</td>
                    <td>5</td>
                    <td><button class="btn btn-sm btn-outline-success">Ver</button></td>



                </tr>
                <tr>
                    <th scope="row">11:00</th>
                    <td>Clínica Veterinaria Elche</td>
                    <td><span class="badge bg-success">Mañana</span></td>
                    <td>Colonia La Huerta</td>
                    <td>Carlos Fernández</td>
                    <td>3</td>
                    <td><button class="btn btn-sm btn-outline-success">Ver</button></td>
                </tr>
                <tr>
                    <th scope="row">14:00</th>
                    <td>Clínica Animalia</td>
                    <td><span class="badge bg-primary">Tarde</span></td>
                    <td>Colonia Centro</td>
                    <td>Ana Martínez</td>
                    <td>4</td>
                    <td class="align-middle"><button class="btn btn-sm btn-outline-success">Ver</button></td>
                </tr>


            </tbody>

        </table>


        <nav aria-label="table navigation ">
            <ul class="pagination justify-content-center pagination-sm ">
                <li class="page-item">
                    <a class="page-link " href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>


    </div>
    <!-- end tabla de informacion -->

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