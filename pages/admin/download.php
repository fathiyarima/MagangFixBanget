<?php
require "../../config/connection.php";

if (!isset($_GET['id']) || !isset($_GET['column'])) {
    die("Missing parameters.");
}

$userId = $_GET['id'];
$column = $_GET['column'];

$query = "SELECT $column FROM mahasiswa WHERE id_mahasiswa = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($fileData);
$stmt->fetch();
$stmt->close();
$conn->close();

if (!$fileData) {
    die("File not found.");
}

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename='$column.pdf'");
echo $fileData;
exit();
?>
