<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "sistem_ta";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'], $_POST['event'])) {
        $id = $_POST['id'];
        $event = $_POST['event'];
        $check = $conn->prepare("SELECT id_mahasiswa FROM $event WHERE id_mahasiswa = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "Kau sudah Mensubmit";
        } else {
            $stmt = $conn->prepare("INSERT INTO $event (`id_mahasiswa`) VALUES (?)");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "New record created successfully!";
            } else {
                echo "Error executing statement: " . $stmt->error;
            }

            $stmt->close();
        }

        $check->close();
    } else {
        echo "Missing required fields!";
    }
}

$conn->close();
?>
