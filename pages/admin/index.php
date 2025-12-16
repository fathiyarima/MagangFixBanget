<?php
session_start();
$nama_admin = $_SESSION['username'];

include '../../config/connection.php';

$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$check = "SELECT nomor_telepon, nama_admin FROM admin WHERE username = :nama";
$checkNomer_telepon = $conn2->prepare($check);
$checkNomer_telepon->execute([':nama' => $nama_admin]);
$row = $checkNomer_telepon->fetch(PDO::FETCH_ASSOC);
$countTA = $conn2->query("SELECT COUNT(*) as totalTA FROM `tugas_akhir` WHERE 1");
$totalTA = (int) $countTA->fetch(PDO::FETCH_ASSOC)['totalTA'];
$countSem = $conn2->query("SELECT COUNT(*) as totalSem FROM `seminar_proposal` WHERE 1");
$totalSem = (int) $countSem->fetch(PDO::FETCH_ASSOC)['totalSem'];
$countUj = $conn2->query("SELECT COUNT(*) as totalUj FROM `ujian` WHERE 1");
$totalUj = (int) $countUj->fetch(PDO::FETCH_ASSOC)['totalUj'];

if ($row) {
  $nomor_telepon = $row['nomor_telepon'];
  $nama_admin= $row['nama_admin'];
} else {
  $nomor_telepon = '0857364562';
  $nama_admin = 'Nama Default';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
  <link rel="stylesheet" href="../../assets/css/css/admin/dashboard.css">
  <style>
    body.dark-mode {
      background-color: #121212;
      color: #e0e0e0;
    }
    body.dark-mode .card {
      background-color: #1e1e1e !important;
      color: #fff;
    }
    body.dark-mode .btn {
      border-color: #fff;
      color: #fff;
    }
    #loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #fff;
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style>
</head>

<body>
  <div id="loader">
    <div class="spinner-border text-primary"></div>
  </div>
  <div class="container-scroller">
    <?php include "sidebar2.php"; ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h4 id="greeting"></h4>
            <h5 id="clock" class="text-primary"></h5>
          </div>
          <button class="btn btn-sm btn-dark" onclick="toggleDarkMode()">🌙 Mode Gelap</button>
        </div>
        <h3 class="font-weight-bold">Selamat Datang di Politeknik NEST</h3>

        <div class="row mt-4">
          <div class="col-md-4 mb-4" data-aos="fade-right">
            <div class="card text-center">
              <div class="card-body">
                <img src="../../assets/img/profile-default.png" alt="admin" class="rounded-circle mb-2" width="80">
                <h5 class="card-title"><?php echo $nama_admin; ?></h5>
                <p class="card-text text-muted">Admin Sistem</p>
                <p><i class="ti-mobile"></i> <?php echo $nomor_telepon; ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-8 mb-4" data-aos="fade-left">
            <div class="alert alert-info" role="alert">
              🔔 Sistem akan mengalami perawatan pada tanggal 5 Juli pukul 22.00 WIB.
            </div>
          </div>
        </div>

        <div class="row mb-4" data-aos="zoom-in">
          <div class="col">
            <a href="daftarMahasiswa.php" class="btn btn-outline-primary w-100">
              <i class="ti-user"></i> Data Mahasiswa
            </a>
          </div>
          <div class="col">
            <a href="dokumenTA.php" class="btn btn-outline-secondary w-100">
              <i class="ti-folder"></i> Dokumen
            </a>
          </div>
          <div class="col">
            <a href="pendaftaranTA.php" class="btn btn-outline-success w-100">
              <i class="ti-calendar"></i> Pendaftaran
            </a>
          </div>
        </div>

        <div class="row mt-5">
          <div class="col-md-4 text-center" data-aos="fade-up">
            <h4>Total TA</h4>
            <h1 class="text-success" id="countTA">0</h1>
          </div>
          <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="200">
            <h4>Total Seminar</h4>
            <h1 class="text-info" id="countSeminar">0</h1>
          </div>
          <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="400">
            <h4>Total Ujian</h4>
            <h1 class="text-danger" id="countUjian">0</h1>
          </div>
        </div>

        <div class="row mb-4 mt-5">
          <div class="col">
            <div class="card bg-light text-center p-4 shadow-sm" data-aos="fade-up">
              <blockquote class="blockquote mb-0">
                <p>"Kesuksesan bukanlah akhir, kegagalan bukanlah kehancuran, keberanian untuk melanjutkan adalah yang terpenting."</p>
                <footer class="blockquote-footer">Winston Churchill</footer>
              </blockquote>
            </div>
          </div>
        </div>

        <?php include '../../pages/footer.php'; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();

    function toggleDarkMode() {
      document.body.classList.toggle("dark-mode");
    }

    function getGreeting() {
      const now = new Date();
      const hour = now.getHours();
      if (hour < 12) return "Selamat pagi 👋";
      if (hour < 18) return "Selamat siang ☀️";
      return "Selamat malam 🌙";
    }

    function updateClock() {
      const now = new Date();
      document.getElementById("clock").innerText = now.toLocaleTimeString("id-ID");
      document.getElementById("greeting").innerText = getGreeting();
    }

    setInterval(updateClock, 1000);
    updateClock();

    window.addEventListener("load", () => {
      const loader = document.getElementById("loader");
      loader.style.opacity = 0;
      setTimeout(() => loader.style.display = "none", 500);
    });

    function animateCounter(id, target, duration = 1500) {
      let el = document.getElementById(id);
      let start = 0;
      let increment = target / (duration / 20);
      let counter = setInterval(() => {
        start += increment;
        if (start >= target) {
          el.innerText = target;
          clearInterval(counter);
        } else {
          el.innerText = Math.floor(start);
        }
      }, 20);
    }

    animateCounter('countTA', <?php echo json_encode((int)$totalTA) ?>);
    animateCounter('countSeminar', <?php echo json_encode((int)$totalSem) ?>);
    animateCounter('countUjian', <?php echo json_encode((int)$totalUj) ?>);
  </script>
</body>
</html>
