<?php
session_start();
include_once('../php/config.php');
ini_set('log_errors', 1);
ini_set('error_log', '../error_log_file.log'); // Replace with the desired file path and name
$unique_id = $_SESSION['unique_id'];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Ensure that the user is logged in and has a valid unique_id in the session

  // Get the schedule ID from the POST data
  $scheduleId = $_POST['schedule_id'];

  // Get the old image path from the database
  $oldImagePathQuery = mysqli_query($conn, "SELECT harvest_image FROM harvest_schedule WHERE id = $scheduleId");
  if ($oldImagePathQuery && mysqli_num_rows($oldImagePathQuery) > 0) {
    $row = mysqli_fetch_assoc($oldImagePathQuery);
    $oldImagePath = $row['harvest_image'];
  }

  // Check if the uploaded file is an image and if the user uploaded a product picture
  if (isset($_FILES['field_image']) && $_FILES['field_image']['error'] === UPLOAD_ERR_OK && strpos($_FILES['field_image']['type'], 'image') !== false) {
    // Continue with the rest of the code for handling the uploaded image
    $tmp_name = $_FILES['field_image']['tmp_name'];
    $file_name = $_FILES['field_image']['name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

    // Check if the file extension is allowed
    if (in_array(strtolower($file_ext), $allowed_extensions)) {
      // Create a directory for the seller's profile if it doesn't exist
      $profileDir = '../seller_profiles/' . $unique_id;
      if (!is_dir($profileDir)) {
        mkdir($profileDir, 0755, true);
      }

      // Generate a unique filename for the uploaded image
      $file_destination = $profileDir . '/' . uniqid('img_', true) . '.' . $file_ext;

      // If a new image is uploaded, delete the old image if it exists
      if (!empty($oldImagePath) && file_exists($oldImagePath)) {
        unlink($oldImagePath);
      }

      // Move the uploaded file to the desired directory
      if (move_uploaded_file($tmp_name, $file_destination)) {
        // Perform the database update

        // Define and initialize the variables for database update
        $scheduleDate = mysqli_real_escape_string($conn, $_POST['schedule_date']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $quantityAvailable = (int) $_POST['quantity_available'];
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $biddingStatus = (int) $_POST['bidding_status'];
        $startingBid = isset($_POST['starting_bid']) ? (float) $_POST['starting_bid'] : NULL;
        $typeofrice  = mysqli_real_escape_string($conn, $_POST['type_rice']);
        $updateQuery = mysqli_query($conn, "UPDATE harvest_schedule SET 
          date_scheduled = '$scheduleDate', 
          rice_type = '$typeofrice', 
          location = '$location', 
          quantity_available = $quantityAvailable, 
          status = '$status', 
          bidding_status = $biddingStatus, 
          starting_bid = " . ($startingBid !== NULL ? $startingBid : 'NULL') . ",
          harvest_image = '$file_destination'
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
        // If there's an error moving the uploaded file, return an error response
        $response = array("success" => false, "message" => "Error moving the uploaded file.");
        header('Content-Type: application/json');
        echo json_encode($response);
      }
    } else {
      // If the file extension is not allowed, return an error response
      $response = array("success" => false, "message" => "Invalid file extension. Only JPG, JPEG, PNG, and GIF files are allowed.");
      header('Content-Type: application/json');
      echo json_encode($response);
    }
  } else {
    // If the user did not upload a product picture, perform the database update without changing the image

    // Define and initialize the variables for database update
    $scheduleDate = mysqli_real_escape_string($conn, $_POST['schedule_date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $quantityAvailable = (int) $_POST['quantity_available'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $biddingStatus = (int) $_POST['bidding_status'];
    $typeofrice  = mysqli_real_escape_string($conn, $_POST['type_rice']);
    $startingBid = isset($_POST['starting_bid']) ? (float) $_POST['starting_bid'] : NULL;

    $updateQuery = mysqli_query($conn, "UPDATE harvest_schedule SET 
      date_scheduled = '$scheduleDate', 
      rice_type = '$typeofrice', 
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
  }
} else {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}
?>
