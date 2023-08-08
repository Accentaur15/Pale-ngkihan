<?php
// update_order_status.php

include_once('../php/config.php');
ini_set('log_errors', 1);
ini_set('error_log', '../errorlog.txt');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data
    $orderCode = $_POST['order_code'];
    $status = $_POST['status'];

    // Perform the database update
    // Assuming you have a table named "order_list" with columns "id" and "order_status"
    $updateQuery = "UPDATE `order_list` SET `order_status` = ? WHERE `order_code` = ?";
    $updateStatement = $conn->prepare($updateQuery);
    $updateStatement->bind_param('is', $status, $orderCode);

    if ($updateStatement->execute()) {
        $updateStatement->close();

        // If the database update is successful, retrieve additional information from the "notifications" table
        $selectQuery = "SELECT order_list_id, buyer_id FROM `notifications` WHERE `order_code` = ?";
        $selectStatement = $conn->prepare($selectQuery);
        $selectStatement->bind_param('s', $orderCode);
        $selectStatement->execute();
        $notificationResult = $selectStatement->get_result();
        $selectStatement->close();

        if ($notificationResult && $notificationResult->num_rows > 0) {
            $notificationData = $notificationResult->fetch_assoc();

            // Additional information retrieved from the "notifications" table
            $orderListId = $notificationData['order_list_id'];
            $buyerId = $notificationData['buyer_id'];
        } 

        // Set the notification title based on the selected order status
        switch ($status) {
            case 0:
                $notificationTitle = "Order Status: Pending";
                $messageoutput = "Order Status: Pending";
                break;
            case 1:
                $notificationTitle = "Order Status: Confirmed";
                $messageoutput  = "Order Status: Confirmed";
                break;
            case 2:
                $notificationTitle = "Order Status: Packed";
                $messageoutput  = "Order Status: Packed";
                break;
            case 3:
                $notificationTitle = "Order Status: Out for Delivery";
                $messageoutput  = "Order Status: Out for Delivery";
                break;
            case 4:
                $notificationTitle = "Order Status: Delivered";
                $messageoutput  = "Order Status: Delivered";
                break;
            case 5:
                $notificationTitle = "Order Status: Cancelled";
                $messageoutput  = "Order Status: Cancelled";
                break;
            default:
                $notificationTitle = "Order Status Updated";
                $messageoutput  = "Order Status Updated";
                break;
        }

        // Assuming you have a table named "notifications" to store notifications
        $notificationMessage = "The order with code '{$orderCode}' has been updated to status '{$messageoutput}'.";

        // Perform the database insert to create the notification using prepared statements
        $insertQuery = "INSERT INTO `notifications` (order_list_id, buyer_id, notification_title, message, order_code) VALUES (?, ?, ?, ?, ?)";
        $insertStatement = $conn->prepare($insertQuery);
        $insertStatement->bind_param('iisss', $orderListId, $buyerId, $notificationTitle, $notificationMessage, $orderCode);

        if (!$insertStatement->execute()) {
            // If the database insert fails, return an error response
            $response = array(
                'success' => false,
                'message' => 'Failed to update order status. Please try again later.',
            );

            // Log the error
            $error_message = "Error inserting notification for order code '{$orderCode}'.";
            error_log($error_message);
        } else {
            // Prepare the response
            $response = array(
                'success' => true,
                'message' => 'Order status updated successfully!',
            );
        }

        $insertStatement->close();
    } else {
        // If the database update fails, return an error response
        $response = array(
            'success' => false,
            'message' => 'Failed to update order status. Please try again later.',
        );

        // Log the error
        $error_message = "Error updating order status for code '{$orderCode}'.";
        error_log($error_message);
    }

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
