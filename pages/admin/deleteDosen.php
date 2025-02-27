<?php
include "../../config/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['id_dosen']) || empty($_POST['id_dosen'])) {
        echo "ID dosen tidak valid!";
        exit();
    }

    $id = intval($_POST['id_dosen']);  // Convert to integer for safety

    $sql = "DELETE FROM dosen_pembimbing WHERE id_dosen = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Data berhasil dihapus";  // Success message
    } else {
        echo "Error: " . $conn->error;  // Debugging message
    }

    $stmt->close();
}

$conn->close();
?>
