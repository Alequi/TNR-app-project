<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro | CES Gatos Elche</title>
  <link rel="icon" type="image/png" href="../public/assets/brand/LOGO-CES-2.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../public/assets/dist/css/styles.css" rel="stylesheet" />
</head>

<body class="bg-light d-flex flex-column min-vh-100">

  <div class="container-xxl">
    <header
      class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-light text-dark">

      <div class="col-md-3 mb-2 mb-md-0">
        <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
          <img src="../public/assets/brand/LOGO-CES-2.png" alt="Logo" width="100" height="auto" />
        </a>
      </div>
      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
          <li><a href="../index.html" class="nav-link px-2 ">Inicio</a></li>
          <li><a href="about.html" class="nav-link px-2">Sobre el proyecto</a></li>

        </ul>
      </ul>
      <div class="col-md-3 text-end">
        <a href="login.php"><button type="button" class="btn btn-outline-primary me-2">Login</button></a>

        <a href="registro.php"><button type="button" class="btn btn-primary">Registro</button></a>
      </div>
    </header>
  </div>
  <main class="bg-body-secondary flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="container col-12 col-md-8 col-lg-6 py-5">
      <div class="mx-auto" style="max-width: 460px;">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">

           

            <h2 class="mb-2 text-center fw-bold">Recuperar contraseña </h2>

            <form method="post" action="../app/actions/recuperar_action.php">
                <p class="text-center mt-3 mb-0"> Introduce tu nombre y el correo electrónico con el que te registraste. <br>
                Si coinciden, generaremos una nueva contraseña temporal.</p>

              <!-- Nombre -->
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre"  maxlength="30" required>
              </div>

             

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="tucorreo@email.com" required>
              </div>

        

              <!-- Botón -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Recuperar contraseña</button>
              </div>

            </form>

            <p class="text-center mt-3 mb-0">
              <a href="login.php" class="text-primary">← Volver al Login</a>
            </p>

          </div>
        </div>
      </div>

  </main>
  <!-- FOOTER -->

  <footer class="bg-dark text-light py-4 mt-auto">
    <div class="container d-flex flex-column flex-md-row flex-wrap 
              justify-content-center justify-content-md-between 
              align-items-center text-center text-md-start">

      <div class="col-md-4 d-flex align-items-center justify-content-center justify-content-md-start mb-3 mb-md-0">
        <a href="/" class="mb-3 me-2 mb-md-0 text-light text-decoration-none lh-1">
          <img src="../public/assets/brand/LOGO-CES-2.png" alt="Logo" width="50" height="auto" />
        </a>
        <span class="mb-3 mb-md-0">&copy; 2025 CES Gatos Elche</span>
      </div>

      <ul class="nav justify-content-center mb-3 mb-md-0 ">
        <li class="nav-item ">
          <a href="index.html" class="nav-link px-2 text-body-secondary ">Inicio</a>
        </li>
        <li class="nav-item">
          <a href="about.html" class="nav-link px-2 text-body-secondary">Características</a>
        </li>

      </ul>

      <ul class="nav justify-content-center justify-content-md-end list-unstyled d-flex">
        <li class="ms-3">
          <a class="text-light" href="#" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram"
              viewBox="0 0 16 16">
              <path
                d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
            </svg>
          </a>
        </li>
        <li class="ms-3">
          <a class="text-light" href="#" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook"
              viewBox="0 0 16 16">
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