<?php
include '../../config/connection.php';
$conn->connect("127.0.0.1", "root", "", "sistem_ta");

if (!empty($_POST)) {
    echo "<script>";
    echo "console.log(" . json_encode($_POST) . ");"; // Send $_POST to the console
    echo "</script>";
}

if (isset($_POST['id_mahasiswa']) && isset($_POST['status_ujian']) && isset($_POST['nilai'])) {
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $status_ujian = $_POST['status_ujian'];
    $nilai = $_POST['nilai'];

    // Debugging the data being posted
    var_dump($_POST);

    $valid_statuses = ['dijadwalkan', 'selesai'];
    if (!in_array($status_ujian, $valid_statuses)) {
        echo "Invalid status value.";
        exit;
    }

    if (empty($id_mahasiswa) || $id_mahasiswa == 0) {
        echo "Error: id_mahasiswa is invalid.";
        exit;
    }

    // Ensure nilai is numeric
    if (!is_numeric($nilai)) {
        echo "Invalid nilai. It should be a number.";
        exit;
    }

    // Validate if id_mahasiswa exists in the mahasiswa table
    $check_mahasiswa_sql = "SELECT id_mahasiswa FROM mahasiswa WHERE id_mahasiswa = ?";
    $check_mahasiswa_stmt = $conn->prepare($check_mahasiswa_sql);
    if ($check_mahasiswa_stmt === false) {
        die("Error preparing check statement: " . $conn->error);
    }

    $check_mahasiswa_stmt->bind_param("i", $id_mahasiswa);
    $check_mahasiswa_stmt->execute();
    $check_mahasiswa_stmt->store_result();

    if ($check_mahasiswa_stmt->num_rows == 0) {
        echo "Error: id_mahasiswa does not exist in the mahasiswa table.";
        exit;
    }

    // Check if the record for id_mahasiswa exists in the ujian table
    $check_sql = "SELECT id_mahasiswa FROM ujian WHERE id_mahasiswa = ?";
    $check_stmt = $conn->prepare($check_sql);
    if ($check_stmt === false) {
        die("Error preparing check statement: " . $conn->error);
    }

    $check_stmt->bind_param("i", $id_mahasiswa);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // If record exists, update
        $sql = "UPDATE ujian SET status_ujian = ?, nilai = ? WHERE id_mahasiswa = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing update statement: " . $conn->error);
        }

        $stmt->bind_param("sii", $status_ujian, $nilai, $id_mahasiswa);

        if ($stmt->execute()) {
            echo "Status updated successfully.";
        } else {
            echo "Error updating status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // If record does not exist, insert
        $sql = "INSERT INTO ujian (id_mahasiswa, status_ujian, nilai) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing insert statement: " . $conn->error);
        }

        $stmt->bind_param("isi", $id_mahasiswa, $status_ujian, $nilai);

        if ($stmt->execute()) {
            echo "Status added successfully.";
        } else {
            echo "Error adding status: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $check_mahasiswa_stmt->close();
} else {
    echo "Error: Required fields are missing.";
}

$conn->close();

// Redirect after processing
header("Location: pendaftaranujian.php");
exit();
?>
