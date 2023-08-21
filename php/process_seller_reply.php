<?php
session_start();
include_once('config.php'); // Include your database connection details
ini_set('log_errors', 1);
ini_set('error_log', '../error_log_file.log'); // Replace with the desired file path and name
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticketId'];
    $sellerReply = $_POST['sellerReply'];
    $unique_id = $_SESSION['unique_id'];

    // Update conversation history with seller's reply
    $insertReplyQuery = "INSERT INTO ticketresponse (ticket_id, user_uniqueid, message) 
                         VALUES ('$ticketId', '$unique_id', '$sellerReply')";
    $insertResult = mysqli_query($conn, $insertReplyQuery);

    if ($insertResult) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit reply.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
