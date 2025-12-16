<?php
session_start();
$nama_admin = $_SESSION['username'];
include "../../config/connection.php";
$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$check = "SELECT nomor_telepon, nama_admin FROM admin WHERE username = :nama";
$checkNomer_telepon = $conn2->prepare($check);
$checkNomer_telepon->execute([':nama' => $nama_admin]);
$row = $checkNomer_telepon->fetch(PDO::FETCH_ASSOC);

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
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Pendaftaran Tugas Akhir</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- plugins:css -->
  <link rel="stylesheet" href="../../Template/skydash/vendors/feather/feather.css">
  <link rel="stylesheet" href="../../Template/skydash/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../Template/skydash/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../../Template/skydash/vendors/ti-icons/css/themify-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../Template/skydash/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../assets/img/Logo.webp" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
  <link rel="stylesheet" type="text/css" href="../../assets/css/css/admin/mahasiswa.css">
  <link rel="stylesheet" href="../../assets/css/css/admin/mahasiswa.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=folder_open" />
  <style>
   /* Enhanced Admin Dashboard Styles */

#nonclick {
    pointer-events: none;
}

/* Main Layout Improvements */
.content-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 20px;
}

/* Stats Cards Enhancement */
.card-light-danger {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-light-danger:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.card-light-danger .card-body {
    color: white;
}

.card-light-danger p {
    color: rgba(255,255,255,0.9);
}

.fs-30 {
    font-size: 2.5rem !important;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Chart Container */
#myChart2 {
    max-width: 300px;
    max-height: 300px;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
}

/* Table Improvements */
.table-responsive {
    overflow-x: auto;
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    background: white;
}

table {
    border-collapse: collapse;
    width: 100%;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    margin: 0;
}

th, td {
    padding: 15px 12px;
    text-align: center;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

th {
    background: linear-gradient(135deg, #4B49AC 0%, #6c5ce7 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    position: sticky;
    top: 0;
    z-index: 10;
}

tbody tr {
    transition: all 0.3s ease;
}

tbody tr:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #e3e7ff 100%);
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(75, 73, 172, 0.1);
}

/* Card Enhancement */
.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 50px rgba(0,0,0,0.12);
}

.card-body {
    padding: 30px;
}

.card-title {
    text-align: center;
    color: #2d3436;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 30px;
    position: relative;
}

.card-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, #4B49AC 0%, #6c5ce7 100%);
    border-radius: 2px;
}

/* Form Elements Enhancement */
input[type="date"] {
    border: 2px solid #e0e6ed;
    padding: 8px 12px;
    border-radius: 10px;
    text-align: center;
    width: 160px;
    background: white;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

input[type="date"]:focus {
    border-color: #4B49AC;
    outline: none;
    box-shadow: 0 0 0 3px rgba(75, 73, 172, 0.1);
}

/* Dropdown Enhancements */
select {
    padding: 8px 12px;
    border-radius: 10px;
    border: 2px solid #e0e6ed;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    background: white;
    font-size: 0.9rem;
}

select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(75, 73, 172, 0.1);
}

select option[value="Revisi"] {
    background: #ffeaa7;
    color: #2d3436;
}

select option[value="Ditolak"] {
    background: #fd79a8;
    color: white;
}

select option[value="Disetujui"] {
    background: #00b894;
    color: white;
}

/* Status-based styling */
select[name="status_pengajuan"] {
    font-weight: bold;
    min-width: 120px;
}

/* Dosen Pembimbing Dropdown */
select[name="dosen_pembimbing"] {
    background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%) !important;
    color: white;
    border: none;
    font-weight: 600;
    min-width: 150px;
}

