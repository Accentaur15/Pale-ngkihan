<?php
// Include your database connection code or config here
include_once('../php/config.php');
ini_set('log_errors', 1);
ini_set('error_log', '../errorlog.txt');
// Function to handle updating the bid status in the database
function updateBidStatus($conn, $bidId, $newStatus) {
    // Sanitize input to prevent SQL injection
    $bidId = mysqli_real_escape_string($conn, $bidId);
    $newStatus = mysqli_real_escape_string($conn, $newStatus);

    // Construct the query to update the bid status
    $query = "UPDATE buyer_bids SET bid_status = '{$newStatus}' WHERE id = '{$bidId}'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

// Function to update the status of a harvest schedule to "completed"
function updateHarvestScheduleStatus($conn, $harvestScheduleId) {
    // Sanitize input to prevent SQL injection
    $harvestScheduleId = mysqli_real_escape_string($conn, $harvestScheduleId);

    // Construct the query to update the status of the harvest schedule
    $query = "UPDATE harvest_schedule SET status = 'completed' WHERE id = '{$harvestScheduleId}'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}



// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the bid_id and action parameters are provided
    if (isset($_POST['bid_id']) && isset($_POST['action']) && isset($_POST['harvest_schedule_id'])) {
        $bidId = $_POST['bid_id'];
        $action = $_POST['action'];
        $harvestScheduleId = $_POST['harvest_schedule_id'];

        // Handle the different actions
        switch ($action) {
            case 'accept':
                if (updateBidStatus($conn, $bidId, 'accepted') && updateHarvestScheduleStatus($conn, $harvestScheduleId)) {
                    $message = "Bid accepted successfully. Harvest schedule status updated.";
                    $success = true;
                } else {
                    $message = "Failed to accept the bid or update the harvest schedule status.";
                    $success = false;
                }
                break;
            case 'reject':
                    if (updateBidStatus($conn, $bidId, 'rejected')) {
                        $message = "Bid rejected successfully.";
                        $success = true;
                    } else {
                        $message = "Failed to reject the bid.";
                        $success = false;
                    }
                    break;
            case 'cancel':
                if (updateBidStatus($conn, $bidId, 'canceled')) {
                    $message = "Bid canceled successfully.";
                    $success = true;
                } else {
                    $message = "Failed to cancel the bid.";
                    $success = false;
                }
                break;
            default:
                $message = "Invalid action.";
                $success = false;
        }

        // Return a JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'message' => $message]);
    } else {
        // Handle missing parameters
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    }
} else {
    // Handle non-POST requests
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
