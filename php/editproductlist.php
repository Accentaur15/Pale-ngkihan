<?php
session_start();
include_once('../php/config.php');
$unique_id = $_SESSION['unique_id'];
if (empty($unique_id)) {
    header("Location: ../seller/sellerlogin.php");
}


$product_id = $_POST['product_id'];
$productname = $_POST['productname'];
$productprice = $_POST['productprice'];
$productunit = $_POST['unit'];
$productcategory =$_POST['productcategory'];
$quantity = $_POST['quantity'];
$status = $_POST['status'];
$description = $_POST['description'];
$productpicture = $_FILES['productpicture'];


// Fetch the data from the database
$sql = mysqli_query($conn, "SELECT pl.product_image, sa.unique_id
                            FROM product_list pl
                            INNER JOIN seller_accounts sa ON pl.seller_id = sa.id
                            WHERE pl.id = '{$product_id}'");
$row = mysqli_fetch_assoc($sql);
$unique_id = $row['unique_id'];
$existingproductimage = $row['product_image'];



    // Check if the user uploaded the product picture
    if (isset($_FILES['productpicture'])) {
        $productpictureName = $_FILES['productpicture']['name'];
        $productpictureTmpName = $_FILES['productpicture']['tmp_name'];
        $productpictureError = $_FILES['productpicture']['error'];
        $productpictureType = $_FILES['productpicture']['type'];

        // Check if the uploaded file is an image
        if (strpos($productpictureType, 'image') !== false) {
            // Remove the existing product picture if it exists
            if (!empty($existingproductimage) && file_exists($existingproductimage)) {
                unlink('../' . $existingproductimage);
            }
            // Generate a unique name for the product picture
            $productpictureExtension = pathinfo($productpictureName, PATHINFO_EXTENSION);
            $newproductpictureName = $productname.'.' . $productpictureExtension;
            $userFolder = '../seller_profiles/' . $unique_id . '/';
            $userproperFolder = 'seller_profiles/' . $unique_id . '/';
            $productpictureDestination = $userFolder . $newproductpictureName;
            $productpictureDestination2 = $userproperFolder . $newproductpictureName;
           

            // Move the uploaded product picture to the user's folder
            move_uploaded_file($productpictureTmpName, $productpictureDestination);
            $updateimage = "UPDATE product_list SET product_image = '$productpictureDestination2' WHERE id = '{$product_id}'";
            $result = mysqli_query($conn, $updateimage);
            if ($result) {
                // Query executed successfully
                // Perform any additional actions or redirect as needed
                
            } 
        } else {
            echo "Product Picture must be an image file.";
        }
    } else {
        echo "Please select a Product Picture.";
    }

    // Insert data into the product_list table
    $updateQuery = "UPDATE product_list SET
    product_name = '$productname',
    price = '$productprice',
    unit = '$productunit',
    category_id = '$productcategory',
    quantity = '$quantity',
    status = '$status',
    product_description = '$description'
    WHERE id = '{$product_id}'";

if (mysqli_query($conn, $updateQuery)) {
    // Update successful
    $_SESSION['message'] = "Product Updated Successfully";
    echo "updated";
    exit(0);
} else {
    echo "Error: " . mysqli_error($conn);
}





?>