<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=sistem_ta', 'root', '');

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    try {
        // Check if the user is a 'dosen_pembimbing'
        $queryDosen = "SELECT 'dosen_pembimbing' AS source_table, id_dosen, username, pass, nip FROM dosen_pembimbing WHERE username = :username";
        $stmt = $pdo->prepare($queryDosen);
        $stmt->execute(['username' => $username]);

        $rowDosen = $stmt->fetch(PDO::FETCH_ASSOC);

        // If the user is found in 'dosen_pembimbing', mark notifications as read
        if ($rowDosen) {
            $user_id = $rowDosen['id_dosen'];  // Using id_dosen for 'dosen_pembimbing'
            $sql = "UPDATE notif SET status = 'read' WHERE id_dosen = :user_id AND status = 'unread'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);

            // Set the message and redirection URL for dosen_pembimbing
            $message = "Notifications for Dosen Pembimbing marked as read!";
            $redirectUrl = "index.php";  // Redirect to dosen_dashboard.php
        } else {
            // If not found in 'dosen_pembimbing', check for 'mahasiswa'
            $queryMahasiswa = "SELECT 'mahasiswa' AS source_table, id_mahasiswa, username, pass, nim FROM mahasiswa WHERE username = :username";
            $stmt = $pdo->prepare($queryMahasiswa);
            $stmt->execute(['username' => $username]);

            $rowMahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

            // If the user is found in 'mahasiswa', mark notifications as read
            if ($rowMahasiswa) {
                $user_id = $rowMahasiswa['id_mahasiswa'];  // Using id_mahasiswa for 'mahasiswa'
                $sql = "UPDATE notif SET status = 'read' WHERE id_mahasiswa = :user_id AND status = 'unread'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['user_id' => $user_id]);

                // Set the message and redirection URL for mahasiswa
                $message = "Notifications for Mahasiswa marked as read!";
                $redirectUrl = "pages/user/dashboard.php";  // Redirect to mahasiswa_dashboard.php
            } else {
                // If the user is not found in either table
                $message = "Username or Password is incorrect!";
            }
        }

        // Check if there was no error and redirect accordingly
        if (!isset($error)) {
            // Redirect after marking notifications as read
            if (!headers_sent()) {
                header("Location: " . $redirectUrl);
                exit();
            } else {
                echo "Headers already sent. Using JavaScript redirect...";
                echo "<script>window.location.href = '$redirectUrl';</script>";
                exit();
            }
        } else {
            // Display error message if any
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } catch (PDOException $e) {
        // Error handling if query fails
        echo "<div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div>";
    }
} else {
    // If the user is not logged in
    echo "<div class='alert alert-danger'>You are not logged in!</div>";
}
?>
