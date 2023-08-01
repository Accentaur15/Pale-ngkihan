<?php
include_once('../php/config.php');

if (isset($_POST['notification_id'])) {
    $notificationId = $_POST['notification_id'];

    try {
        // Update the is_seen status for the notification
        $updateQuery = "UPDATE notifications SET is_seen = 1 WHERE id = '$notificationId'";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            // Return a success response to the JavaScript code
            echo json_encode(['status' => 'success']);
        } else {
            // Return an error response if the update fails
            echo json_encode(['status' => 'error', 'message' => 'Failed to mark notification as seen']);
        }
    } catch (Exception $e) {
        // Handle any database connection or query errors
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Return an error response if the notification_id parameter is missing
    echo json_encode(['status' => 'error', 'message' => 'Notification ID not provided']);
}
