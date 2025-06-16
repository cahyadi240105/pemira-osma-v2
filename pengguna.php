<?php
include 'auth/title.php';
require_once 'auth/config.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!--  meta tags -->
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
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="logo web/3.png" />
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

</head>

<body>
    <div class="container-scroller">

        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5 <?= $current_page == 'index.php' ? 'active' : ''; ?>" href="index.php"><img src="logo web/3.png" class="mr-2" alt="logo" style="height : 50px;" /></a>
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
                        <div class="col-lg-12 grid-margin stretch-card mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title mb-0">Data Pengguna</h4>
                                        <div class="d-flex">
                                            <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#modalTambahCalon">
                                                <i class="fa-solid fa-user-plus"></i>
                                                Tambah Pemilih
                                            </button>
                                            <!-- cek spredsheet -->
                                            <!-- <?php
                                                    if (class_exists('ZipArchive')) {
                                                        echo "ZipArchive is enabled!";
                                                    } else {
                                                        echo "ZipArchive is NOT enabled.";
                                                    }
                                                    ?> -->
                                            <!-- Modal Tambah Calon -->
                                            <div class="modal fade" id="modalTambahCalon" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form class="modal-content" action="insert_pengguna.php" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel">Tambah Pemilih</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Username</label>
                                                                <input type="text" name="username" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nim</label>
                                                                <input type="text" name="nim" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nama Lengkap</label>
                                                                <input type="text" name="nama_lengkap" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- Tombol trigger modal -->
                                            <button class="btn btn-success" data-toggle="modal" data-target="#modalImport">
                                                <i class="fa-solid fa-file-import"></i> Import Excel
                                            </button>

                                            <!-- Modal Upload Excel -->
                                            <div class="modal fade" id="modalImport" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <form action="import_excel.php" method="POST" enctype="multipart/form-data" class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Import Data dari Excel</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Pilih File Excel (.xlsx)</label>
                                                                <input type="file" name="file_excel" accept=".xlsx" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Import</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <p class="card-description">
                                        <code></code>
                                    </p>

                                    <div class="table-responsive">
                                        <table class="table" id="myTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Username</th>
                                                    <th>Nim</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Status Vote</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                $stmt = $pdo->query("SELECT * FROM users WHERE role = 'user'");
                                                while ($row = $stmt->fetch()) {
                                                ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $row['username']; ?></td>
                                                        <td><?= $row['nim']; ?></td>
                                                        <td><?= $row['nama_lengkap']; ?></td>
                                                        <td><?= $row['status_vote']; ?></td>
                                                        <td>
                                                            <a href="#" data-toggle="modal" data-target="#modalEdit<?= $row['id_users']; ?>">
                                                                <label class="badge badge-warning text-white">Edit</label>
                                                            </a> |
                                                            <a href="" data-toggle="modal" data-target="#modalHapus<?= $row['id_users']; ?>">
                                                                <label class="badge badge-danger">Hapus</label>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Modal Edit - -->
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'user'");
                                    while ($row = $stmt->fetch()) {
                                    ?>
                                        <div class="modal fade" id="modalEdit<?= $row['id_users']; ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <form class="modal-content" action="update_user.php" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Pengguna</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_users" value="<?= $row['id_users']; ?>">

                                                        <div class="form-group">
                                                            <label>Username</label>
                                                            <input type="text" name="username" class="form-control" value="<?= $row['username']; ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>NIM</label>
                                                            <input type="text" disabled="nim" class="form-control" value="<?= $row['nim']; ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Nama Lengkap</label>
                                                            <input type="text" name="nama_lengkap" class="form-control" value="<?= $row['nama_lengkap']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-warning text-white">Simpan Perubahan</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    $users = [];
                                    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'user'");
                                    while ($row = $stmt->fetch()) {
                                        $users[] = $row;
                                    }
                                    foreach ($users as $row) {
                                    ?>
                                        <div class="modal fade" id="modalHapus<?= $row['id_users']; ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 48px;"></i>
                                                            <h4 class="mt-3">Apakah Anda yakin?</h4>
                                                            <p class="text-muted">
                                                                Data pengguna <strong><?= $row['nama_lengkap']; ?></strong>
                                                                dengan NIM <strong><?= $row['nim']; ?></strong> akan dihapus secara permanen.
                                                            </p>
                                                            <p class="text-danger">
                                                                <small><i class="fas fa-info-circle"></i> Tindakan ini tidak dapat dibatalkan!</small>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <a href="hapus_user.php?id=<?= $row['id_users']; ?>" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i> Ya, Hapus
                                                        </a>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                            <i class="fas fa-times"></i> Batal
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Notifikasi dinamis berdasarkan status
                        $status = $_GET['status'] ?? null;
                        $toast = [
                            'berhasil' => ['class' => 'bg-success', 'pesan' => 'Data berhasil disimpan!'],
                            'update'   => ['class' => 'bg-warning', 'pesan' => 'Data berhasil diperbarui!'],
                            'hapus'    => ['class' => 'bg-danger',  'pesan' => 'Data berhasil dihapus!'],
                            'error_input' =>  ['class' => 'bg-danger',  'pesan' => ' Ada input kosong.'],
                            'gagal'    => ['class' => 'bg-danger',  'pesan' => 'Terjadi kesalahan. Silakan coba lagi!'],
                        ];
                        ?>
                        <?php if ($status && isset($toast[$status])): ?>
                            <!-- Notifikasi Dinamis -->
                            <div class="toast position-fixed" style="bottom: 20px; right: 20px; z-index: 9999;" data-delay="6000">
                                <div class="toast-header <?= $toast[$status]['class'] ?> text-white">
                                    <div class="toast-body">
                                        <?= $toast[$status]['pesan'] ?>
                                    </div>
                                    <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">&times;</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
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
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "pageLength": 5, // default tampil 5 data
                "lengthMenu": [5, 10, 25, 50], // pilihan jumlah data per halaman
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.toast').toast('show');
        });
    </script>
</body>

</html>