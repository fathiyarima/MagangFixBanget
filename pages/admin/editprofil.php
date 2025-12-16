<?php
// editprofil.php (FINAL - sesuai dashboard)
session_start();
require '../../config/connection.php'; // koneksi PDO ($conn2)

if (!isset($_SESSION['username'])) {
    header('Location: ../../index.php');
    exit;
}

$username = $_SESSION['username'];

// Ambil data admin
$stmt = $conn2->prepare("SELECT username, nama_admin, nomor_telepon FROM admin WHERE username = :username");
$stmt->execute([':username' => $username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die('Data admin tidak ditemukan');
}

// Proses update profil
if (isset($_POST['update'])) {
    $nama_admin = trim($_POST['nama_admin']);
    $nomor_telepon = trim($_POST['nomor_telepon']);
    $password = $_POST['password'];

    if (!empty($password) && strlen($password) < 6) {
        echo "<script>alert('Password minimal 6 karakter');</script>";
    } else {
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn2->prepare("UPDATE admin SET nama_admin=:nama, nomor_telepon=:telp, password=:pass WHERE username=:username");
            $update->execute([
                ':nama' => $nama_admin,
                ':telp' => $nomor_telepon,
                ':pass' => $password_hash,
                ':username' => $username
            ]);
        } else {
            $update = $conn2->prepare("UPDATE admin SET nama_admin=:nama, nomor_telepon=:telp WHERE username=:username");
            $update->execute([
                ':nama' => $nama_admin,
                ':telp' => $nomor_telepon,
                ':username' => $username
            ]);
        }

        echo "<script>alert('Profil berhasil diperbarui'); window.location='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil Admin</title>

  <!-- Skydash CSS -->
  <link rel="stylesheet" href="../../Template/skydash/vendors/feather/feather.css">
  <link rel="stylesheet" href="../../Template/skydash/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../Template/skydash/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../Template/skydash/css/vertical-layout-light/style.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../assets/css/css/admin/dashboard.css">
  <link rel="shortcut icon" href="../../assets/img/Logo.webp" />
</head>
<body>
<div class="container-scroller">

<?php 
  $current_page = 'editprofil.php';
  include "sidebar2.php"; 
?>

<div class="main-panel">
  <div class="content-wrapper">

    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Profil Admin</h5>
          </div>
          <div class="card-body">
            <form method="POST">

              <div class="form-group mb-3">
                <label>Username</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($admin['username']); ?>" readonly>
              </div>

              <div class="form-group mb-3">
                <label>Nama Admin</label>
                <input type="text" name="nama_admin" class="form-control" value="<?= htmlspecialchars($admin['nama_admin']); ?>" required>
              </div>

              <div class="form-group mb-3">
                <label>Nomor Telepon</label>
                <input type="text" name="nomor_telepon" class="form-control" value="<?= htmlspecialchars($admin['nomor_telepon']); ?>" required>
              </div>

              <div class="form-group mb-4">
                <label>Password Baru <small class="text-muted">(opsional)</small></label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
              </div>

              <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

</div>

<!-- Skydash JS -->
<script src="../../Template/skydash/vendors/js/vendor.bundle.base.js"></script>
<script src="../../Template/skydash/js/off-canvas.js"></script>
<script src="../../Template/skydash/js/hoverable-collapse.js"></script>
<script src="../../Template/skydash/js/template.js"></script>
<script src="../../Template/skydash/js/settings.js"></script>

</body>
</html>