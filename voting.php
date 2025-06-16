<?php
include 'auth/title.php';
require_once 'auth/config.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$calon = $pdo->query("SELECT * FROM calon")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?></title>
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
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
</head>

<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="index.html">
                    <img src="logo web/3.png" class="mr-2"
                        alt="logo" style="height: 50px;" />
                    </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now"
                                aria-label="search" aria-describedby="search">
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">

                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">

            <!-- partial:../../partials/_sidebar.html -->
            <?php include 'partials/_sidebar.php'; ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <?php
                        $id_user = $_SESSION['user']['id_users'];
                        $vote_stmt = $pdo->query("SELECT id_calon FROM vote_logs WHERE id_user = $id_user");
                        $already_voted = $vote_stmt->fetchColumn();
                        ?>

                        <?php if (!empty($calon)) : ?>
                            <?php foreach ($calon as $r) : ?>
                                <div class="col-lg-6 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h4 class="card-title">Calon Kandidat <?= $r['no_calon']; ?></h4>
                                            <img src="<?= ('images/' . $r['foto']); ?>" alt="Foto Calon" class="img-fluid mb-3" style="max-height:200px;">
                                            <p class="card-text"><?= $r['nama_calon']; ?></p>
                                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin'): ?>
                                                <a href="#" class="btn btn-primary " data-toggle="modal" data-target="#modalVisiMisi<?= $r['id_calon']; ?>">Lihat Visi & Misi</a>
                                                <?php if ($already_voted): ?>
                                                    <button class="btn btn-secondary" disabled>Sudah Memilih</button>
                                                <?php else: ?>
                                                    <a href="pilih.php?id=<?= $r['id_calon']; ?>" class="btn btn-success">Pilih</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="d-flex justify-content-center align-items-center col-12" style="min-height: 50vh;">
                                <div class="card text-center" style="width: 100%; max-width: 500px;">
                                    <div class="card-body">
                                        <h4 class="card-title">Belum Masuk Musim Pemira</h4>
                                        <p class="card-text">Saat ini belum ada calon yang terdaftar. Silakan cek kembali nanti.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php foreach ($calon as $r) : ?>
                            <div class="modal fade" id="modalVisiMisi<?= $r['id_calon']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalVisiMisiLabel<?= $r['id_calon']; ?>" aria-hidden="true">
                                <div class="modal-dialog  modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title" id="modalVisiMisiLabel<?= $r['id_calon']; ?>">
                                                Visi & Misi - Calon Nomor <?= $r['no_calon']; ?>
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <h6><strong>Nama:</strong> <?= $r['nama_calon']; ?></h6>
                                            <hr>
                                            <h6><strong>Visi:</strong></h6>
                                            <p><?= $r['visi']; ?></p>
                                            <h6><strong>Misi:</strong></h6>
                                            <p><?= $r['misi']; ?></p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                <i class="fas fa-times"></i> Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (isset($_GET['status'])): ?>
                            <div class="alert alert-info ml-4">
                                <?php
                                if ($_GET['status'] == 'berhasil_memilih') {
                                    echo "Pilihan Anda telah disimpan.";
                                } elseif ($_GET['status'] == 'sudah_memilih') {
                                    echo "Anda sudah memilih. Setiap user hanya dapat memilih satu kali.";
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                <?php include 'partials/_footer.php'; ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
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
    <!-- Custom js for this page-->
    <!-- End custom js for this page-->
</body>

</html>