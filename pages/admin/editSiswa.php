<?php
include "../../config/connection.php";

$id = $_POST['id_mahasiswa'];
$nama = $_POST['nama_mahasiswa'];
$nim = $_POST['nim'];
$prodi = $_POST['prodi'];
$kelas = $_POST['kelas'];
$telepon = $_POST['nomor_telepon'];
$username = $_POST['username'];
$pass = $_POST['password'];
$hashedPass = password_hash($pass, PASSWORD_DEFAULT);

$sql = "UPDATE mahasiswa SET nama_mahasiswa='$nama', nim='$nim', prodi='$prodi', kelas='$kelas', nomor_telepon='$telepon', username='$username', pass='$hashedPass' WHERE id_mahasiswa='$id'";

if ($conn->query($sql) === TRUE) {
  echo "Success";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
