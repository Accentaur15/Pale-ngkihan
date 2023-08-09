<?php
session_start();
include_once('../php/config.php');
ini_set('log_errors', 1);
ini_set('error_log', '../error_log_file.log'); // Replace with the desired file path and name
function generateOrderCode()
{
    // Generate a random string using letters and numbers
    $length = 8;
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    // Combine the random string with the current timestamp
    $timestamp = time(); // Current Unix timestamp
    $orderCode = 'BID-' . $timestamp . '-' . $randomString;

    return $orderCode;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Get the harvest ID from the request
    $harvest_id = $_GET['harvest_id'];

    // Perform a check to ensure that the buyer has not already added this schedule
    $unique_id = $_SESSION['unique_id'];
    $order_code = generateOrderCode();
    // Retrieve the buyer's ID from the buyer_accounts table using unique_id
    $selectQuery = "SELECT id FROM buyer_accounts WHERE unique_id = '$unique_id'";
    $result = mysqli_query($conn, $selectQuery);
    $row = mysqli_fetch_assoc($result);
    $buyer_id = $row['id'];

    $checkQuery = mysqli_query($conn, "SELECT * FROM buyer_bids WHERE buyer_id = '$buyer_id' AND harvest_schedule_id = '$harvest_id'");

    if (mysqli_num_rows($checkQuery) > 0) {
        // Schedule is already added
        echo json_encode(array('error' => 'Schedule already added.'));
    } else {
        // Insert the schedule into the buyer_schedules table
        $insertQuery = mysqli_query($conn, "INSERT INTO buyer_bids (buyer_id, harvest_schedule_id, bid_code, bid_status) VALUES ('$buyer_id', '$harvest_id', '$order_code', 'pending')");
        $notificationMessage = "The bid order with the code '" . $order_code . "' has been succesfully been placed";
        $notificationTitle = "Bid Status: Bid Placed";
        // Prepare the notification insert query
        $insertnotificationQuery = "INSERT INTO `notifications` (buyer_id, notification_title, message, order_code) VALUES (?, ?, ?, ?)";
        $insertnotificationStmt = mysqli_prepare($conn, $insertnotificationQuery);
        mysqli_stmt_bind_param($insertnotificationStmt, "isss", $buyer_id, $notificationTitle, $notificationMessage, $order_code);
        $insertnotificationResult = mysqli_stmt_execute($insertnotificationStmt);

        if ($insertQuery && $insertnotificationResult) {
            // Successful insertion for "Get Schedule"
            echo json_encode(array('message' => 'Schedule added successfully.'));
        } else {
            // Error in insertion for "Get Schedule"
            echo json_encode(array('error' => 'Error adding schedule.'));
        }

        // Close the prepared statement
        mysqli_stmt_close($insertnotificationStmt);
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $unique_id = $_SESSION['unique_id'];
    $harvest_id = $_POST['harvest_id'];
    $bidAmount = $_POST['bidAmount'];
        $order_code = generateOrderCode();
    // Retrieve the buyer's ID from the buyer_accounts table using unique_id
    $selectQuery = "SELECT id FROM buyer_accounts WHERE unique_id = '$unique_id'";
    $result = mysqli_query($conn, $selectQuery);
    $row = mysqli_fetch_assoc($result);
    $buyer_id = $row['id'];

    // Perform a check to ensure that the buyer has not already added this schedule
    $checkQuery = mysqli_query($conn, "SELECT * FROM buyer_bids WHERE buyer_id = '$buyer_id' AND harvest_schedule_id = '$harvest_id'");
    if (mysqli_num_rows($checkQuery) > 0) {
        // Schedule is already added
        echo json_encode(array('message' => 'Schedule already added.'));
        exit();
    }


    // Insert the bid amount into the buyer_bids table
    $insertBidQuery = mysqli_query($conn, "INSERT INTO buyer_bids (harvest_schedule_id, buyer_id, bid_amount, bid_code, bid_status) VALUES ('$harvest_id', '$buyer_id', '$bidAmount', '$order_code', 'pending')");
    $notificationMessage = "The bid order with the code '" . $order_code . "' that has a bid amount of '₱" . $bidAmount . "' has been succesfully been placed";
    $notificationTitle = "Bid Status: Bid Placed";
    // Prepare the notification insert query
    $insertnotificationQuery = "INSERT INTO `notifications` (buyer_id, notification_title, message, order_code) VALUES (?, ?, ?, ?)";
    $insertnotificationStmt = mysqli_prepare($conn, $insertnotificationQuery);
    mysqli_stmt_bind_param($insertnotificationStmt, "isss", $buyer_id, $notificationTitle, $notificationMessage, $order_code);
    $insertnotificationResult = mysqli_stmt_execute($insertnotificationStmt);

    if ($insertBidQuery && $insertnotificationResult) {
        // Successful insertion for "Submit Bid"
        echo json_encode(array('message' => 'Bid submitted successfully.'));
    } else {
        // Error in insertion for "Submit Bid"
        echo json_encode(array('error' => 'Error submitting bid.'));
    }

     // Close the prepared statement
     mysqli_stmt_close($insertnotificationStmt);
} else {
    // Handle invalid request method
    echo json_encode(array('error' => 'Invalid request method.'));
}
?>
