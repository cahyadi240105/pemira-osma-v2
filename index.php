<?php
session_start();
require_once 'auth/config.php';
include 'auth/title.php';
require_once 'auth/function.php';
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$id_user = $_SESSION['user']['id_users'];
$stmtUser = $pdo->prepare("SELECT role, status_vote FROM users WHERE id_users = :id");
$stmtUser->execute([':id' => $id_user]);
$userData = $stmtUser->fetch();
if (!$userData) {
  session_destroy();
  header("Location: login.php?error=akses-tidak-valid");
  exit;
}
$role = $userData['role'];
$status_vote = $userData['status_vote'];
$bolehLihat = ($role === 'admin' || $status_vote === 'sudah');

$labels = [];
$data = [];

$stmt = $pdo->query("SELECT nama_calon, jumlah_suara FROM calon");
while ($row = $stmt->fetch()) {
  $labels[] = $row['nama_calon'];
  $data[] = $row['jumlah_suara'];
}

$totalPemilih       = countWhere($pdo, 'users', 'role', 'user');
$totalSudahMemilih  = countWhereMulti($pdo, 'users', ['role' => 'user', 'status_vote' => 'sudah']);
$totalBelumMemilih  = countWhereMulti($pdo, 'users', ['role' => 'user', 'status_vote' => 'belum']);
$totalKandidat      = countRows($pdo, 'calon');
$totalSuaraMasuk    = countRows($pdo, 'vote_logs');


$partisipasi = $totalPemilih > 0 ? round(($totalSudahMemilih / $totalPemilih) * 100, 2) : 0;

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
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="logo web/3.png" />
  <!-- icon -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
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
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">

        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php include 'partials/_sidebar.php'; ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Welcome To Pemira - Osma</h3>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <?php if (isAdmin()): ?>
              <?php if (empty($_SESSION['sudah_reset'])): ?>
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="alert alert-danger d-flex justify-content-between align-items-center">
                      <div>
                        <h4 class="alert-heading">Reset Pemilu</h4>
                        <p>Menghapus seluruh log suara dan mengatur ulang status pemilih serta suara kandidat.</p>
                      </div>
                      <div>
                        <a href="reset_voting.php" onclick="return confirm('Yakin ingin mereset semua data pemilu?')" class="btn btn-danger">
                          <i class="mdi mdi-delete-forever"></i> Reset Pemilu
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <div class="col-md-6 grid-margin transparent">
                <div class="row">
                  <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-tale">
                      <div class="card-body">
                        <p class="mb-4">Total Pemilih Terdaftar</p>
                        <p class="fs-30 mb-2"><?= $totalPemilih; ?> </p>
                        <p>Data semua pemilih</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-dark-blue">
                      <div class="card-body">
                        <p class="mb-4">Total Sudah Memilih</p>
                        <p class="fs-30 mb-2"> <?= $totalSudahMemilih; ?> </p>
                        <p>Jumlah yang sudah memberikan suara</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                    <div class="card card-light-blue">
                      <div class="card-body">
                        <p class="mb-4">Belum Memilih</p>
                        <p class="fs-30 mb-2"><?= $totalBelumMemilih; ?></p>
                        <p><?= $totalBelumMemilih > 0 ? 'Masih menunggu memilih' : 'Semua sudah memilih'; ?></p>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6 stretch-card transparent">
                    <div class="card card-light-danger">
                      <div class="card-body">
                        <p class="mb-4">Jumlah Kandidat</p>
                        <p class="fs-30 mb-2"><?= $totalKandidat; ?> </p>
                        <p>Calon yang tersedia</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-light-warning bg-warning">
                      <div class="card-body">
                        <p class="mb-4">Total Suara Masuk</p>
                        <p class="fs-30 mb-2"> <?= $totalSuaraMasuk; ?> </p>
                        <p>Akumulasi semua suara</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-light-success bg-success">
                      <div class="card-body">
                        <p class="mb-4">Persentase Partisipasi</p>
                        <p class="fs-30 mb-2"><?= $partisipasi; ?> %</p>
                        <p>Partisipasi pemilih</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            <?php endif; ?>
            <!-- Bagian Kanan: Pie Chart -->
            <?php if ($bolehLihat): ?>
              <div class="col-lg-6 grid-margin grid-margin-lg-0 stretch-card">
                <div class="card">
                  <?php
                  $statusList = [
                    'reset_sukses' => ['class' => 'bg-success', 'pesan' => 'Pemilu berhasil direset!'],
                    'reset_gagal' => ['class' => 'bg-danger', 'pesan' => 'Gagal mereset pemilu. Coba lagi.'],
                    'unauthorized' => ['class' => 'bg-danger', 'pesan' => 'Akses tidak diizinkan.']
                  ];

                  if (isset($_GET['status']) && isset($statusList[$_GET['status']])) {
                    $s = $statusList[$_GET['status']];
                    echo '<div class="alert ' . $s['class'] . ' text-white fw-bold">' . $s['pesan'] . '</div>';
                  }
                  ?>
                  <div class="card-body">
                    <h4 class="card-title">Hasil Voting</h4>
                    <div class="d-flex flex-wrap mb-3" style="gap: 1rem;">
                      <?php
                      $colors = [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                      ];

                      foreach ($labels as $index => $label): ?>
                        <div class="card shadow-sm border-0" style="width: 12rem; margin-bottom: 0.5rem;">
                          <div class="card-body py-2 px-3 d-flex align-items-center">
                            <div class="rounded-circle" style="width: 15px; height: 15px; background-color: <?= $colors[$index]; ?>;"></div>
                            <strong class="ms-2">Kandidat <?= htmlspecialchars($label); ?></strong>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <canvas id="pieChart"></canvas>
                  </div>
                </div>
              </div>
            <?php else: ?>
              <!-- Jika belum memilih -->
              <div class="alert alert-warning col-lg-6">Anda belum diberi akses untuk melihat hasil voting.</div>
            <?php endif; ?>
          </div>

        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php include 'partials/_footer.php' ?>
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
  <!-- <script src="vendors/chart.js/Chart.min.js"></script> -->
  <!-- <script src="vendors/datatables.net/jquery.dataTables.js"></script> -->
  <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="js/dataTables.select.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <!-- End custom js for this page-->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="js/chart.js"></script>
  <script src="vendors/chart.js/Chart.min.js"></script>
  <!-- <script>
    const ctx = document.getElementById('pieChart').getContext('2d');
    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Today’s Bookings', 'Total Bookings', 'Meetings', 'Clients'],
        datasets: [{
          data: [4006, 61344, 34040, 47033],
          backgroundColor: [
            '#f39c12', // card-tale (kuning)
            '#34495e', // card-dark-blue
            '#3498db', // card-light-blue
            '#e74c3c'  // card-light-danger
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  </script> -->

  <!-- Chart JS Script -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctx, {
      type: 'pie',
      data: {
        datasets: [{
          label: 'Jumlah Suara',
          data: <?= json_encode($data); ?>,
          backgroundColor: [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)'
          ],
          borderColor: [
            'rgba(255, 255, 255, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
          },
          title: {
            display: true,
            text: 'Distribusi Suara Kandidat'
          }
        }
      }
    });
  </script>
</body>

</html>