select[name="dosen_pembimbing"]:hover {
    background: linear-gradient(135deg, #0984e3 0%, #74b9ff 100%) !important;
}

select[name="dosen_pembimbing"] option {
    background: #ddd6fe;
    color: #2d3436;
}

/* Button Enhancements */
.btn-update, .btn-inverse-success {
    background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

.btn-update:hover, .btn-inverse-success:hover {
    background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 92, 231, 0.3);
}

/* Icon Styling */
.icon-folder, .material-symbols-outlined {
    font-size: 24px;
    color: #4B49AC;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    padding: 8px;
    border-radius: 8px;
}

.icon-folder:hover, .material-symbols-outlined:hover {
    color: #6c5ce7;
    background: rgba(75, 73, 172, 0.1);
    transform: scale(1.1);
}

.folder-btn {
    background: none;
    border: none;
    cursor: pointer;
}

/* Popup Enhancements */
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    z-index: 1000;
}

.popup-content {
    background: white;
    padding: 30px;
    border-radius: 20px;
    width: 60%;
    max-width: 800px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideInUp 0.4s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translate(-50%, -40%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

.close-btn {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
    color: #636e72;
    transition: all 0.3s ease;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.close-btn:hover {
    color: #d63031;
    background: rgba(214, 48, 49, 0.1);
}

/* Popup Table */
.popup-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.popup-table th, .popup-table td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #f0f0f0;
}

.popup-table th {
    background: linear-gradient(135deg, #2d3436 0%, #636e72 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

.popup-table tbody tr:hover {
    background: #f8f9ff;
}

/* Verify Button */
.verify-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
    color: white;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.verify-btn:hover {
    background: linear-gradient(135deg, #00cec9 0%, #00b894 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 184, 148, 0.3);
}

.verify-btn:disabled {
    background: #ddd;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* No File Text */
.no-file {
    color: #d63031;
    font-weight: 600;
    font-style: italic;
}

/* Revision Textbox */
.revisi-textbox {
    margin-top: 10px;
}

.revisi-textbox textarea {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid #e0e6ed;
    border-radius: 8px;
    resize: vertical;
    font-family: inherit;
    font-size: 0.9rem;
    transition: border-color 0.3s ease;
}

.revisi-textbox textarea:focus {
    border-color: #4B49AC;
    outline: none;
    box-shadow: 0 0 0 3px rgba(75, 73, 172, 0.1);
}

.small-text {
    font-size: 0.8rem;
    color: #636e72;
    font-style: italic;
    margin-top: 5px;
    line-height: 1.4;
}

/* Footer Enhancement */
.footer {
    background: white;
    border-top: 1px solid #f0f0f0;
    padding: 20px 0;
    margin-top: 40px;
}

.footer a {
    color: #4B49AC;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer a:hover {
    color: #6c5ce7;
}

/* Responsive Design */
@media (max-width: 768px) {
    .popup-content {
        width: 90%;
        padding: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    th, td {
        padding: 10px 8px;
        font-size: 0.85rem;
    }
    
    .fs-30 {
        font-size: 2rem !important;
    }
}

@media (max-width: 480px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    th, td {
        padding: 8px 6px;
    }
    
    select, input[type="date"] {
        font-size: 0.8rem;
        padding: 6px 8px;
    }
    
    .btn-update, .btn-inverse-success {
        padding: 6px 12px;
        font-size: 0.75rem;
    }
}

/* Animation for table rows */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

tbody tr {
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
}

tbody tr:nth-child(1) { animation-delay: 0.1s; }
tbody tr:nth-child(2) { animation-delay: 0.2s; }
tbody tr:nth-child(3) { animation-delay: 0.3s; }
tbody tr:nth-child(4) { animation-delay: 0.4s; }
tbody tr:nth-child(5) { animation-delay: 0.5s; }

/* Loading state for buttons */
.btn-update:disabled, .btn-inverse-success:disabled {
    background: #ddd;
    cursor: not-allowed;
    transform: none;
}

/* Enhanced focus states for accessibility */
button:focus, select:focus, input:focus, textarea:focus {
    outline: 2px solid #4B49AC;
    outline-offset: 2px;
}

/* Smooth transitions for all interactive elements */
* {
    transition: all 0.3s ease;
}
  </style>
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php include "sidebar.php";?>
      <?php
          if ($conn->connect_error) {
              die("Koneksi gagal: " . $conn->connect_error);
          }

          // Ambil total pendaftar tugas akhir
          $sqlTA = "SELECT COUNT(*) AS total FROM tugas_akhir";
          $resultTA = $conn->query($sqlTA);
          $totalTA = ($resultTA->num_rows > 0) ? $resultTA->fetch_assoc()['total'] : 0;
      ?>
      
      <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-md-5 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body text-center">
                      <p class="mb-4">Total Pendaftar Tugas Akhir</p>
                      <p class="fs-30 mb-2"><?php echo number_format($totalTA); ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-center">
                <?php

                  if ($conn->connect_error) {
                      die("Connection failed: " . $conn->connect_error);
                  }

                  $sql = "SELECT status_pengajuan, COUNT(*) as count FROM tugas_akhir
                          WHERE status_pengajuan IN ('Disetujui', 'Revisi', 'Ditolak') 	
                          GROUP BY status_pengajuan";
                  $result = $conn->query($sql);

                  $xValues = [];
                  $yValues = [];

                  if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          $xValues[] = $row['status_pengajuan']; 
                          $yValues[] = $row['count'];
                      }
                  }
                  ?>
                  <canvas id="myChart2"></canvas>
                  <script>
                    var xValues = <?php echo json_encode($xValues); ?>; 
                    var yValues = <?php echo json_encode($yValues); ?>;

                    var barColors = ["#73ad91", "#ebd382", "#d25d5d",];

                    new Chart("myChart2", {
                        type: "doughnut",
                        data: {
                            labels: xValues,
                            datasets: [{
                                backgroundColor: barColors,
                                data: yValues
                            }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: "Jumlah Pendaftar"
                            }
                        }
                    });
                </script>
            </div>
            </div>
          </div>
          <?php
          if ($conn->connect_error) {
              die("Koneksi gagal: " . $conn->connect_error);
          }

          // Fetch all dosen_pembimbing from the dosen_pembimbing table
          $sql_dosen = "SELECT id_dosen, nama_dosen FROM dosen_pembimbing";
          $result_dosen = $conn->query($sql_dosen);

          // Fetch mahasiswa and their related data from the database
          $sql1 = "SELECT mahasiswa.id_mahasiswa, mahasiswa.nama_mahasiswa, mahasiswa.nim, mahasiswa.prodi, 
                          tugas_akhir.tema, tugas_akhir.judul, tugas_akhir.status_pengajuan, tugas_akhir.alasan_revisi, 
                          mahasiswa_dosen.id_dosen
                  FROM mahasiswa 
                  LEFT JOIN tugas_akhir ON mahasiswa.id_mahasiswa = tugas_akhir.id_mahasiswa
                  LEFT JOIN mahasiswa_dosen ON mahasiswa.id_mahasiswa = mahasiswa_dosen.id_mahasiswa";
          $result = $conn->query($sql1);
          ?>

          <style>
          /* Membuat tabel lebih rapi dan responsif */
          .table-responsive {
              overflow-x: auto;
              width: 100%;
          }

          table {
              border-collapse: collapse;
              width: 100%;
              background: #fff;
              border-radius: 8px;
              overflow: hidden;
          }

          th, td {
              padding: 12px;
              text-align: center;
              border-bottom: 1px solid #ddd;
          }

          th {
              background-color: #4B49AC;
              color: white;
          }

          h4 {
            text-align: center;
          }

          /* Input tanggal */
          input[type="date"] {
              border: 1px solid #ccc;
              padding: 5px;
              border-radius: 5px;
              text-align: center;
              width: 150px;
          }

          /* Dropdown Status */
          select {
                padding: 5px;
                border-radius: 5px;
                border: none;
                cursor: pointer;
                font-weight: bold;
            }
            select option[value="Revisi"] {
                background: yellow;
            }
            select option[value="Ditolak"] {
                background: red;
                color: white;
            }
            select option[value="Disetujui"] {
                background: green;
                color: white;
            }

          /* Styling untuk dropdown Dosen Pembimbing */
          select[name="dosen_pembimbing"] {
              background-color: #007bff !important; /* Biru */
              color: white;
              padding: 5px;
              border-radius: 5px;
              border: none;
              font-weight: bold;
              cursor: pointer;
          }

          /* Saat opsi dalam dropdown dipilih */
          select[name="dosen_pembimbing"] option {
              background-color:rgb(162, 199, 241); /* Biru */
              color: black;
          }


          /* Tombol Update */
          .btn-update {
              background-color:rgb(178, 212, 249);
              color: white;
              padding: 5px 10px;
              border-radius: 5px;
              border: none;
              cursor: pointer;
          }

          .btn-update:hover {
              background-color: #0056b3;
          }

          /* Ikon dokumen */
          .icon-folder {
              font-size: 20px;
              color: #007bff;
              cursor: pointer;
              text-decoration: none;
          }
          /* Style untuk pop-up */
          .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
          }

          .popup-content {
              background: white;
              padding: 20px;
              border-radius: 10px;
              width: 50%;
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
              animation: fadeIn 0.3s ease-in-out;
          }

          /* Animasi */
          @keyframes fadeIn {
              from {
                  opacity: 0;
                  transform: translate(-50%, -60%);
              }
              to {
                  opacity: 1;
                  transform: translate(-50%, -50%);
              }
          }

          .close-btn {
              position: absolute;
              top: 10px;
              right: 15px;
              font-size: 20px;
              cursor: pointer;
              color: #555;
              transition: color 0.2s;
          }

          .close-btn:hover {
              color: red;
          }

          /* Style untuk tabel */
          .popup-table {
              width: 100%;
              border-collapse: collapse;
              margin-top: 10px;
          }

          .popup-table th, .popup-table td {
              padding: 10px;
              text-align: center;
              border-bottom: 1px solid #ddd;
          }

          .popup-table th {
              background-color: #1b4f72;
              color: white;
              font-weight: bold;
          }

          /* Style untuk tombol Verify */
          .verify-btn {
              padding: 6px 12px;
              border: none;
              border-radius: 5px;
              background-color: #007bff;
              color: white;
              cursor: pointer;
              font-size: 14px;
              transition: background 0.2s ease-in-out;
          }

          .verify-btn:hover {
              background-color: #0056b3;
          }

          /* Style untuk teks "No File" */
          .no-file {
              color: red;
              font-weight: bold;
          }     
          
          .card-title{
          text-align: center;
        }
          </style>
          
<div class="row">
    
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <p class="card-title">Pendaftar Tugas Akhir Politeknik Nest Sukoharjo</p>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Prodi</th>
                                <th>Tema</th>
                                <th>Judul</th>
                                <th>Dosen Pembimbing</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th>Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $event = "tugas_akhir";
                            while ($row = $result->fetch_assoc()) {
                                $status_pengajuan = isset($row['status_pengajuan']) ? $row['status_pengajuan'] : '';
                                $selected_dosen = isset($row['id_dosen']) ? $row['id_dosen'] : '';
                                $has_dosen = !empty($selected_dosen) ? 'yes' : 'no';

                                echo "<tr>";
                                echo "<td>" . $row['id_mahasiswa'] . "</td>";
                                echo "<td>" . $row['nama_mahasiswa'] . "</td>";
                                echo "<td>" . $row['nim'] . "</td>";
                                echo "<td>" . $row['prodi'] . "</td>";
                                echo "<td>" . $row['tema'] . "</td>";
                                echo "<td>" . $row['judul'] . "</td>";
                                echo "<td>";

                                echo "<form action='update_pengajuan.php' method='POST'>";

                                echo "<select name='dosen_pembimbing' class='js-example-basic-single w-30' required>";
                                echo "<option value=''>Select Dosen Pembimbing</option>";

                                $result_dosen->data_seek(0);

                                while ($dosen_row = $result_dosen->fetch_assoc()) {
                                    echo "<option value='" . $dosen_row['id_dosen'] . "' " . ($dosen_row['id_dosen'] == $selected_dosen ? 'selected' : '') . ">" . $dosen_row['nama_dosen'] . "</option>";
                                }

                                echo "</select>";
                                echo "</td>";

                                echo "<td>";
                                echo "<select name='status_pengajuan' onchange='toggleRevisiTextbox(this)' required>
                                        <option value='Ditolak' " . ($status_pengajuan == 'Ditolak' ? 'selected' : '') . ">Ditolak</option>
                                        <option value='Revisi' " . ($status_pengajuan == 'Revisi' ? 'selected' : '') . ">Revisi</option>
                                        <option value='Disetujui' " . ($status_pengajuan == 'Disetujui' ? 'selected' : '') . ">Disetujui</option>
                                      </select>

                                      <div class='revisi-textbox' id='revisi-textbox-" . $row['id_mahasiswa'] . "' data-id='" . $row['id_mahasiswa'] . "' style='display: none;'>
                                        <textarea name='alasan_revisi' rows='3' disabled data-was-revisi='" . ($status_pengajuan == 'Revisi' ? 'true' : 'false') . "'>" . 
                                          (isset($row['alasan_revisi']) ? htmlspecialchars($row['alasan_revisi']) : '') . 
                                        "</textarea>
                                      </div>";

                                echo "<p class='small-text' id='revisi-text-" . $row['id_mahasiswa'] . "' style='display:none;'>" . $row['alasan_revisi'] . "</p>";

                                echo "</td>";


                                echo "<input type='hidden' name='id_mahasiswa' value='" . $row['id_mahasiswa'] . "'>";
                                echo "<input type='hidden' name='has_dosen' value='" . $has_dosen . "'>";
                                echo "<td>";
                                echo "<button class='btn btn-inverse-success btn-fw' type='submit'>Update</button>";
                                echo "</td>";
                                echo "</form>";
                                echo "<td><button class='folder-btn' data-event='" . $event . "' data-userid='" . $row['id_mahasiswa'] . "'><span class='material-symbols-outlined'>folder_open</span></button></td>";
                                echo "</tr>";
                            }
                            $result_dosen->close();
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Pop Up Dokumen-->
<div id="popup" class="popup">
          <div class="popup-content">
              <span class="close-btn">&times;</span>
              <h3>Dokumen</h3>
              <div class="table-responsive">
                  <table class="popup-table">
                      <thead>
                          <tr>
                              <th>Keterangan</th>
                              <th>Dokumen</th>
                              <th>Aksi</th>
                          </tr>
                      </thead>
                      <tbody id="popup-content">

                      </tbody>
                  </table>
              </div>
          </div>
      </div>



      
<script>
  // Open Modal
  let openBtn = document.getElementById("open");
    if (openBtn) {
        openBtn.onclick = function () {
            document.getElementById("myModal").style.display = "flex";
        };
    }

    // Close Modal
    let closeBtn = document.querySelector(".close");
    if (closeBtn) {
        closeBtn.onclick = function () {
            document.getElementById("myModal").style.display = "none";
        };
    }

          $(document).on("click", ".folder-btn", function () {
          let event = $(this).data("event");
          let userId = $(this).data("userid");

          console.log("Clicked button for event:", event, "User ID:", userId);

          $.ajax({
              url: "fetch_pdfs.php",
              type: "POST",
              data: { event: event, userId: userId },
              success: function (response) {
                  $("#popup-content").html(response);
                  $("#popup").show();
              },
              error: function (xhr, status, error) {
                  console.error("AJAX Error:", error);
              }
          });
      });

      $(document).on("click", ".close-btn", function () {
          $("#popup").hide();
      });
          
      // Pop Up Verifikasi
      $(document).off("click", ".verify-btn").on("click", ".verify-btn", function (e) {
    e.preventDefault(); 

    let userId = $(this).data("userid");
    let event = $(this).data("event");
    let column = $(this).data("column");
    let button = $(this);

    Swal.fire({
        title: "Konfirmasi Verifikasi",
        text: "Apakah Anda yakin ingin memverifikasi dokumen ini?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Verifikasi!"
    }).then((result) => {
        if (result.isConfirmed) {
            button.prop("disabled", true).text("Verifying...");

            $.post("verify.php", { userId: userId, event: event, column: column }, function (response) {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Dokumen telah diverifikasi.",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            }).fail(function (xhr) {
                Swal.fire({
                    title: "Gagal!",
                    text: "Terjadi kesalahan dalam verifikasi.",
                    icon: "error"
                });
                button.prop("disabled", false).text("Verify");
            });
        }
    });
});


            
function changeColor(selectElement) {
    var selectedValue = selectElement.value;

    if (selectedValue === 'Revisi') {
        selectElement.style.backgroundColor = 'yellow';
    } else if (selectedValue === 'Ditolak') {
        selectElement.style.backgroundColor = 'red';
    } else if (selectedValue === 'Disetujui') {
        selectElement.style.backgroundColor = 'green';
    } else {
        selectElement.style.backgroundColor = 'rgb(174, 215, 242)';
        selectElement.style.color = 'black';
    }
}

function toggleRevisiTextbox(selectElement) {
    var mahasiswaId = selectElement.closest('tr').querySelector('input[name="id_mahasiswa"]').value;
    var revisiTextbox = document.getElementById('revisi-textbox-' + mahasiswaId);
    var revisiText = document.getElementById('revisi-text-' + mahasiswaId);
    var textarea = revisiTextbox ? revisiTextbox.querySelector('textarea') : null;

    if (!revisiTextbox || !textarea || !revisiText) return;

    if (selectElement.value === 'Revisi') {
        revisiTextbox.style.display = 'block';
        textarea.disabled = false;
    } else {
        revisiTextbox.style.display = 'none';
        textarea.disabled = true;
        revisiText.innerText = ''; // Menghapus teks jika bukan revisi
    }
}

// Saat tombol update ditekan, komentar berubah menjadi teks kecil tanpa border
document.querySelectorAll('.btn-inverse-success').forEach(button => {
    button.addEventListener('click', function (event) {
        var form = this.closest('form');
        var revisiTextbox = form.querySelector('.revisi-textbox');
        var textarea = revisiTextbox ? revisiTextbox.querySelector('textarea') : null;
        var revisiText = form.closest('tr').querySelector('.small-text');

        if (textarea && textarea.value.trim() !== '') {
            revisiText.innerText = textarea.value;
            revisiText.style.display = 'block';
            revisiTextbox.style.display = 'none';
        }
    });
});

window.onload = function () {
    document.querySelectorAll('select[name="status_pengajuan"]').forEach(function (select) {
        changeColor(select);
        toggleRevisiTextbox(select);
    });

    document.querySelectorAll('.revisi-textbox').forEach(function (box) {
        var mahasiswaId = box.getAttribute('data-id');
        var revisiText = document.getElementById('revisi-text-' + mahasiswaId);

        if (revisiText && revisiText.innerText.trim() !== '') {
            revisiText.style.display = 'block';
            box.style.display = 'none';  
        }
    });
};

</script>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php include "footer.php" ;?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="../../Template/skydash/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../../Template/skydash/vendors/chart.js/Chart.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../../Template/skydash/js/off-canvas.js"></script>
  <script src="../../Template/skydash/js/hoverable-collapse.js"></script>
  <script src="../../Template/skydash/js/../../Template.js"></script>
  <script src="../../Template/skydash/js/settings.js"></script>s
  <script src="../../Template/skydash/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../../Template/skydash/js/dashboard.js"></script>
  <script src="../../Template/skydash/js/Chart.roundedBarCharts.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

