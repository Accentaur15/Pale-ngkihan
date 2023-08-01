<?php
session_start();
include_once('../php/config.php');

if (isset($_POST['product_delete'])) {
    $delete_id = $_POST['product_delete'];

    // Get the product image path associated with the product ID
    $imageQuery = "SELECT product_image FROM product_list WHERE id = '$delete_id'";
    $imageResult = mysqli_query($conn, $imageQuery);
    $row = mysqli_fetch_assoc($imageResult);
    $product_image = $row['product_image'];

    // Delete the image from the database
    unlink('../'.$product_image);
    $query = "DELETE FROM product_list WHERE id = '$delete_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        $_SESSION['message'] = "Product Deleted Successfully";
        header('Location: ../seller/productlist.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: ../seller/productlist.php');
        exit(0);
    }
}
?>
