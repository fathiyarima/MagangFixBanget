<?php
$conn = new mysqli("localhost", "root", "", "sistem_ta");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']);
    $type = $_GET['type']; // Example: "formulir", "persetujuan", "konsultasi"

    $query = "SELECT $type FROM mahasiswa WHERE id_mahasiswa = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($fileData);
        $stmt->fetch();

        header("Content-Disposition: attachment; filename=" . $type . ".pdf");
        echo $fileData;
    } else {
        echo "File not found!";
    }
} else {
    echo "Invalid request!";
}
?>
