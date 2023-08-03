<?php
session_start();
include_once('config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Ensure that the user is logged in and has a valid unique_id in the session
  if (empty($_SESSION['unique_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
  }

  // Get the schedule ID from the POST data
  $scheduleId = $_POST['schedule_id'];

  // Validate and sanitize other form data (e.g., schedule_date, location, quantity_available, status, bidding_status, starting_bid)
  $scheduleDate = mysqli_real_escape_string($conn, $_POST['schedule_date']);
  $location = mysqli_real_escape_string($conn, $_POST['location']);
  $quantityAvailable = (int) $_POST['quantity_available'];
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $biddingStatus = (int) $_POST['bidding_status'];
  $startingBid = isset($_POST['starting_bid']) ? (float) $_POST['starting_bid'] : NULL;

  // Validate the data and check for any other necessary conditions before proceeding with the update

  // Update the harvest_schedule record in the database
  $updateQuery = mysqli_query($conn, "UPDATE harvest_schedule SET 
      date_scheduled = '$scheduleDate', 
      location = '$location', 
      quantity_available = $quantityAvailable, 
      status = '$status', 
      bidding_status = $biddingStatus, 
      starting_bid = " . ($startingBid !== NULL ? $startingBid : 'NULL') . " 
    WHERE id = $scheduleId");

  if ($updateQuery) {
    $response = array(
      'success' => true,
      'message' => 'Harvest schedule has been updated successfully.'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
  } else {
    $response = array(
      'success' => false,
      'message' => 'Error updating the harvest schedule.'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
  }
} else {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}
?>
