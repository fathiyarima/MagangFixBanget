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
  $nama_admin = $row['nama_admin'];
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
  <title>Daftar Mahasiswa</title>
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

  <link rel="stylesheet" type="text/css" href="../../assets/css/css/admin/mahasiswa.css">
  <link rel="stylesheet" href="../../assets/css/css/admin/mahasiswa.css">
  <link rel="stylesheet" href="../../assets/css/admin/daftardosen.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php include "sidebar.php";?>
    <!-- partial -->
      <!-- partial -->


      <!--Advanced-->
      <div class="main-panel">
        <div class="content-wrapper">
          <!--Advanced-->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Daftar Mahasiswa</p>
                  <div class="row">
                    <div class="col-12">
                      <div class="table-responsive">
                        <?php

                        if ($conn->connect_error) {
                          die("Koneksi gagal: " . $conn->connect_error);
                        }

                        $limit = 10;
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        $sql1 = "SELECT id_mahasiswa, nama_mahasiswa, nim, prodi, kelas, nomor_telepon, username, pass FROM mahasiswa LIMIT $limit OFFSET $offset";
                        $result = $conn->query($sql1);

                        $totalQuery = "SELECT COUNT(id_mahasiswa) AS total FROM mahasiswa";
                        $totalResult = $conn->query($totalQuery);
                        $totalRow = $totalResult->fetch_assoc();
                        $totalData = $totalRow['total'];
                        $totalPages = ceil($totalData / $limit);
                        ?>
                        <div style="overflow-x: auto;">
                          <table id="example" class="display expandable-table" style="width:100%; white-space: nowrap;">
                            <thead>
                              <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Program Studi</th>
                                <th>Kelas</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                                <th>Preview</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id_mahasiswa'] . "</td>";
                                echo "<td>" . $row['nama_mahasiswa'] . "</td>";
                                echo "<td>" . $row['nim'] . "</td>";
                                echo "<td>" . $row['prodi'] . "</td>";
                                echo "<td>" . $row['kelas'] . "</td>";
                                echo "<td><button class='editBtn' data-id='" . $row['id_mahasiswa'] . "'>Edit</button></td>";
                                echo "<td><button class='deleteBtn' data-id='" . $row['id_mahasiswa'] . "'>Hapus</button></td>";
                                echo "<td>
                                  <button class='previewMhsBtn btn btn-info'
                                    data-nama='{$row['nama_mahasiswa']}'
                                    data-nim='{$row['nim']}'
                                    data-prodi='{$row['prodi']}'
                                    data-kelas='{$row['kelas']}'
                                    data-telp='{$row['nomor_telepon']}'
                                    data-username='{$row['username']}'>
                                    Preview
                                  </button>
                                </td>";


                                echo "</tr>";
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                        
                        <hr>

                        <div class="pagination-container">
                          <div class="pagination-info">PAGES <?php echo $page; ?> OF <?php echo $totalPages; ?></div>
                          <div class="pagination">
                            <?php if ($page > 1): ?>
                              <a href="?page=1" class="btn">FIRST</a>
                              <a href="?page=<?php echo $page - 1; ?>" class="btn">PREV</a>
                            <?php endif; ?>

                            <?php
                            if ($totalPages <= 10) {
                              for ($i = 1; $i <= $totalPages; $i++) {
                                echo "<a href='?page=$i' class='btn " . ($i == $page ? "active" : "") . "'>$i</a>";
                              }
                            } else {
                              if ($page > 3) echo "<a href='?page=1' class='btn'>1</a> ... ";

                              for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) {
                                echo "<a href='?page=$i' class='btn " . ($i == $page ? "active" : "") . "'>$i</a>";
                              }

                              if ($page < $totalPages - 2) echo " ... <a href='?page=$totalPages' class='btn'>$totalPages</a>";
                            }
                            ?>

                            <?php if ($page < $totalPages): ?>
                              <a href="?page=<?php echo $page + 1; ?>" class="btn">NEXT</a>
                              <a href="?page=<?php echo $totalPages; ?>" class="btn">LAST</a>
                            <?php endif; ?>
                          </div>
                        </div>
                        <?php
                        $conn->close();
                        ?>


                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end gap-2 mb-4">
              <button id="openModalBtn" class="btn btn-primary">➕ Add Data</button>
              <button id="openModal" class="btn btn-outline-primary">📥 Add Batch</button>
            </div>

            <div id="myModal" class="modal">
              <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Add Student Data</h2>
                <form id="studentForm">
                  <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="nama_mahasiswa" required>
                  </div>

                  <div class="form-group">
                    <label for="nim">NIM:</label>
                    <input type="text" id="nim" name="nim" required>
                  </div>

                  <div class="form-group">
                    <label for="program">Program Studi:</label>
                    <select id="program" name="prodi" required>
                      <option value="Teknologi Informasi">Teknologi Informasi</option>
                      <option value="Seni Kuliner">Seni Kuliner</option>
                      <option value="Perhotelan">Perhotelan</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="class">Class:</label>
                    <input type="text" id="class" name="kelas" required>
                  </div>

                  <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="nomor_telepon" required>
                  </div>

                  <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                  </div>

                  <div class="form-group">
                    <label for="pass">Password:</label>
                    <input type="password" id="pass" name="pass" required>
                  </div>

                  <button type="submit" id="submitBtn" class="btn-submit">Submit</button>
                </form>
              </div>
            </div>

            <div id="ModalBatch" class="modal">
              <div class="modal-content">
                <span class="close" id="closeModalBatch">&times;</span>
                <form action="upload_aksi.php" method="post" enctype="multipart/form-data">
                  <label for="file">Choose an Excel file to upload:</label>
                  <input type="file" name="excel_file" id="excel_file" required>
                  <button type="submit" name="submit">Upload</button>
                </form>
              </div>
            </div>

            <div id="editModal" class="modal">
              <div class="modal-content">
                <span class="close" id="closeEditModal">&times;</span>
                <h2>Edit Data Mahasiswa</h2>
                <form id="editForm">
                  <input type="hidden" id="edit_id" name="id_mahasiswa">

                  <div class="form-group">
                    <label for="edit_name">Nama:</label>
                    <input type="text" id="edit_name" name="nama_mahasiswa" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_nim">NIM:</label>
                    <input type="text" id="edit_nim" name="nim" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_prodi">Program Studi:</label>
                    <select id="edit_prodi" name="prodi" required>
                      <option value="Teknologi Informasi">Teknologi Informasi</option>
                      <option value="Seni Kuliner">Seni Kuliner</option>
                      <option value="Perhotelan">Perhotelan</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="edit_kelas">Kelas:</label>
                    <input type="text" id="edit_kelas" name="kelas" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_telepon">Nomor Telepon:</label>
                    <input type="text" id="edit_telepon" name="nomor_telepon" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_username">Username:</label>
                    <input type="text" id="edit_username" name="username" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_username">Password:</label>
                    <input type="password" id="edit_password" name="password" required>
                  </div>

                  <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </form>
              </div>
        </div>
    </div>
