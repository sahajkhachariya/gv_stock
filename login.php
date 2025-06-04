<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Stock Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- For icons -->
</head>
<body>

  <div class="container-fluid min-vh-100 d-flex align-items-center bg-dark-blue text-white">
    <div class="row w-100">

      <!-- Left Section -->
      <div class="col-md-6 d-flex flex-column justify-content-center ps-md-5 px-4">
        <h1 class="display-3 fw-bold">Welcome Back<span class="text-white"> !</span></h1>
        <p class="lead mt-3">
          Today is a new day. It’s your day. You shape it. <br />
          Sign in to start managing your stocks.
        </p>

        <!-- Icons / Charts Section -->
        <div class="icon-section mt-4 d-flex align-items-center gap-4 flex-wrap">
          <div class="chart-bar">
            <div class="bar bar-1"></div>
            <div class="bar bar-2"></div>
            <div class="bar bar-3"></div>
            <div class="bar bar-4"></div>
          </div>
          <div class="icon-set d-flex flex-column gap-3">
            <i class="fas fa-arrow-up text-success fs-3"></i>
            <i class="fas fa-dollar-sign text-info fs-3"></i>
            <i class="fas fa-coins text-light fs-3"></i>
          </div>
        </div>
      </div>

      <!-- Right Section -->
      <div class="col-md-6 d-flex justify-content-center align-items-center px-4 mt-5 mt-md-0">
        <div class="login-box bg-light text-dark rounded-4 p-5 shadow" style="min-width: 300px;">
          <h4 class="fw-semibold">Login</h4>
          <small class="text-muted">Glad you're back</small>
      <form class="mt-4" method="POST" action="">
  <?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      require_once 'includes/config.php';

      $input_user = $_POST['username'];
      $input_pass = $_POST['password'];

      if ($input_user === $admin_username && $input_pass === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: home.php");
        exit();
      } else {
        echo '<div class="alert alert-danger" role="alert">Invalid credentials!</div>';
      }
    }
  ?>
  <div class="mb-3">
    <input type="text" class="form-control" name="username" placeholder="Username" required />
  </div>
  <div class="mb-4">
    <input type="password" class="form-control" name="password" placeholder="Password" required />
  </div>
  <button type="submit" class="btn btn-primary w-100 fw-bold">LOGIN</button>
</form>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
