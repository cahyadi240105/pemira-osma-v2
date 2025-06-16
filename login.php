<?php
require_once "auth/config.php";
session_start();

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $pwd = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);

    if ($stmt->rowCount() === 1) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ambil salt dan hash password input + salt
        $salt = $data['pw_salt'];
        $hashedInput = hash('sha3-256', $salt . $pwd);

        if ($hashedInput === $data['password']) {
            // Cek role yang diizinkan login
            if (in_array($data['role'], ['admin', 'user'])) {
                $_SESSION['user'] = $data;
                header('Location: index.php');
                exit;
            } else {
                $error = "Akses tidak diizinkan untuk role ini.";
            }
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="logo web/3.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="logo web/3.png" alt="logo">
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <?php if (!empty($error)): ?>
                <div class="alert alert-danger mt-3" role="alert">
                  <?= htmlspecialchars($error); ?>
                </div>
              <?php endif; ?>

              <form class="pt-3" method="post" action="">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username" name="username">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="password" name="password">
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" name="login">SIGN IN</button>
                </div>
                <!-- <div class="text-center mt-3">
                  <span class="text-muted">Belum punya akun? </span>
                  <a href="register.php" class="text-primary font-weight-bold">Daftar di sini</a>
                </div> -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>