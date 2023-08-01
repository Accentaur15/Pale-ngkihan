<?php
session_start();
include_once('../php/config.php');

// Check if the user is logged in
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
    header("Location: ../buyerlogin.php");
    exit;
}

// Retrieve the buyer_id and other details using the unique_id
$qry = mysqli_query($conn, "SELECT id FROM buyer_accounts WHERE unique_id = '{$unique_id}'");

if (mysqli_num_rows($qry) > 0) {
    $row = mysqli_fetch_assoc($qry);
    if ($row) {
        $buyer_id = $row['id'];
    }
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the same product with the same buyer already exists in the cart
    $existing_item_query = "SELECT id, quantity FROM cart_list WHERE buyer_id = ? AND product_id = ?";
    $stmt = $conn->prepare($existing_item_query);
    $stmt->bind_param("ii", $buyer_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update the quantity of the existing item in the cart
        $stmt->bind_result($existing_item_id, $existing_item_quantity);
        $stmt->fetch();
        $new_quantity = $existing_item_quantity + $quantity;

        // Update the quantity of the existing item in the cart
        $update_query = "UPDATE cart_list SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $new_quantity, $existing_item_id);
        $stmt->execute();

        // Prepare the response
        $response = array(
            'status' => 'success',
            'message' => 'Product quantity has been updated in the cart.',
        );
    } else {
        // Insert a new item into the cart
        $insert_query = "INSERT INTO cart_list (buyer_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iii", $buyer_id, $product_id, $quantity);
        $stmt->execute();

        // Prepare the response
        $response = array(
            'status' => 'success',
            'message' => 'Product has been added to the cart.',
        );
    }

    // Close the prepared statement
    $stmt->close();

    // Return the response as JSON
    echo json_encode($response);
}
?>
