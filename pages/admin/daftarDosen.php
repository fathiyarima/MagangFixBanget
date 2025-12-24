<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../../index.php");
  exit;
}

include "../../config/connection.php";
$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Daftar Dosen</title>

<!-- CSS (TIDAK DIUBAH) -->
<link rel="stylesheet" href="../../Template/skydash/vendors/feather/feather.css">
<link rel="stylesheet" href="../../Template/skydash/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../../Template/skydash/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../../Template/skydash/css/vertical-layout-light/style.css">
<link rel="shortcut icon" href="../../assets/img/Logo.webp">
<link rel="stylesheet" href="../../assets/css/admin/dosen.css">
<link rel="stylesheet" href="../../assets/css/admin/daftardosen.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<div class="container-scroller">
<?php include "sidebar.php"; ?>

<div class="main-panel">
<div class="content-wrapper">

<div class="card">
<div class="card-body">
<p class="card-title" style="text-align:center;">Daftar Dosen</p>

<?php
$result = $conn->query("SELECT * FROM dosen_pembimbing");
$limit = 5;
                      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                      $offset = ($page - 1) * $limit;

                      $sql1 = "SELECT id_dosen, nama_dosen, nip, prodi, nomor_telepon, username, pass FROM dosen_pembimbing LIMIT $limit OFFSET $offset";
                      $result2 = $conn->query($sql1);

                      $totalQuery = "SELECT COUNT(id_dosen) AS total FROM dosen_pembimbing";
                      $totalResult = $conn->query($totalQuery);
                      $totalRow = $totalResult->fetch_assoc();
                      $totalData = $totalRow['total'];
                      $totalPages = ceil($totalData / $limit);
?>

<div style="overflow-x:auto">
<table class="display expandable-table" style="width:100%">
<thead>
<tr>
<th>No</th>
<th>Nama</th>
<th>NIP</th>
<th>Prodi</th>
<th>Detail</th>
<th>Edit</th>
<th>Hapus</th>
</tr>
</thead>

<tbody>
<?php while($r = $result->fetch_assoc()): ?>
<tr>
<td><?= $r['id_dosen'] ?></td>
<td><?= $r['nama_dosen'] ?></td>
<td><?= $r['nip'] ?></td>
<td><?= $r['prodi'] ?></td>

<td style="text-align: center">
<button class="detailBtn btn btn-info btn-sm"
data-id="<?= $r['id_dosen'] ?>">Lihat Selengkapnya</button>
</td>

<td style="text-align: center">
<button class="editBtn btn btn-warning btn-sm"
data-id="<?= $r['id_dosen'] ?>">Edit</button>
</td>

<td style="text-align: center">
<button class="deleteBtn btn btn-danger btn-sm"
data-id="<?= $r['id_dosen'] ?>">Hapus</button>
</td>
</tr>
<?php endwhile; ?>
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

<div class="d-flex justify-content-end gap-2 mt-3">
<button id="openModalBtn" class="btn btn-primary">➕ Add Data</button>
<button id="openModalBatch" class="btn btn-outline-primary">📥 Add Batch</button>
</div>

</div>
</div>

</div>
</div>
</div>

<!-- ================= ADD MODAL ================= -->
<div id="myModal" class="modal">
<div class="modal-content">
<span class="close">&times;</span>
<h2>Add Data Dosen</h2>

<form id="studentForm">
                  <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="nama_dosen" required>
                  </div>

                  <div class="form-group">
                    <label for="nim">NIP:</label>
                    <input type="text" id="nip" name="nip" required>
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
                  </div><button type="submit">Submit</button>
</form>
</div>
</div>

<!-- ================= EDIT MODAL (FIXED) ================= -->
<div id="editModal" class="modal">
<div class="modal-content">
<span class="close">&times;</span>

<h2>Edit Data Dosen</h2>

<form id="editForm">
<input type="hidden" id="edit_id" name="id_dosen">

