<?php
session_start();
include_once('../php/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_cancel'])) {
    $scheduleId = $_POST['product_cancel'];

    // Validate the schedule ID (you might want to add more validation here)
    if (!empty($scheduleId)) {
        // Update the bid status to 'canceled' in the database
        $query = "UPDATE buyer_bids SET bid_status = 'canceled' WHERE harvest_schedule_id = '{$scheduleId}'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to the page where the cancel button was clicked
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Handle database error
            echo "Error updating bid status: " . mysqli_error($conn);
        }
    }
} else {
    // Invalid request or missing parameters
    echo "Invalid request";
}
?>