</div>
            </div>

            <div id="previewMhsModal" class="modal">
                <div class="modal-content" style="max-width:420px">
                  <span class="close" id="closePreviewMhs">&times;</span>

                  <div class="profile-card">

                    <h3 id="m_nama"></h3>
                    <p><strong>NIM:</strong> <span id="m_nim"></span></p>
                    <p><strong>Kelas:</strong> <span id="m_kelas"></span></p>
                    <p><strong>Program Studi:</strong> <span id="m_prodi"></span></p>
                    <p><strong>Nomor Telepon:</strong> <span id="m_telp"></span></p>
                    <p><strong>Username:</strong> <span id="m_username"></span></p>
                    <p>
                      <strong>Password:</strong>
                      <span id="m_password">••••••••</span>
                    </p>
                  </div>
                </div>
              </div>

            <script>
              document.getElementById("openModalBtn").onclick = function() {
                document.getElementById("myModal").style.display = "flex";
              }

              document.querySelector(".close").onclick = function() {
                document.getElementById("myModal").style.display = "none";
              }

              document.getElementById("openModal").onclick = function() {
                document.getElementById("ModalBatch").style.display = "flex";
              }

              document.getElementById("closeModalBatch").onclick = function() {
                document.getElementById("ModalBatch").style.display = "none";
              }


              window.onclick = function(event) {
                if (event.target == document.getElementById("myModal")) {
                  document.getElementById("myModal").style.display = "none";
                }
              }

              document.getElementById("openModalBtn").onclick = function() {
                document.getElementById("myModal").style.display = "flex";
              };

              document.getElementsByClassName("close")[0].onclick = function() {
                document.getElementById("myModal").style.display = "none";
              };

              document.getElementById("studentForm").onsubmit = function(event) {
                event.preventDefault();

                var name = document.getElementById("name").value;
                var nim = document.getElementById("nim").value;
                var phone = document.getElementById("phone").value;

                console.log('Form data:', {
                  name,
                  nim,
                  phone
                });

                if (name === "" || nim === "" || phone === "") {
                  Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please fill in all fields!'
                  });
                  return;
                }

                var phoneRegex = /^[0-9]{10,15}$/;
                if (!phoneRegex.test(phone)) {
                  Swal.fire({
                    icon: 'error',
                    title: 'Invalid Phone Number',
                    text: 'Please enter a valid phone number.'
                  });
                  return;
                }

                var formData = new FormData(document.getElementById("studentForm"));

                Swal.fire({
                  title: 'Are you sure?',
                  text: "Do you want to submit this data?",
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "addSiswa.php", true);
                    xhr.onload = function() {
                      console.log('Response from PHP:', xhr.responseText);
                      if (xhr.status === 200) {
                        Swal.fire({
                          icon: 'success',
                          title: 'Success!',
                          text: 'Data added successfully!'
                        }).then(() => {
                          document.getElementById("myModal").style.display = "none";
                          document.getElementById("studentForm").reset();
                        });
                      } else {
                        Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: 'Error: ' + xhr.statusText
                        });
                      }
                    };
                    xhr.onerror = function() {
                      Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred during the request. Please try again.'
                      });
                    };
                    xhr.send(formData);
                  }
                });
              };
              // ===== PREVIEW PROFILE FUNCTIONALITY =====

