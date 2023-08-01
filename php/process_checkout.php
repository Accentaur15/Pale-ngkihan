<?php
session_start();
include_once('../php/config.php');
include_once('../php/cart_functions.php');

$unique_id = $_SESSION['unique_id'];
$qry = mysqli_query($conn, "SELECT * FROM buyer_accounts WHERE unique_id = '{$unique_id}'");

if (mysqli_num_rows($qry) > 0) {
    $row = mysqli_fetch_assoc($qry);
    if ($row) {
        $id = $row['id'];
    }
}

// Function to clear the cart for a specific buyer
function clearCart($conn, $buyer_id)
{
    $buyer_id = mysqli_real_escape_string($conn, $buyer_id);
    $delete_query = "DELETE FROM cart_list WHERE buyer_id = '$buyer_id'";
    mysqli_query($conn, $delete_query);
}

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
    $orderCode = 'ORD-' . $timestamp . '-' . $randomString;

    return $orderCode;
}

// Initialize error message variable
$error_message = '';

// Initialize response array
$response = array('status' => '', 'msg' => '');

// Initialize notification variables
$notification_title = "Order Placed";

// Process the Checkout Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $delivery_method = $_POST['delivery_method'];
    $delivery_address = mysqli_real_escape_string($conn, $_POST['delivery_address']);

    // Validate the form data (Add more validation as needed)
    if (empty($payment_method) || empty($delivery_method) || empty($delivery_address)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Fetch the seller IDs from the cart items
        $seller_ids = array();
        $cart_items_query = "SELECT DISTINCT p.seller_id FROM cart_list c INNER JOIN product_list p ON c.product_id = p.id WHERE c.buyer_id = '$id'";
        $cart_items_result = mysqli_query($conn, $cart_items_query);
        while ($cart_item = mysqli_fetch_assoc($cart_items_result)) {
            $seller_ids[] = $cart_item['seller_id'];
        }

        // Initialize an array to store the order IDs for each seller
        $seller_order_ids = array();

        foreach ($seller_ids as $seller_id) {
            // Calculate the total order amount for each seller
            $vtotal = $conn->query("SELECT sum(c.quantity * p.price) FROM `cart_list` c inner join product_list p on c.product_id = p.id where c.buyer_id = '{$id}' and p.seller_id = '$seller_id'")->fetch_array()[0];
            $vtotal = $vtotal > 0 ? $vtotal : 0;

            // Insert the order details into the "order_list" table for each seller
            $order_code = generateOrderCode();
            $order_status = 0;
            $insert_order_query = "INSERT INTO order_list (order_code, buyer_id, seller_id, total_amount, delivery_address, order_status, payment_method, delivery_method)
                                   VALUES ('$order_code', '$id', '$seller_id', '$vtotal', '$delivery_address', '$order_status', '$payment_method', '$delivery_method')";
            mysqli_query($conn, $insert_order_query);

            // Store the order ID for this seller in the array
            $seller_order_ids[$seller_id] = mysqli_insert_id($conn);
        }

        foreach ($seller_ids as $seller_id) {
            // Retrieve the order ID for this seller
            $order_id = $seller_order_ids[$seller_id];

            // Insert the cart items into the "order_items" table for this seller's order
            $cart_items_query = "SELECT c.product_id, c.quantity, p.price FROM cart_list c INNER JOIN product_list p ON c.product_id = p.id WHERE c.buyer_id = '$id' AND p.seller_id = '$seller_id'";
            $cart_items_result = mysqli_query($conn, $cart_items_query);
            while ($cart_item = mysqli_fetch_assoc($cart_items_result)) {
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];
                $price = $cart_item['price'];

                // Insert item into order_items table with the seller's order_id
                $insert_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price)
                                      VALUES ('$order_id', '$product_id', '$quantity', '$price')";
                mysqli_query($conn, $insert_item_query);
            }

            // Add notification message for successful order placement for this seller
            $order_code = mysqli_fetch_assoc(mysqli_query($conn, "SELECT order_code FROM order_list WHERE id = '$order_id'"))['order_code'];
            $notification_message = "Your order with Order Number $order_code has been successfully placed.\n";
            $insert_notification_query = "INSERT INTO notifications (order_list_id, buyer_id, order_code, notification_title, message) VALUES ('$order_id','$id', '$order_code','$notification_title', '$notification_message')";
            mysqli_query($conn, $insert_notification_query);
        }

        // Clear the cart after the order is placed
        clearCart($conn, $id);

        // Set success response
        $response['status'] = 'success';
    }
} else {
    $response['msg'] = 'Invalid Request';
}

// Send the JSON response
echo json_encode($response);
?>
