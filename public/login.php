<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicia sesión | CES Gatos Elche</title>
  <link rel="icon" type="image/png" href="../public/assets/brand/LOGO-CES-2.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="../public/assets/dist/css/styles.css" rel="stylesheet" />
</head>

<body class="bg-light d-flex flex-column min-vh-100">

<!-- HEADER -->
  <div class="container-xxl">
    <?php include_once 'partials/header.php'; ?>
  </div>

  <!-- HEADER -->

  <!-- MAIN CONTENT -->

  <main class="bg-body-secondary flex-grow-1 d-flex justify-content-center align-items-center">

    <div class="container col-12 col-md-8 col-lg-6 py-5 px-2 ">
      <div class="mx-auto" style="max-width: 460px;">
        <div class="card shadow-sm border-0 ">
          <div class="card-body p-4 ">


            <!--FORMULARIO -->
            <h2 class="mb-2 text-center fw-bold">Entra a tu panel</h2>
            <p class="text-center text-body-secondary lead">Gestión de campañas CER</p>

            <form method="POST" action="../app/actions/login_action.php">



              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="tucorreo@email.com" required>
              </div>

              <!-- Contraseña -->
               <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>

                <div class="position-relative"> 
                  <input type="password" class="form-control pe-5" id="password"   name="password" placeholder="••••••••" required>

                  <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePass" ></i>
                  
                </div>
              </div>
              <p class="text-center mt-2 mb-3">
                ¿Has olvidado tu contraseña?
                <a href="recuperar_pass.php" class="text-primary m-2">Recupérala </a>
              </p>
              <!-- Botón -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg d-inline-flex justify-content-center gap-2"> Iniciar
                  sesión<i class="bi bi-box-arrow-in-right"></i></button>
              </div>

            </form>

            <p class="text-center mt-3 mb-0">
              ¿No tienes cuenta?
              <a href="registro.php" class="text-primary m-2">Regístrate aquí</a>
            </p>
            <p class="text-center mt-3 mb-0 fw-bold">
              <a href="adminDashboard.html" class="text-primary m-2">Pincha aquí para ir al Dashboard</a>
            </p>


          </div>
        </div>
      </div>
    </div>

  </main>
  
  <!-- FOOTER -->

  <?php include_once 'partials/footer.php'; ?>

  <!-- FOOTER -->

  <script src="../public/assets/js/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>