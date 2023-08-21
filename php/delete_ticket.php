<?php
session_start();
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticketId'];
    
    // Perform the deletion query
    $deleteQuery = "DELETE FROM ticket WHERE ticket_id = '$ticketId'";
    
    if ($conn->query($deleteQuery) === TRUE) {
        $response = array('status' => 'success', 'message' => 'Ticket deleted successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Error deleting ticket');
    }
    
    echo json_encode($response);
} else {
    header("HTTP/1.0 405 Method Not Allowed");
}
?>
