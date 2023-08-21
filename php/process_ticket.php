<?php
session_start();
include_once('../php/config.php');
ini_set('log_errors', 1);
ini_set('error_log', '../error_log_file.log'); // Replace with the desired file path and name

// Retrieve form data
$category = $_POST['category'];
$urgency = $_POST['urgency'];
$subject = $_POST['subject'];
$message = $_POST['ticketMessage'];
$status = 'open'; // Set the default status to 'Open'

// Get the unique ID of the user (seller)
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
    header("Location: sellerlogin.php");
    exit();
}

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO ticket (user_uniqueid, category, urgency_level, status, subject, description) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $unique_id, $category, $urgency, $status, $subject, $message);

if ($stmt->execute()) {
    // Ticket added successfully
    echo json_encode(['status' => 'success']);
} else {
    // Error adding ticket
    echo json_encode(['status' => 'error', 'message' => 'Failed to add ticket. Please try again later.']);
}

$stmt->close();
$conn->close();
?>
