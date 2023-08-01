<?php
session_start();
include_once('../php/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'add_quantity') {
            addQuantity($conn);
        } elseif ($action === 'subtract_quantity') {
            subtractQuantity($conn);
        } elseif ($action === 'delete_cart_item') {
            deleteCartItem($conn);
        }
    }
}

function addQuantity($conn)
{
    if (isset($_POST['cart_id']) && isset($_POST['quantity']) && isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $cart_id = $_POST['cart_id'];
        $quantity = (int)$_POST['quantity'];

        // Check if the cart item belongs to the current buyer
        $buyer_id = $_SESSION['id'];
        $result = mysqli_query($conn, "SELECT * FROM cart_list WHERE id = '{$cart_id}' AND buyer_id = '{$buyer_id}'");

        if ($result->num_rows > 0) {
            // Update the quantity for the cart item
            mysqli_query($conn, "UPDATE cart_list SET quantity = '{$quantity}' WHERE id = '{$cart_id}'");
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Invalid cart item or unauthorized action.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Invalid request parameters.']);
    }
}

function subtractQuantity($conn)
{
    if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
        $cart_id = $_POST['cart_id'];
        $quantity = (int)$_POST['quantity'];
        
        // Check if the cart item belongs to the current buyer
        $buyer_id = $_SESSION['id'];
        $result = mysqli_query($conn, "SELECT * FROM cart_list WHERE id = '{$cart_id}' AND buyer_id = '{$buyer_id}'");

        if ($result->num_rows > 0) {
            // Check if the quantity is greater than 1 before subtracting
            if ($quantity >= 1) {
                // Update the quantity for the cart item
                mysqli_query($conn, "UPDATE cart_list SET quantity = quantity - 1 WHERE id = '{$cart_id}'");
                echo json_encode(['status' => 'success']);
            } else {
                // If the quantity is 1, show an error but don't perform the subtraction
                echo json_encode(['status' => 'error', 'msg' => 'Minimum quantity reached.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Invalid cart item or unauthorized action.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Invalid request parameters.']);
    }
}


function deleteCartItem($conn)
{
    if (isset($_POST['cart_id']) && isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $cart_id = $_POST['cart_id'];

        // Check if the cart item belongs to the current buyer
        $buyer_id = $_SESSION['id'];
        $result = mysqli_query($conn, "SELECT * FROM cart_list WHERE id = '{$cart_id}' AND buyer_id = '{$buyer_id}'");

        if ($result->num_rows > 0) {
            // Delete the cart item
            mysqli_query($conn, "DELETE FROM cart_list WHERE id = '{$cart_id}'");
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Invalid cart item or unauthorized action.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Invalid request parameters.']);
    }
}
?>
