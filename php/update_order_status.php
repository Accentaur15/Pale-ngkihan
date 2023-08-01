<?php
// update_order_status.php

session_start();
include_once('../php/config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data
    $orderCode = $_POST['order_code']; // Assuming the order code is passed from the form
    $status = $_POST['status']; // The new order status selected in the form

    // Sanitize and validate the data (you should add appropriate sanitization and validation here)

    // Perform the database update
    // Assuming you have a table named "order_list" with columns "id" and "order_status"
    $updateQuery = "UPDATE `order_list` SET `order_status` = '{$status}' WHERE `order_code` = '{$orderCode}'";
    $result = $conn->query($updateQuery);

    if ($result) {
        // If the database update is successful, return a success response
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
