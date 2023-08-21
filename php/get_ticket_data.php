<?php
// Include your database connection and necessary functions
include_once('../php/config.php');

if (isset($_POST['ticketId'])) {
    $ticketId = $_POST['ticketId'];
    
    // Query the database to retrieve ticket data
    $query = "SELECT * FROM ticket WHERE ticket_id = '{$ticketId}'";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $ticketData = $result->fetch_assoc();
        
        // Return the retrieved data as JSON
        echo json_encode(['status' => 'success', 'data' => $ticketData]);
    } else {
        // No data found for the given ticket ID
        echo json_encode(['status' => 'error']);
    }
} else {
    // Invalid or missing parameters
    echo json_encode(['status' => 'error']);
}
?>
