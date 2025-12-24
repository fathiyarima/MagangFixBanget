<?php
include "../../config/connection.php";
header("Content-Type: application/json");

if ($conn->connect_error) {
  echo json_encode(["status"=>"error","message"=>"Koneksi gagal"]);
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $required = ['nama_dosen','nip','prodi','nomor_telepon','username','pass'];
  foreach ($required as $r) {
    if (empty($_POST[$r])) {
      echo json_encode(["status"=>"error","message"=>"Field $r kosong"]);
      exit;
    }
  }

  $nama = $_POST['nama_dosen'];
  $nip = $_POST['nip'];
  $prodi = $_POST['prodi'];
  $telp = $_POST['nomor_telepon'];
  $username = $_POST['username'];
  $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare(
    "INSERT INTO dosen_pembimbing 
    (nama_dosen, username, pass, nip, prodi, nomor_telepon) 
    VALUES (?,?,?,?,?,?)"
  );

  $stmt->bind_param("ssssss", $nama,$username,$pass,$nip,$prodi,$telp);

  if ($stmt->execute()) {
    echo json_encode(["status"=>"success","message"=>"Data berhasil disimpan"]);
  } else {
    echo json_encode(["status"=>"error","message"=>$stmt->error]);
  }

  $stmt->close();
}
$conn->close();
