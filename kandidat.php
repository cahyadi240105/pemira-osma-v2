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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title; ?></title>
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
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
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
                                        <h4 class="card-title mb-0">Data Kandidat</h4>

                                        <!-- Tombol Tambah -->
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahCalon">
                                            <i class="fa-solid fa-user-plus"></i>
                                            Tambah Calon
                                        </button>

                                        <!-- Modal Tambah Calon -->
                                        <div class="modal fade" id="modalTambahCalon" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form class="modal-content" action="insert_calon.php" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel">Tambah Calon</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>No Calon</label>
                                                            <input type="text" name="no_calon" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nama Calon</label>
                                                            <input type="text" name="nama_calon" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Foto</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="foto" class="form-control" id="foto">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Visi</label>
                                                            <textarea name="visi" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Misi</label>
                                                            <textarea name="misi" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Simpan</button>
                                                    </div>
                                                </form>
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
                                                    <th>No.</th>
                                                    <th>No Calon.</th>
                                                    <th>Nama Calon</th>
                                                    <th>Foto</th>
                                                    <th>Visi</th>
                                                    <th>Misi</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                $stmt = $pdo->query("SELECT * FROM calon");
                                                while ($row = $stmt->fetch()) {
                                                ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><?= $row['no_calon']; ?></td>
                                                        <td><?= $row['nama_calon']; ?></td>
                                                        <td><img src="images/<?= $row['foto']; ?>" alt="" width="" style="border-radius: 0;" class="img-fluid img-square mt-2"></td>
                                                        <td><?= $row['visi']; ?></td>
                                                        <td><?= $row['misi']; ?></td>
                                                        <td>
                                                            <a href="" data-toggle="modal" data-target="#modalEdit<?= $row['id_calon']; ?>">
                                                                <label class="badge badge-warning text-white">Edit</label>
                                                            </a> |
                                                            <a href="" data-toggle="modal" data-target="#modalHapus<?= $row['id_calon']; ?>">
                                                                <label class="badge badge-danger">Hapus</label>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <!-- Modal Edit Calon -->
                                        <?php
                                        $stmt = $pdo->query("SELECT * FROM calon ORDER BY no_calon");
                                        while ($row = $stmt->fetch()) {
                                        ?>
                                            <div class="modal fade" id="modalEdit<?= $row['id_calon']; ?>" tabindex="-1" role="dialog">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <form class="modal-content" action="update_calon.php" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">
                                                                <i class="fas fa-user-edit"></i> Edit Calon
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <input type="hidden" name="id_calon" value="<?= $row['id_calon']; ?>">

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="no_calon<?= $row['id_calon']; ?>">
                                                                            <i class="fas fa-hashtag"></i> Nomor Calon <span class="text-danger">*</span>
                                                                        </label>
                                                                        <input type="number"
                                                                            id="no_calon<?= $row['id_calon']; ?>"
                                                                            name="no_calon"
                                                                            class="form-control"
                                                                            value="<?= $row['no_calon']; ?>"
                                                                            min="1"
                                                                            required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="nama_calon<?= $row['id_calon']; ?>">
                                                                            <i class="fas fa-user"></i> Nama Calon <span class="text-danger">*</span>
                                                                        </label>
                                                                        <input type="text"
                                                                            id="nama_calon<?= $row['id_calon']; ?>"
                                                                            name="nama_calon"
                                                                            class="form-control"
                                                                            value="<?= $row['nama_calon']; ?>"
                                                                            required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="foto<?= $row['id_calon']; ?>">
                                                                            <i class="fas fa-image"></i> Foto Calon
                                                                        </label>
                                                                        <input type="file"
                                                                            id="foto<?= $row['id_calon']; ?>"
                                                                            name="foto"
                                                                            class="form-control-file"
                                                                            accept="images/*">
                                                                        <small class="form-text text-muted">
                                                                            Kosongkan jika tidak ingin mengubah foto.
                                                                        </small>
                                                                        <?php if (!empty($row['foto'])): ?>
                                                                            <div class="mt-2">
                                                                                <img src="images/<?= $row['foto']; ?>"
                                                                                    alt="Foto <?= $row['nama_calon']; ?>"
                                                                                    class="img-thumbnail"
                                                                                    style="max-width: 100px; max-height: 100px;">
                                                                                <br><small class="text-muted">Foto saat ini</small>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="visi<?= $row['id_calon']; ?>">
                                                                            <i class="fas fa-eye"></i> Visi <span class="text-danger">*</span>
                                                                        </label>
                                                                        <textarea id="visi<?= $row['id_calon']; ?>"
                                                                            name="visi"
                                                                            class="form-control"
                                                                            rows="5"
                                                                            required
                                                                            placeholder="Masukkan visi calon..."><?= $row['visi']; ?></textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="misi<?= $row['id_calon']; ?>">
                                                                            <i class="fas fa-list"></i> Misi <span class="text-danger">*</span>
                                                                        </label>
                                                                        <textarea id="misi<?= $row['id_calon']; ?>"
                                                                            name="misi"
                                                                            class="form-control"
                                                                            rows="5"
                                                                            required
                                                                            placeholder="Masukkan misi calon..."><?= $row['misi']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-warning text-white">
                                                                <i class="fas fa-save"></i> Simpan Perubahan
                                                            </button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                <i class="fas fa-times"></i> Batal
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        $stmt = $pdo->query("SELECT * FROM calon ORDER BY no_calon");
                                        while ($row = $stmt->fetch()) {
                                        ?>
                                            <div class="modal fade" id="modalHapus<?= $row['id_calon']; ?>" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body text-center">
                                                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 48px;"></i>
                                                            <h4 class="mt-3">Apakah Anda yakin?</h4>
                                                            <p class="text-muted">
                                                                Data calon <strong><?= $row['nama_calon']; ?></strong> dengan nomor <strong><?= $row['no_calon']; ?></strong> akan dihapus secara permanen.
                                                            </p>
                                                            <p class="text-danger">
                                                                <small><i class="fas fa-info-circle"></i> Tindakan ini tidak dapat dibatalkan!</small>
                                                            </p>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <a href="hapus_calon.php?id=<?= $row['id_calon']; ?>" class="btn btn-danger">
                                                                <i class="fas fa-trash"></i> Ya, Hapus
                                                            </a>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                <i class="fas fa-times"></i> Batal
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                                <?php
                                // Notifikasi dinamis berdasarkan status
                                $status = $_GET['status'] ?? null;
                                $toast = [
                                    'berhasil' => ['class' => 'bg-success', 'pesan' => 'Data berhasil disimpan!'],
                                    'update' => ['class' => 'bg-warning', 'pesan' => 'Data berhasil diperbarui!'],
                                    'hapus' => ['class' => 'bg-danger', 'pesan' => 'Data berhasil dihapus!'],
                                    'gagal' => ['class' => 'bg-danger', 'pesan' => 'Terjadi kesalahan. Silakan coba lagi!'],
                                    'tidak_ada_foto' => ['class' => 'bg-warning', 'pesan' => 'Tidak ada file foto dikirim.'],
                                    'error_input' => ['class' => 'bg-danger', 'pesan' => 'Ada input kosong.'],
                                    'gagal_upload' => ['class' => 'bg-danger', 'pesan' => 'Gagal upload file gambar.'],
                                    'mime_invalid' => ['class' => 'bg-danger', 'pesan' => 'Tipe file tidak valid. Harus berupa gambar (jpg, png, webp).'],
                                    'ukuran_terlalu_besar' => ['class' => 'bg-warning', 'pesan' => 'Ukuran file terlalu besar. Maksimum 2MB.'],
                                    'ekstensi_tidak_diperbolehkan' => ['class' => 'bg-danger', 'pesan' => 'Ekstensi file tidak diperbolehkan.']
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
    <script>
        $(document).ready(function() {
            $('.toast').toast('show');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>