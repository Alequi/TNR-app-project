<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';


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

<!-- HEADER -->
<?php include_once 'partials/header.php'; ?>
<!-- HEADER -->
 
  <main class="bg-body-secondary flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="container col-12 col-md-8 col-lg-6 py-5">
      <div class="mx-auto" style="max-width: 460px;">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">

           

            <h2 class="mb-2 text-center fw-bold">Recuperar contraseña </h2>

            <form method="post" action="../app/actions/recuperar_action.php">
                <p class="text-center mt-3 mb-0">Introduce el correo electrónico con el que te registraste. <br>
                Si coinciden, generaremos una nueva contraseña temporal.</p>

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

  <?php include_once 'partials/footer.php'; ?>
  
   <!-- FOOTER -->

     
       <script src="../public/assets/js/script.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>