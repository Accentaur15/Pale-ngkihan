<?php
// cancel_order.php

require_once('../php/config.php');

if (isset($_POST['id']) && $_POST['id'] > 0) {
    $order_id = $_POST['id'];

    // Check if the order exists and its status is 'Pending' (status code: 0)
    $order_query = $conn->query("SELECT * FROM `order_list` WHERE id = '$order_id' AND order_status = 0");

    if ($order_query->num_rows > 0) {
        $order_data = $order_query->fetch_assoc();
        $order_code = $order_data['order_code'];

        // Update the order status to 'Cancelled' (status code: 5)
        $update_query = $conn->query("UPDATE `order_list` SET order_status = 5 WHERE id = '$order_id'");
        if ($update_query) {
            // Add notification for order cancellation
            $notification_title = "Order Cancelled";
            $notification_message = "Your order with Order Number $order_code has been cancelled.";
            $insert_notification_query = "INSERT INTO notifications (buyer_id, notification_title, message, order_code) VALUES ('{$order_data['buyer_id']}', '$notification_title', '$notification_message','$order_code')";
            mysqli_query($conn, $insert_notification_query);

            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to cancel the order.'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Order not found or cannot be cancelled.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid order ID.'));
}
?>
