<?php
session_start();
include_once('../php/config.php');
$unique_id = $_SESSION['unique_id'];
if (empty($unique_id)) {
    header("Location: ../seller/sellerlogin.php");
}


$seller_id = $_POST['sellerid'];
$productname = $_POST['productname'];
$productprice = $_POST['productprice'];
$productunit = isset($_POST['unit']) ? $_POST['unit'] : '';
$productcategory = isset($_POST['productcategory']) ? $_POST['productcategory'] : '';
$quantity = $_POST['quantity'];
$status = isset($_POST['status']) ? $_POST['status'] : '';
$description = $_POST['description'];
$productpicture = $_FILES['productpicture'];

if (!empty($seller_id) && !empty($productname) && !empty($productprice) && !empty($productunit) && !empty($productcategory) && !empty($quantity) && !empty($status) && !empty($description) && !empty($productpicture)) {

    // Check if the user uploaded the product picture
    if (isset($_FILES['productpicture'])) {
        $productpictureName = $_FILES['productpicture']['name'];
        $productpictureTmpName = $_FILES['productpicture']['tmp_name'];
        $productpictureError = $_FILES['productpicture']['error'];
        $productpictureType = $_FILES['productpicture']['type'];

        // Check if the uploaded file is an image
        if (strpos($productpictureType, 'image') !== false) {
            // Create a unique folder for each user
            $userFolder = '../seller_profiles/' . $unique_id . '/';
            $userproperFolder = 'seller_profiles/' . $unique_id . '/';


            // Generate a unique name for the product picture
            $productpictureExtension = pathinfo($productpictureName, PATHINFO_EXTENSION);
            $newproductpictureName = $productname.'.' . $productpictureExtension;
            $productpictureDestination = $userproperFolder . $newproductpictureName;
            $productpictureDestination2 = $userFolder . $newproductpictureName;

            // Move the uploaded product picture to the user's folder
            move_uploaded_file($productpictureTmpName, $productpictureDestination2);
        } else {
            echo "Product Picture must be an image file.";
        }
    } else {
        echo "Please select a Product Picture.";
    }

    // Insert data into the product_list table
    $insertQuery = "INSERT INTO product_list (seller_id, product_name, price, unit, category_id, quantity, status, product_description, product_image)
                                    VALUES ('$seller_id', '$productname', '$productprice', '$productunit', '$productcategory', '$quantity', '$status', '$description', '$productpictureDestination')";

    if (mysqli_query($conn, $insertQuery)) {
        // Insert successful
        $_SESSION['message'] = "Product Added Succesfully";
        echo "updated";
        exit(0);
    } else {
        echo "Error: " . mysqli_error($conn);
    }



} else {
    echo "All Input Fields are Required";
}
?>