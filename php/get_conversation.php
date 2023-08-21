<?php
// Replace with your database connection code
include_once('../php/config.php');

// Retrieve conversation history based on the provided ticket ID
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticketId'];

    $query = "SELECT * FROM ticketresponse WHERE ticket_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ticketId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $conversationData = array();
        while ($row = $result->fetch_assoc()) {
            $conversationData[] = $row;
        }
        echo json_encode(array("status" => "success", "data" => $conversationData));
    } else {
        echo json_encode(array("status" => "error", "message" => "No conversation history found."));
    }

    $stmt->close();
    $conn->close();
}
?>