// Function to get initials from name
function getInitials(name) {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
}

// Function to determine badge color based on program study
function getProdiColor(prodi) {
    const colors = {
        'Teknologi Informasi': { bg: '#e3f2fd', text: '#1565c0' },
        'Seni Kuliner': { bg: '#f3e5f5', text: '#7b1fa2' },
        'Perhotelan': { bg: '#e8f5e9', text: '#2e7d32' }
    };
    
    for (const [key, color] of Object.entries(colors)) {
        if (prodi.includes(key)) {
            return color;
        }
    }
    return { bg: '#f5f5f5', text: '#333' };
}

// Function to determine badge color based on class
function getKelasColor(kelas) {
    const colors = {
        'A': { bg: '#e3f2fd', text: '#1565c0' },
        'B': { bg: '#f3e5f5', text: '#7b1fa2' },
        'C': { bg: '#e8f5e9', text: '#2e7d32' },
        'D': { bg: '#fff3e0', text: '#ef6c00' },
        'E': { bg: '#fce4ec', text: '#c2185b' }
    };
    
    const firstChar = kelas.charAt(0).toUpperCase();
    return colors[firstChar] || { bg: '#f5f5f5', text: '#333' };
}

// Toggle password visibility
function togglePassword() {
    const passwordDisplay = document.getElementById('previewPassword');
    const eyeIcon = document.querySelector('.btn-show-password i');
    
    if (passwordDisplay.classList.contains('show')) {
        passwordDisplay.textContent = '••••••••';
        passwordDisplay.classList.remove('show');
        eyeIcon.className = 'fas fa-eye';
    } else {
        passwordDisplay.textContent = '[Password Terenkripsi]';
        passwordDisplay.classList.add('show');
        eyeIcon.className = 'fas fa-eye-slash';
    }
}

