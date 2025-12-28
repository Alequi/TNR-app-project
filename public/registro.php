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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="../public/assets/dist/css/styles.css" rel="stylesheet" />
  
</head>

<body class="bg-light d-flex flex-column min-vh-100">

<!-- HEADER -->

<?php include_once 'partials/header.php'; ?>

<!-- HEADER -->

  <!-- MAIN CONTENT -->


  <main class="bg-body-secondary flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="container col-12 col-md-8 col-lg-6 py-5">
      <div class="mx-auto" style="max-width: 460px;">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">

            <?php if (isset($_SESSION['error_mail'])): ?>
              <div class="alert alert-danger"><?php echo $_SESSION['error_mail'];
                                              unset($_SESSION['error_mail']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_general'])): ?>
              <div class="alert alert-danger"><?php echo $_SESSION['error_general'];
                                              unset($_SESSION['error_general']); ?></div>
            <?php endif; ?>

            <h2 class="mb-2 text-center fw-bold">Crear cuenta </h2>

            <form method="post" action="../app/actions/register_action.php">

              <!-- Nombre -->
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" maxlength="30" required>
              </div>

              <!-- Apellidos -->
              <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Tus apellidos" required>
              </div>

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

              <!-- telefono -->
              <div class="mb-3">
                <label for="telefono" class="form-label">Telefono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="666666666" pattern="[0-9]{9}" size="9" required>
              </div>

              <!-- Botón -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Registrarme</button>
              </div>

            </form>

            <p class="text-center mt-3 mb-0">
              ¿Ya tienes cuenta?
              <a href="login.php" class="text-primary">Inicia sesión aquí</a>
            </p>

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