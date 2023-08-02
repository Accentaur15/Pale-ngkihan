<?php
session_start();
include_once('../php/config.php');

// Check if the request is coming from a valid form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the seller_id from the form data
  $seller_id = $_POST['sellerid'];

  // Validate the form data (e.g., check if all required fields are provided)
  if (
    isset($_POST['schedule_date']) && !empty($_POST['schedule_date']) &&
    isset($_POST['location']) && !empty($_POST['location']) &&
    isset($_POST['quantity_available']) && !empty($_POST['quantity_available']) &&
    isset($_POST['status']) && !empty($_POST['status']) &&
    isset($_POST['bidding_status']) && !empty($_POST['bidding_status'])
  ) {
    // Sanitize the input data to prevent SQL injection
    $schedule_date = mysqli_real_escape_string($conn, $_POST['schedule_date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $quantity_available = (float)$_POST['quantity_available'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $bidding_status = (int)$_POST['bidding_status'];
    $starting_bid = isset($_POST['starting_bid']) ? (float)$_POST['starting_bid'] : null;

    // Perform the database insertion
    $insertQuery = "INSERT INTO harvest_schedule (seller_id, date_scheduled, location, quantity_available, status, bidding_status, starting_bid)
                    VALUES ('$seller_id', '$schedule_date', '$location', $quantity_available, '$status', $bidding_status, $starting_bid)";

    if (mysqli_query($conn, $insertQuery)) {
      // If insertion is successful, return a success response
      $response = array("success" => true);
    } else {
      // If there's an error, return an error response
      $response = array("success" => false, "message" => "Error inserting data into the database.");
    }
  } else {
    // If required fields are missing, return an error response
    $response = array("success" => false, "message" => "Please fill all the required fields.");
  }
} else {
  // If the request method is not POST, return an error response
  $response = array("success" => false, "message" => "Invalid request method.");
}

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
