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

// Get the result set instead of binding
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();

if (!$row || !$row[$column]) {
    die("File not found.");
}

$fileData = $row[$column];

// Set headers before outputting data
header("Content-Type: application/pdf");
header("Content-Length: " . strlen($fileData));
header("Content-Disposition: attachment; filename='" . basename($column) . ".pdf'");

echo $fileData;
exit();
?>