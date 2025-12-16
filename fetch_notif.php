<?php
ob_start(); // Prevent unexpected output
session_start();
header('Content-Type: application/json'); // Ensure JSON response
include "config/connection.php";

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$username = $_SESSION['username'];

try {
    if (!isset($pdo)) {
        echo json_encode(['error' => 'Database connection missing']);
        exit;
    }

    $userTypes = [
        'dosen_pembimbing' => ['id_dosen', 'status_dosen'],
        'mahasiswa' => ['id_mahasiswa', 'status_mahasiswa'],
        'admin' => ['id_admin', 'status_admin']
    ];

    foreach ($userTypes as $table => [$userIdField, $statusField]) {
        $query = "SELECT id, message, created_at FROM notif WHERE $userIdField = 
                  (SELECT $userIdField FROM $table WHERE username = :username) AND $statusField = 'unread'";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($notifications) {
            echo json_encode($notifications);
            exit;
        }
    }

    echo json_encode(['message' => 'No unread notifications']);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
