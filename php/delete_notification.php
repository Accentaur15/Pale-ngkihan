<?php
// Include necessary files and configurations
session_start();
ini_set('log_errors', 1);
ini_set('error_log', '../error_log_file.log'); // Replace with the desired file path and name
include_once('../php/config.php');

// Check if the request is sent via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_id'])) {
    // Get the notification ID from the POST data
    $notificationId = mysqli_real_escape_string($conn, $_POST['notification_id']);

    // Perform the deletion query (adjust the table and column names as needed)
    $deleteQuery = "DELETE FROM notifications WHERE id = '{$notificationId}'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        // Return success response
        echo json_encode(['success' => true]);
        exit;
    } else {
        // Return failure response
        echo json_encode(['success' => false, 'error' => 'Failed to delete notification']);
        exit;
    }
}
?>
