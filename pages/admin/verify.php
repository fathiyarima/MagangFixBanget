<?php
require "../../config/connection.php";

$userId = $_POST['userId'];
$event = $_POST['event'];
$column = $_POST['column'];

// Check if the user already exists in verifikasi_dokumen
$checkid = "SELECT id_mahasiswa FROM verifikasi_dokumen WHERE id_mahasiswa = ?";
$stmtid = $conn->prepare($checkid);
$stmtid->bind_param("i", $userId);
$stmtid->execute();
$stmtid->store_result();
$exists = $stmtid->num_rows > 0;
$stmtid->close();

if ($exists) {
    // If the record exists, update the document verification status
    $query = "UPDATE verifikasi_dokumen SET `$column` = 1 WHERE id_mahasiswa = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
} else {
    // If the record does not exist, insert a new row with default values for all columns
    $query = "INSERT INTO verifikasi_dokumen (id_mahasiswa, `$column`) VALUES (?, 1)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

$stmt_nama = $conn->prepare("SELECT nama_mahasiswa FROM mahasiswa WHERE id_mahasiswa = ?");
$stmt_nama->bind_param("i", $userId);
$stmt_nama->execute();
$stmt_nama->bind_result($nama_mahasiswa);
$stmt_nama->fetch();
$stmt_nama->close();

// Add notification
$message = "File " . $column . " untuk mahasiswa " . $nama_mahasiswa . " telah diverifikasi.";
$notif_sql = "INSERT INTO notif (id_mahasiswa, message, status_mahasiswa) VALUES (?, ?, 'unread')";
$stmt_notify = $conn->prepare($notif_sql);
$stmt_notify->bind_param("is", $userId, $message);

if (!$stmt_notify->execute()) {
    die("Error menambahkan notifikasi: " . $stmt_notify->error);
}

$stmt_notify->close();

echo "Success";
?>
