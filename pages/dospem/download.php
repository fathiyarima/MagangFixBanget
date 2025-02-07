<?php
// Enable error reporting to display any potential errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to your database
$conn = new mysqli('127.0.0.1', 'root', '', 'sistem_ta');

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $file_id = $_GET['id'];

    // Prepare the SQL query to get the file data from the database
    $sql = "SELECT sppsp, lbta FROM seminar_proposal WHERE id_mahasiswa = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $stmt->bind_result($sppsp, $lbta);

    // Debugging output to check if data is being fetched
    if ($stmt->fetch()) {
        echo "Data fetched successfully.<br>";

        // Show the lengths of the BLOBs (Binary Data)
        echo "Form Pendaftaran (length): " . (empty($sppsp) ? "No file" : strlen($form_pendaftaran)) . " bytes<br>";
        echo "Bukti Transkip (length): " . (empty($lbta) ? "No file" : strlen($bukti_transkip)) . " bytes<br>";
    } else {
        echo "No data found for the provided ID.<br>";
    }

    // Determine which file to send based on the request
    $file = null;
    $file_name = '';

    if (!empty($sppspn)) {
        $file = $form_pendaftaran;
        $file_name = 'Form_Pendaftaran.pdf';
    } elseif (!empty($lbta)) {
        $file = $bukti_transkip;
        $file_name = 'Bukti_Transkip.pdf';
    } 

    if ($file) {
        // Set headers for download
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"$file_name\"");
        header("Content-Length: " . strlen($file));
        echo $file;
    } else {
        echo "No file found!";
    }

    $stmt->close();
} else {
    echo "No ID parameter provided!";
}

$conn->close();
?>
