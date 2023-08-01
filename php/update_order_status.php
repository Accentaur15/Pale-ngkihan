<?php
// update_order_status.php


ini_set('display_errors', 1);
include_once('../php/config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data
    $orderCode = $_POST['order_code'];
    $status = $_POST['status'];

    // Sanitize and validate the data (you should add appropriate sanitization and validation here)

    // Perform the database update
    // Assuming you have a table named "order_list" with columns "id" and "order_status"
    $updateQuery = "UPDATE `order_list` SET `order_status` = '{$status}' WHERE `order_code` = '{$orderCode}'";
    $result = $conn->query($updateQuery);

    if ($result) {
        // If the database update is successful, retrieve additional information from the "notifications" table
        $selectQuery = "SELECT order_list_id, buyer_id FROM `notifications` WHERE `order_code` = '{$orderCode}'";
        $notificationResult = $conn->query($selectQuery);

        if ($notificationResult && $notificationResult->num_rows > 0) {
            $notificationData = $notificationResult->fetch_assoc();

            // Additional information retrieved from the "notifications" table
            $orderListId = $notificationData['order_list_id'];
            $buyerId = $notificationData['buyer_id'];
        } else {
            // If there is no corresponding notification data, you can set default values or provide an appropriate message.
            // In this example, we assume default values.
            $orderListId = '';
            $buyerId = '';
        }

        // Set the notification title based on the selected order status
        switch ($status) {
            case 0:
                $notificationTitle = "Order Status: Pending";
                break;
            case 1:
                $notificationTitle = "Order Status: Confirmed";
                break;
            case 2:
                $notificationTitle = "Order Status: Packed";
                break;
            case 3:
                $notificationTitle = "Order Status: Out for Delivery";
                break;
            case 4:
                $notificationTitle = "Order Status: Delivered";
                break;
            case 5:
                $notificationTitle = "Order Status: Cancelled";
                break;
            default:
                $notificationTitle = "Order Status Updated";
                break;
        }

        // Assuming you have a table named "notifications" to store notifications
        $notificationMessage = "The order with code '{$orderCode}' has been updated to status '{$status}'.";

        // Perform the database insert to create the notification
        $insertQuery = "INSERT INTO `notifications` (order_list_id, buyer_id, notification_title, message, order_code) VALUES ('$orderListId', '$buyerId', '$notificationTitle', '$notificationMessage', '$orderCode')";
        $insertResult = $conn->query($insertQuery);

        if (!$insertResult) {
            echo "<script>console.error('Error inserting notification for order code \'{$orderCode}\'.');</script>";
        }

        // Prepare the response
        $response = array(
            'success' => true,
            'message' => 'Order status updated successfully!',
        );
    } else {
        // If the database update fails, return an error response
        $response = array(
            'success' => false,
            'message' => 'Failed to update order status. Please try again later.',
        );
    }

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
