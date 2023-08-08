<?php
include_once('../php/config.php');
//error_log("Received Schedule ID: " . $_GET['schedule_id']);
//ini_set('log_errors', 1);
//ini_set('error_log', '../error_log_file.log'); // Replace with the desired file path and name
if (isset($_GET['schedule_id'])) {
    $scheduleId = $_GET['schedule_id'];
    $qry = mysqli_query($conn, "SELECT * FROM harvest_schedule WHERE id = '{$scheduleId}'");
    if (mysqli_num_rows($qry) > 0) {
        $schedule = mysqli_fetch_assoc($qry);
        // Send JSON response with appropriate headers
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'schedule' => $schedule]);
        exit; // Make sure to exit the script after sending the response
    } else {
        // Send JSON response with appropriate headers
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Schedule not found.']);
        exit; // Make sure to exit the script after sending the response
    }
} else {
    // Send JSON response with appropriate headers
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit; // Make sure to exit the script after sending the response
}
?>
