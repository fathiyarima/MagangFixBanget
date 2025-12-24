<?php
session_start();
include "../../config/connection.php";
$nama_admin = $_SESSION['username'];

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

<?php
$currentPage = basename($_SERVER['PHP_SELF']);

if (strpos($currentPage, 'pendaftaranTA.php') !== false) {
  $category = 'tugas_akhir';
} elseif (strpos($currentPage, 'pendaftaranSeminar.php') !== false) {
    $category = 'seminar'; // This is for the seminar page
} elseif (strpos($currentPage, 'pendaftaranUjian.php') !== false) {
    $category = 'ujian'; // This is for the ujian page
} else {
    $category = 'unknown'; // Default or unknown category, in case none match
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Pendaftaran Ujian</title>
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
  <link rel="stylesheet" type="text/css" href="../../assets/css/css/admin/dosen.css">
  <link rel="stylesheet" href="../../assets/css/css/admin/dosen.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=folder_open" />
  <!-- Add jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
     <?php
     include "sidebar.php";
     ?>
      <!-- partial -->
      <?php

          // Ambil total pendaftar tugas akhir
          $sqlUjian = "SELECT COUNT(*) AS total FROM ujian";
          $resultUjian = $conn->query($sqlUjian);
          $totalUjian = ($resultUjian->num_rows > 0) ? $resultUjian->fetch_assoc()['total'] : 0;
      ?>
      
      <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-md-5 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body text-center">
                      <p class="mb-4">Total Pendaftar Ujian</p>
                      <p class="fs-30 mb-2"><?php echo number_format($totalUjian); ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-center">
                
                <?php

                  if ($conn->connect_error) {
                      die("Connection failed: " . $conn->connect_error);
                  }

                  $sql = "SELECT status_ujian, COUNT(*) as count FROM ujian
                          WHERE status_ujian IN ('dijadwalkan', 'selesai') 	
                          GROUP BY status_ujian";
                  $result = $conn->query($sql);

                  $xValues = [];
                  $yValues = [];

                  if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          $xValues[] = $row['status_ujian']; 
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
          <!--Advanced-->
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

            /* Memastikan input tetap memiliki border */
            input[type="text"] {
                border: 1px solid #ccc; /* Border default */
                padding: 5px;
                border-radius: 5px;
                text-align: center;
                width: 60px; /* Sesuaikan lebar */
            }

            /* Saat input diklik (focus), border tetap terlihat */
            input[type="text"]:focus {
                border: 1px solid #007bff; /* Warna biru saat diklik */
                outline: none; /* Hilangkan outline bawaan browser */
                box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5); /* Efek glow saat aktif */
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
                color: white;
                border: none;
                cursor: pointer;
                font-weight: bold;
            }

            select option[value="dijadwalkan"] {
                background: yellow;
            }

            select option[value="ditunda"] {
                background: red;
                color: white;
            }

            select option[value="selesai"] {
                background: green;
                color: white;
            }

            /* Tombol Update */
            .btn-update {
                background-color: #007bff;
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
                border: none;
                cursor: pointer;
            }

            .btn-update:hover {
                background-color: #0056b3;
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

            .btn-add-nilai {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 8px 16px;
                border: none;
                border-radius: 8px;
                color: white;
                cursor: pointer;
                font-size: 0.85rem;
                font-weight: 600;
                transition: all 0.3s ease;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
        

          /* Style untuk teks "No File" */
          .no-file {
              color: red;
              font-weight: bold;
          }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        /* Tampilkan modal ketika memiliki class 'active' */
        .modal.active {
            display: flex;
        }

        /* Pastikan modal selalu presisi di tengah */
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 320px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.25);
            text-align: center;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* HEADER */ 
        .modal-content h3, 
        .modal-content h4 { 
            margin: 0; 
            padding: 18px;
            color: black; 
            font-size: 20px; 
            font-weight: 600; 
        }

        /* BODY */ .modal-content 
        .modal-body { 
            padding: 22px; 
        }

        /* LABEL */ 
        .modal-content label { 
            text-align: left; 
            font-size: 15px; 
            font-weight: 600; 
            margin-bottom: 6px; 
            color: #444; 
        }

        /* INPUT */ 
        .modal-content input { 
            width: 100%; 
            padding: 10px 14px; 
            margin-bottom: 14px; 
            border-radius: 10px; 
            border: 1px solid #ddd; 
            font-size: 14px; 
            transition: 0.3s; 
        }

        /* Fokus input */ 
        .modal-content input:focus { 
            border-color: #1b4f72; 
            box-shadow: 0 0 0 3px rgba(27, 79, 114, 0.2); 
            outline: none; 
        }

        /* RATA-RATA */ 
        .modal-content input[readonly] { 
            background: #ecf5ff; 
            font-weight: bold; 
            color: #1b4f72; 
            text-align: center; 
        }

        /* FOOTER */ 
        .modal-content 
        .modal-footer { 
            padding: 15px 22px; 
            background: #f8f9fa; 
            display: flex; 
            justify-content: flex-end; 
            gap: 10px; 
        }

        /* TOMBOL SIMPAN */ 
        .modal-content button { 
            padding: 8px 20px; 
            border-radius: 10px; 
            border: none; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; 
            font-size: 14px; 
            cursor: pointer; 
            transition: all 0.25s ease; } 
        .modal-content button:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 14px rgba(0,0,0,0.25); 
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-modal {
            position: absolute; t
            op: 12px; right: 16px; 
            font-size: 22px; 
            cursor: pointer; 
            color: #fff; 
            transition: color 0.2s; 
        }
        

        .close-modal:hover {
            color: #333;
            background-color: #f5f5f5;
        }

        /* Form styling untuk modal */
        .modal-title {
            color: #2c3e50;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #444;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: border 0.3s;
        }

        .form-group input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }

        .average-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #eaeaea;
        }

        .average-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .average-value {
            font-size: 26px;
            font-weight: 700;
            color: #3498db;
        }

        .btn-save {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .btn-save:hover {
            background-color: #2980b9;
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

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4>Pendaftar Ujian Politeknik Nest Sukoharjo</h4>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>NIM</th>
                                        <th>Status</th>
                                        <th>Jadwal</th>
                                        <th>Verifikasi</th>
                                        <th>Doc</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                  if ($conn->connect_error) {
                                      die("Koneksi gagal: " . $conn->connect_error);
                                  }

                                  $event = "ujian";
                                  $sql = "SELECT mahasiswa.id_mahasiswa, mahasiswa.nama_mahasiswa, mahasiswa.nim, 
                                          ujian.status_ujian, ujian.nilai, ujian.tanggal_ujian
                                          FROM mahasiswa
                                          INNER JOIN ujian ON mahasiswa.id_mahasiswa = ujian.id_mahasiswa";
                                  $result = $conn->query($sql);

                                  while ($row = $result->fetch_assoc()) {
                                      echo "<tr>";
                                      echo "<td>{$row['id_mahasiswa']}</td>";
                                      echo "<td>{$row['nama_mahasiswa']}</td>";
                                      echo "<td>{$row['nim']}</td>";
                                      echo "<td>
                                              <form action='update_ujian.php' method='POST'>
                                                  <input type='hidden' name='id_mahasiswa' value='{$row['id_mahasiswa']}'>
                                                  <select name='status_ujian'>
                                                      <option value='dijadwalkan' " . ($row['status_ujian'] == 'dijadwalkan' ? 'selected' : '') . ">Dijadwalkan</option>
                                                      <option value='ditunda' " . ($row['status_ujian'] == 'ditunda' ? 'selected' : '') . ">Ditunda</option>
                                                      <option value='selesai' " . ($row['status_ujian'] == 'selesai' ? 'selected' : '') . ">Selesai</option>
                                                  </select>
                                              </td>
                                              <td><input type='date' name='tanggal_ujian' value='" . $row['tanggal_ujian'] . "'></td>
                                              <td><button class='btn-update' type='submit'>Verifikasi</button></form></td>
                                              <td><button class='folder-btn' data-event='{$event}' data-userid='{$row['id_mahasiswa']}'>
                                                      <span class='material-symbols-outlined'>folder_open</span>
                                                  </button></td>
                                                   </button></td>
                                                  <td>
                                                    <button class='btn-add-nilai' data-id='{$row['id_mahasiswa']}'>Add Nilai</button>
                                                    <p class='nilai-display' id='nilai-display-{$row['id_mahasiswa']}'>" . (!empty($row['nilai']) ? $row['nilai'] : '-') . "</p>
                                                </td>
                                                ";
                                      echo "</tr>";
                                  }

                                  $conn->close();
                                  ?>
                                  </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

      <div id="nilaiModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Input Nilai</h3>
            <form id="nilaiForm">
                <input type="hidden" id="id_mahasiswa" name="id_mahasiswa">
                <label>Nilai Dosen Pembimbing 1:</label>
                <input type="number" id="nilai1" name="nilai1" min="0" max="100">
                
                <label>Nilai Dosen Pembimbing 2:</label>
                <input type="number" id="nilai2" name="nilai2" min="0" max="100">

                <label>Rata-rata:</label>
                <input type="text" id="rataRata" name="rata_rata" readonly>

                <button type="submit">Simpan</button>
            </form>
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

    // Change select background color
    function changeSelectColor(selectElement) {
        var selectedValue = selectElement.value;

        if (selectedValue === "dijadwalkan") {
            selectElement.style.backgroundColor = "rgb(255, 251, 0)";
        } else if (selectedValue === "ditunda") {
            selectElement.style.backgroundColor = "rgb(255, 99, 71)";
        } else if (selectedValue === "selesai") {
            selectElement.style.backgroundColor = "rgb(34, 139, 34)";
        }
    }

    document.querySelectorAll("select[name='status_ujian']").forEach(function (select) {
        changeSelectColor(select);
    });

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

document.querySelectorAll(".btn-add-nilai").forEach(button => {
    button.addEventListener("click", function() {
        let idMahasiswa = this.getAttribute("data-id");
        document.getElementById("id_mahasiswa").value = idMahasiswa;
        document.getElementById("nilaiModal").style.display = "block";
    });
});

document.querySelector(".close-modal").addEventListener("click", function() {
    document.getElementById("nilaiModal").style.display = "none";
});

document.getElementById("nilaiForm").addEventListener("input", function() {
    let nilai1 = parseFloat(document.getElementById("nilai1").value) || 0;
    let nilai2 = parseFloat(document.getElementById("nilai2").value) || 0;
    let rataRata = (nilai1 + nilai2) / 2;
    document.getElementById("rataRata").value = rataRata.toFixed(2);
});

document.getElementById("nilaiForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let idMahasiswa = document.getElementById("id_mahasiswa").value;
    
    fetch("simpan_nilai.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        let rataRata = document.getElementById("rataRata").value;
        
         // Menampilkan notifikasi SweetAlert
         Swal.fire({
            title: "Sukses!",
            text: "Nilai telah berhasil disimpan.",
            icon: "success",
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            // Update tampilan nilai di tabel tanpa reload halaman
            document.getElementById("nilai-display-" + idMahasiswa).innerText = rataRata;
            document.getElementById("nilaiModal").style.display = "none";
        });
    })
    .catch(error => {
        Swal.fire({
            title: "Gagal!",
            text: "Terjadi kesalahan saat menyimpan nilai.",
            icon: "error"
        });
    });
});

</script>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php include 'footer.php';?>
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
  <script src="../../Template/skydash/js/settings.js"></script>
  <script src="../../Template/skydash/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../../Template/skydash/js/dashboard.js"></script>
  <script src="../../Template/skydash/js/Chart.roundedBarCharts.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

