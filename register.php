<?php
require_once "auth/config.php";
session_start();

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);
    $nama     = trim($_POST['nama_lengkap']);
    $nim      = trim($_POST['nim']);

    if (empty($username) || empty($password) || empty($nama) || empty($confirm) || empty($nim)) {
        $error = "Semua field harus diisi.";
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $salt = bin2hex(random_bytes(16));
        $hashedPwd = hash('sha3-256', $salt . $password); 

        $check = $pdo->prepare("SELECT username FROM users WHERE username = :username");
        $check->execute([':username' => $username]);

        if ($check->rowCount() > 0) {
            $error = "Username sudah terdaftar.";
        } else {

            $stmt = $pdo->prepare("INSERT INTO users 
                (username, password, pw_salt, nama_lengkap, nim, role, status_vote) 
                VALUES (:username, :password, :pw_salt, :nama_lengkap, :nim, :role, :status_vote)");
            $inserted = $stmt->execute([
                ':username'     => $username,
                ':password'     => $hashedPwd,
                ':pw_salt'      => $salt,
                ':nama_lengkap' => $nama,
                ':nim'          => $nim,
                ':role'         => 'admin',
                ':status_vote'  => 'belum'
            ]);

            if ($inserted) {
                $success = "Registrasi berhasil. Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat registrasi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>
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
    <link rel="shortcut icon" href="images/favicon.png" />
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
                            <?php if ($error): ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    <?= htmlspecialchars($error) ?>
                                </div>
                            <?php elseif ($success): ?>
                                <div class="alert alert-success mt-3" role="alert">
                                    <?= htmlspecialchars($success) ?>
                                </div>
                            <?php endif; ?>
                            <form method="post" action="">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" placeholder="Masukan nama lengkap" name="nama_lengkap">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" placeholder="Masukan NIM" name="nim">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" placeholder="Username" name="username">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" placeholder="Password" name="password">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" placeholder="Konfirmasi Password" name="confirm">
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" name="register">Sign Up</button>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="text-muted">Sudah punya akun? </span>
                                    <a href="login.php" class="text-primary font-weight-bold">Sign In</a>
                                </div>
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