// Print profile function
function printProfile() {
    const printContent = document.querySelector('.preview-modal-content').cloneNode(true);
    const printWindow = window.open('', '_blank');
    
    // Remove action buttons for print
    const actionButtons = printContent.querySelector('.profile-actions');
    if (actionButtons) actionButtons.remove();
    
    // Remove eye button
    const eyeButton = printContent.querySelector('.btn-show-password');
    if (eyeButton) eyeButton.remove();
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Profil Mahasiswa</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .profile-card { max-width: 500px; margin: 0 auto; }
                    .profile-avatar { 
                        width: 100px; height: 100px; 
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        border-radius: 50%; display: flex; align-items: center;
                        justify-content: center; color: white; font-size: 32px;
                        margin: 0 auto 20px; font-weight: bold;
                    }
                    .profile-name { text-align: center; margin-bottom: 20px; }
                    .profile-details { background: #f5f5f5; padding: 20px; border-radius: 10px; }
                    .detail-item { display: flex; justify-content: space-between; margin-bottom: 10px; }
                    .detail-label { font-weight: bold; }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="profile-card">
                    ${printContent.innerHTML}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        window.close();
                    };
                <\/script>

                
            </body>
        </html>
    `);
    printWindow.document.close();
}

// Preview button functionality
document.addEventListener('DOMContentLoaded', function() {
    const previewModal = document.getElementById('previewModal');
    const closePreviewBtn = document.querySelector('.close-preview');
    let currentMahasiswaId = null;
    
    // Open preview modal when clicking preview buttons
    document.addEventListener('click', function(e) {
        const previewBtn = e.target.closest('.previewBtn');
        if (previewBtn) {
            // Get data from button attributes
            const mahasiswaId = previewBtn.getAttribute('data-id');
            const nama = previewBtn.getAttribute('data-nama');
            const nim = previewBtn.getAttribute('data-nim');
            const prodi = previewBtn.getAttribute('data-prodi');
            const kelas = previewBtn.getAttribute('data-kelas');
            const telepon = previewBtn.getAttribute('data-telepon');
            const username = previewBtn.getAttribute('data-username');
            const password = previewBtn.getAttribute('data-password');

            currentMahasiswaId = mahasiswaId;
            
            // Update modal content
            document.getElementById('previewNama').textContent = nama;
            document.getElementById('previewNim').textContent = nim;
            document.getElementById('previewTelepon').textContent = telepon;
            document.getElementById('previewUsername').textContent = username;
            document.getElementById('previewPassword').textContent = password;
            document.getElementById('previewId').textContent = mahasiswaId;
            
            // Update avatar with initials
            const initials = getInitials(nama);
            document.getElementById('profileAvatar').textContent = initials;
            
            // Update prodi badge
            const prodiBadge = document.getElementById('previewProdiBadge');
            prodiBadge.textContent = prodi;
            const prodiColor = getProdiColor(prodi);
            prodiBadge.style.backgroundColor = prodiColor.bg;
            prodiBadge.style.color = prodiColor.text;
            
            // Update kelas badge
            const kelasBadge = document.getElementById('previewKelasBadge');
            kelasBadge.textContent = kelas;
            const kelasColor = getKelasColor(kelas);
            kelasBadge.style.backgroundColor = kelasColor.bg;
            kelasBadge.style.color = kelasColor.text;
            
            // Update edit button to edit this mahasiswa
            document.getElementById('previewEditBtn').onclick = function() {
                previewModal.style.display = 'none';
                editMahasiswa(mahasiswaId);
            };
            
            // Update email button
            document.getElementById('previewEmailBtn').onclick = function() {
                const email = prompt('Masukkan email untuk mahasiswa ini:', '');
                if (email) {
                    alert(`Email akan dikirim ke: ${email}\nFitur ini akan diimplementasi kemudian.`);
                }
            };
            
            // Show modal with animation
            previewModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    });
    
    // Close preview modal
    closePreviewBtn.addEventListener('click', function() {
        previewModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === previewModal) {
            previewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Close with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && previewModal.style.display === 'block') {
            previewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});

// Function to edit mahasiswa (connect with existing edit modal)
function editMahasiswa(id) {
    // Tutup preview modal jika terbuka
    const previewModal = document.getElementById('previewModal');
    if (previewModal.style.display === 'block') {
        previewModal.style.display = 'none';
    }
    
    // Buka edit modal
    const editModal = document.getElementById('editModal');
    const closeEditModal = document.getElementById('closeEditModal');
    
    // Fetch data mahasiswa untuk diisi di form edit
    fetch(`get_mahasiswa.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Isi form edit dengan data
                document.getElementById('edit_id').value = data.mahasiswa.id_mahasiswa;
                document.getElementById('edit_name').value = data.mahasiswa.nama_mahasiswa;
                document.getElementById('edit_nim').value = data.mahasiswa.nim;
                document.getElementById('edit_prodi').value = data.mahasiswa.prodi;
                document.getElementById('edit_kelas').value = data.mahasiswa.kelas;
                document.getElementById('edit_telepon').value = data.mahasiswa.nomor_telepon;
                document.getElementById('edit_username').value = data.mahasiswa.username;
                document.getElementById('edit_password').value = data.mahasiswa.password;
                
                // Tampilkan modal edit
                editModal.style.display = 'flex';
                
                // Update close button functionality
                closeEditModal.onclick = function() {
                    editModal.style.display = 'none';
                };
                
                // Close when clicking outside
                window.onclick = function(event) {
                    if (event.target == editModal) {
                        editModal.style.display = 'none';
                    }
                };
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data untuk edit');
        });
}
            </script>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
              document.addEventListener("DOMContentLoaded", function() {
                // Fungsi untuk membuka modal edit dan mengisi data ke dalam form
                document.body.addEventListener("click", function(event) {
                  if (event.target.classList.contains("editBtn")) {
                    document.getElementById("edit_id").value = event.target.getAttribute("data-id");
                    document.getElementById("edit_name").value = event.target.getAttribute("data-nama");
                    document.getElementById("edit_nim").value = event.target.getAttribute("data-nim");
                    document.getElementById("edit_prodi").value = event.target.getAttribute("data-prodi");
                    document.getElementById("edit_kelas").value = event.target.getAttribute("data-kelas");
                    document.getElementById("edit_telepon").value = event.target.getAttribute("data-telepon");
                    document.getElementById("edit_username").value = event.target.getAttribute("data-username");
                    document.getElementById("edit_password").value = event.target.getAttribute("data-password");

                    document.getElementById("editModal").style.display = "flex";
                  }
                });

                // Menutup modal edit
                document.getElementById("closeEditModal").onclick = function() {
                  document.getElementById("editModal").style.display = "none";
                };

                // Kirim data edit ke PHP dengan SweetAlert2
                document.getElementById("editForm").onsubmit = function(event) {
                  event.preventDefault();

                  var formData = new FormData(document.getElementById("editForm"));

                  var xhr = new XMLHttpRequest();
                  xhr.open("POST", "editSiswa.php", true);
                  xhr.onload = function() {
                    if (xhr.status === 200) {
                      Swal.fire({
                        title: "Berhasil!",
                        text: "Data mahasiswa berhasil diperbarui.",
                        icon: "success",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                      }).then(() => {
                        document.getElementById("editModal").style.display = "none";
                        location.reload();
                      });
                    } else {
                      Swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan: " + xhr.statusText,
                        icon: "error",
                        confirmButtonColor: "#d33"
                      });
                    }
                  };
                  xhr.onerror = function() {
                    Swal.fire({
                      title: "Kesalahan!",
                      text: "Terjadi kesalahan dalam koneksi.",
                      icon: "error",
                      confirmButtonColor: "#d33"
                    });
                  };
                  xhr.send(formData);
                };

                // Menggunakan SweetAlert2 untuk konfirmasi hapus
                document.body.addEventListener("click", function(event) {
                  if (event.target.classList.contains("deleteBtn")) {
                    let id = event.target.getAttribute("data-id");

                    Swal.fire({
                      title: "Apakah Anda yakin?",
                      text: "Data mahasiswa ini akan dihapus secara permanen!",
                      icon: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#d33",
                      cancelButtonColor: "#3085d6",
                      confirmButtonText: "Ya, Hapus!",
                      cancelButtonText: "Batal"
                    }).then((result) => {
                      if (result.isConfirmed) {
                        let xhr = new XMLHttpRequest();
                        xhr.open("POST", "deleteSiswa.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                          if (xhr.readyState === 4 && xhr.status === 200) {
                            Swal.fire(
                              "Terhapus!",
                              "Data mahasiswa telah berhasil dihapus.",
                              "success"
                            ).then(() => {
                              location.reload();
                            });
                          }
                        };
                        xhr.send("id_mahasiswa=" + id);
                      }
                    });
                  }
                });
              });
            </script>



          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
              Copyright © 2025.
              <a href="https://nestpoliteknik.com/" target="_blank">Politeknik Nest Sukoharjo</a>.
              All rights reserved.
            </span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
              <a href="https://wa.me/628112951003" target="_blank">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" width="20" height="20" class="me-2">
                +6281 1295 1003
              </a>
            </span>
          </div>

          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a href="https://politekniknest.ac.id/" target="_blank">Anak Magang UNS</a></span>
          </div>
        </footer>
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

<script>
document.addEventListener("click", function (e) {
  const btn = e.target.closest(".previewMhsBtn");
  if (!btn) return;

  document.getElementById("m_nama").innerText = btn.dataset.nama;
  document.getElementById("m_nim").innerText = btn.dataset.nim;
  document.getElementById("m_kelas").innerText = btn.dataset.kelas;
  document.getElementById("m_prodi").innerText = btn.dataset.prodi;
  document.getElementById("m_telp").innerText = btn.dataset.telp;
  document.getElementById("m_username").innerText = btn.dataset.username;

  // password dummy
  document.getElementById("m_password").innerText = "••••••••";

  document.getElementById("previewMhsModal").style.display = "flex";
});

// close button
document.getElementById("closePreviewMhs").onclick = function () {
  document.getElementById("previewMhsModal").style.display = "none";
};

// klik luar modal
window.addEventListener("click", function (e) {
  const modal = document.getElementById("previewMhsModal");
  if (e.target === modal) {
    modal.style.display = "none";
  }
});
</script>



  <!-- End custom js for this page-->
</body>

</html>