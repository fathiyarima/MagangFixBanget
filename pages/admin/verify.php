<?php
require "../../config/connection.php";

$userId = $_POST['userId'];
$event = $_POST['event'];
$column = $_POST['column'];

$query = "UPDATE $event SET $column = 1 WHERE id_mahasiswa = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();

echo "Success";
?>
