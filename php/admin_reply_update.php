<?php
session_start();
include_once('../php/config.php');
ini_set('log_errors', 1);
ini_set('error_log', '../error_log_file.log'); // Replace with the desired file path and name

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticketId'];
    $adminReply = $_POST['adminReply'];
    $statusUpdate = $_POST['statusUpdate'];
    $adminUniqueId = $_SESSION['unique_id']; // Get the admin's unique ID
    
    // Update ticket status
    $updateStatusQuery = "UPDATE ticket SET status = '$statusUpdate' WHERE ticket_id = '$ticketId'";
    $updateStatusResult = mysqli_query($conn, $updateStatusQuery);
    
    if ($updateStatusResult) {
        if (!empty($adminReply)) {
            // Insert admin's reply into ticketresponse table
            $insertReplyQuery = "INSERT INTO ticketresponse (ticket_id, user_uniqueid, admin_uniqueid, message, created_at)
                                 VALUES ('$ticketId', NULL, '$adminUniqueId', '$adminReply', NOW())";
            $insertReplyResult = mysqli_query($conn, $insertReplyQuery);
            
            if (!$insertReplyResult) {
                $response = array('status' => 'error', 'message' => 'Failed to insert admin reply.');
            } else {
                $response = array('status' => 'success', 'message' => 'Admin reply and status update saved successfully.');
            }
        } else {
            $response = array('status' => 'success', 'message' => 'Status updated successfully.');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update status.');
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request.');
}

echo json_encode($response);
?>
