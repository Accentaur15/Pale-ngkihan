<?php
session_start();
include_once('../php/config.php');
ini_set('log_errors', 1);
ini_set('error_log', '../error_log_file.log'); // Replace with the desired file path and name

if (empty($_SESSION['unique_id'])) {
  header("Location: sellerlogin.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userType = $_POST['user_type'];  // 'buyer' or 'seller'
    $uniqueId = $_SESSION['unique_id'];
    $message = $_POST['message'];
  
        // Debug: Log the received data
        //error_log("Received user_type: " . $userType);
       // error_log("Received unique_id: " . $uniqueId);
        //error_log("Received message: " . $message);
    // Insert the message into the support_messages table
    $insertQuery = "INSERT INTO support_messages (user_type, unique_id, message) VALUES ('$userType', '$uniqueId', '$message')";
    $insertResult = mysqli_query($conn, $insertQuery);
  
    if ($insertResult) {
      echo "Message sent successfully!";
    } else {
      echo "Failed to send message. Please try again.";
    }
  

}
?>
