<?php
// Include your database connection and other necessary files here
session_start();
include_once('../php/config.php');

// Check if the schedule_id parameter is provided
if (isset($_GET['schedule_id'])) {
  $scheduleId = $_GET['schedule_id'];

  // Fetch the data for the given schedule_id
  $query = mysqli_query($conn, "SELECT * FROM harvest_schedule WHERE id = '{$scheduleId}'");
  if ($query && mysqli_num_rows($query) > 0) {
    $schedule = mysqli_fetch_assoc($query);
    // Return the data as JSON
    echo json_encode(['success' => true, 'schedule' => $schedule]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Schedule not found.']);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