<div class="form-group">
<label>Nama</label>
<input type="text" id="edit_name" name="nama_dosen" required>
</div>

<div class="form-group">
<label>NIP</label>
<input type="text" id="edit_nip" name="nip" required>
</div>

<div class="form-group">
<label>Program Studi</label>
<select id="edit_prodi" name="prodi" required>
<option value="Teknologi Informasi">Teknologi Informasi</option>
<option value="Seni Kuliner">Seni Kuliner</option>
<option value="Perhotelan">Perhotelan</option>
</select>
</div>

<div class="form-group">
<label>Nomor Telepon</label>
<input type="text" id="edit_telepon" name="nomor_telepon" required>
</div>

<div class="form-group">
<label>Username</label>
<input type="text" id="edit_username" name="username" required>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" id="edit_password" name="pass" required>
</div>

<button type="submit" class="btn-submit">Simpan Perubahan</button>
</form>
</div>
</div>

<style>

          th {
    background: linear-gradient(135deg, #4B49AC 0%, #6c5ce7 100%) !important;
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    position: sticky;
    top: 0;
    z-index: 10;
    text-align: center;
}

  /* ================= DETAIL DOSEN MODAL ================= */
#detailModal .modal-content {
  max-width: 420px;
  width: 90%;
  padding: 24px 26px;
  border-radius: 14px;
  background: #ffffff;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
  animation: fadeScale 0.25s ease;
}

#detailModal h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #4B49AC;
  font-weight: 700;
}

/* isi detail */
#detailModal p {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 14px;
  margin-bottom: 10px;
  background: #f6f7fb;
  border-radius: 10px;
  font-size: 0.9rem;
}

#detailModal p b {
  color: #4B49AC;
  font-weight: 600;
}

/* value */
#detailModal span {
  color: #333;
  font-weight: 500;
  word-break: break-word;
  text-align: right;
}

/* close button */
#detailModal .close {
  position: absolute;
  top: 14px;
  right: 18px;
  font-size: 22px;
  font-weight: bold;
  color: #999;
  cursor: pointer;
}

#detailModal .close:hover {
  color: #e74c3c;
}

/* animasi halus */
@keyframes fadeScale {
  from {
    opacity: 0;
    transform: scale(0.92);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}


/* ================= ADD DATA DOSEN MODAL ================= */
#myModal .modal-content {
  max-width: 460px;
  width: 90%;
  padding: 26px 28px;
  border-radius: 16px;
  background: #ffffff;
  box-shadow: 0 14px 35px rgba(0, 0, 0, 0.2);
  animation: fadeScale 0.25s ease;
}

#myModal h2 {
  text-align: center;
  margin-bottom: 22px;
  color: #4B49AC;
  font-weight: 700;
}

/* form group */
#myModal .form-group {
  margin-bottom: 14px;
}

#myModal label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
  font-size: 0.85rem;
  color: #4B49AC;
}

/* input & select */
#myModal input,
#myModal select {

<style>
/* ===== FORM GROUP SPACING (ADD & EDIT DOSEN) ===== */
#myModal .form-group,
#editModal .form-group {
  margin-bottom: 16px;
}

/* Label lebih rapi */
#myModal label,
#editModal label {
  display: block;
  font-weight: 600;
  margin-bottom: 6px;
  color: #333;
}

/* Input & select full width + nyaman */
#myModal input,
#myModal select,
#editModal input,
#editModal select {
  width: 100%;
  padding: 10px 12px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 14px;
}

/* Fokus input */
#myModal input:focus,
#myModal select:focus,
#editModal input:focus,
#editModal select:focus {
  outline: none;
  border-color: #4B49AC;
  box-shadow: 0 0 0 2px rgba(75,73,172,.15);
}

/* Tombol submit */
#myModal button,
#editModal button {
  margin-top: 10px;
}
</style>


</style>
<!-- ================= DETAIL MODAL ================= -->
<div id="detailModal" class="modal">
<div class="modal-content">
<span class="close">&times;</span>
<h2>Detail Dosen</h2>

