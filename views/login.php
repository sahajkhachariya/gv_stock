<?php
session_start();
require_once '../config/db.php';

$db = new DB();
$conn = $db->connect();

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password']));

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Stock Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
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
          <?php if (!empty($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

      <form class="mt-4" method="POST" action="">

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

  <style>
    /* styles.css */

/* Background color from the design */
.bg-dark-blue {
  background-color: #002c6f;
}

/* Simulated bar chart */
.chart-bar {
  display: flex;
  gap: 5px;
  align-items: flex-end;
  height: 80px;
}

.bar {
  width: 12px;
  background-color: #00e0ff;
  border-radius: 4px;
}

.bar-1 { height: 30px; }
.bar-2 { height: 50px; background-color: #00c2ff; }
.bar-3 { height: 70px; background-color: #00aaff; }
.bar-4 { height: 40px; background-color: #008cff; }

.login-box {
  max-width: 400px;
  width: 100%;

  @media screen and (max-width: 768px) {
  .login-box {
    padding: 2rem;
    width: 100%;
    max-width: 400px;
  }

  .display-3 {
    font-size: 2rem;
  }

  .lead {
    font-size: 1rem;
  }

  .icon-section {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .icon-set {
    flex-direction: row;
    gap: 1.5rem;
  }
}

@media screen and (max-width: 576px) {
  .container-fluid {
    padding: 1rem;
  }

  .login-box {
    padding: 1.5rem;
  }

  .chart-bar {
    height: 60px;
  }

  .bar {
    width: 10px;
  }

  .bar-1 { height: 25px; }
  .bar-2 { height: 40px; }
  .bar-3 { height: 55px; }
  .bar-4 { height: 35px; }

  .icon-set i {
    font-size: 1.5rem;
  }

  .btn {
    font-size: 14px;
    padding: 8px 12px;
  }
}

}



  </style>
</body>
</html>
