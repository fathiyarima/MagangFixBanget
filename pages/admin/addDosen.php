<?php
include "../../config/connection.php";

// Ensure JSON response
header("Content-Type: application/json");

// Start output buffering to prevent hidden characters
ob_start();

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nama_dosen'], $_POST['nip'], $_POST['prodi'], $_POST['nomor_telepon'], $_POST['username'], $_POST['pass'])) {
        $nama = trim($_POST['nama_dosen']);
        $nip = trim($_POST['nip']);
        $prodi = trim($_POST['prodi']);
        $nomor_telepon = trim($_POST['nomor_telepon']);
        $username = trim($_POST['username']);
        $pass = trim($_POST['pass']);

        if (empty($nama) || empty($username) || empty($pass) || empty($nip) || empty($prodi) || empty($nomor_telepon)) {
            echo json_encode(["status" => "error", "message" => "Semua field wajib diisi!"]);
            exit();
        }

        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO `dosen_pembimbing`(`nama_dosen`, `username`, `pass`, `nip`, `prodi`, `nomor_telepon`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nama, $username, $hashedPass, $nip, $prodi, $nomor_telepon);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Dosen berhasil ditambahkan!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Terjadi kesalahan: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Field tidak lengkap!"]);
    }
}

// Flush output buffer to ensure clean JSON output
ob_end_flush();

$conn->close();
?>