<p><b>Nama:</b> <span id="d_nama"></span></p>
<p><b>NIP:</b> <span id="d_nip"></span></p>
<p><b>Prodi:</b> <span id="d_prodi"></span></p>
<p><b>Nomor Telepon:</b> <span id="d_telepon"></span></p>
<p><b>Username:</b> <span id="d_username"></span></p>
<p><b>Password:</b> <span id="d_password"></span></p>

</div>
</div>

<!-- ================= BATCH MODAL ================= -->
<div id="ModalBatch" class="modal">
  <div class="modal-content">
    <span class="close" id="closeModalBatch">&times;</span>
    <form action="upload_aksi_dosen.php" method="post" enctype="multipart/form-data">
      <label for="file">Choose an Excel file to upload:</label>
      <input type="file" name="excel_file" id="excel_file" required>
      <button type="submit" name="submit">Upload</button>
    </form>
  </div>
</div>

<script>
const myModal = document.getElementById("myModal");
const editModal = document.getElementById("editModal");
const detailModal = document.getElementById("detailModal");
const batchModal = document.getElementById("ModalBatch");

/* OPEN */
openModalBtn.onclick = () => myModal.style.display = "flex";
openModalBatch.onclick = () => batchModal.style.display = "flex";

/* CLOSE */
document.querySelectorAll(".close").forEach(btn=>{
btn.onclick=()=>{
myModal.style.display="none";
editModal.style.display="none";
detailModal.style.display="none";
batchModal.style.display="none";
};
});

window.onclick=e=>{
if(e.target===myModal) myModal.style.display="none";
if(e.target===editModal) editModal.style.display="none";
if(e.target===detailModal) detailModal.style.display="none";
if(e.target===batchModal) batchModal.style.display="none";
};

/* DETAIL */
document.querySelectorAll(".detailBtn").forEach(btn=>{
btn.onclick=()=>{
fetch(`getDosen.php?id=${btn.dataset.id}`)
.then(r=>r.json())
.then(d=>{
d_nama.innerText=d.nama_dosen;
d_nip.innerText=d.nip;
d_prodi.innerText=d.prodi;
d_telepon.innerText=d.nomor_telepon;
d_username.innerText=d.username;
d_password.innerText = d.pass;
detailModal.style.display="flex";
});
};
});

/* EDIT (FIXED ID) */
document.querySelectorAll(".editBtn").forEach(btn=>{
btn.onclick=()=>{
fetch(`getDosen.php?id=${btn.dataset.id}`)
.then(r=>r.json())
.then(d=>{
document.getElementById("edit_id").value = d.id_dosen;
document.getElementById("edit_name").value = d.nama_dosen;
document.getElementById("edit_nip").value = d.nip;
document.getElementById("edit_prodi").value = d.prodi;
document.getElementById("edit_telepon").value = d.nomor_telepon;
document.getElementById("edit_username").value = d.username;
document.getElementById("edit_password").value = d.pass;
editModal.style.display="flex";
});
};
});

/* ADD */
studentForm.onsubmit=e=>{
e.preventDefault();
fetch("addDosen.php",{method:"POST",body:new FormData(studentForm)})
.then(()=>Swal.fire("Berhasil","Data ditambahkan","success"))
.then(()=>location.reload());
};

/* UPDATE */
editForm.onsubmit=e=>{
e.preventDefault();
fetch("editDosen.php",{method:"POST",body:new FormData(editForm)})
.then(()=>Swal.fire("Berhasil","Data diperbarui","success"))
.then(()=>location.reload());
};

/* DELETE */
document.querySelectorAll(".deleteBtn").forEach(btn=>{
btn.onclick=()=>{
Swal.fire({title:"Yakin hapus?",icon:"warning",showCancelButton:true})
.then(r=>{
if(r.isConfirmed){
fetch("deleteDosen.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:"id_dosen="+btn.dataset.id
}).then(()=>location.reload());
}
});
};
});
</script>

</body>
</html>
