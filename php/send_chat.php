<?php
session_start();
include_once "../php/config.php"; // Include the database configuration

function sendMessage($conn, $outgoing_id, $incoming_id, $message) {
    // Make sure to include the proper error handling for the database connection
    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }

    // Escape and sanitize input
    $outgoing_id = mysqli_real_escape_string($conn, $outgoing_id);
    $incoming_id = mysqli_real_escape_string($conn, $incoming_id);
    $message = mysqli_real_escape_string($conn, $message);

    // Check if the message is not empty
    if (!empty($message)) {
        // Prepare the SQL query
        $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                VALUES ('$incoming_id', '$outgoing_id', '$message')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            return true; // Message sent successfully
        } else {
            return false; // Error sending message
        }
    }

    return false; // Empty message, not sent
}

// Get outgoing_id, incoming_id, and message from your code
$outgoing_id = $_SESSION['unique_id'];
$incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
$message = mysqli_real_escape_string($conn, $_POST['message']);

// Call the sendMessage function
if (sendMessage($conn, $outgoing_id, $incoming_id, $message)) {
    echo "Message sent successfully!";
} else {
    echo "Error sending message.";
}
?>
