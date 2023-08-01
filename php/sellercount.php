<?php
// Count categories
$qryCategories = mysqli_query($conn, "SELECT COUNT(*) as categoryCount FROM category_list");
$categoryCount = 0;

if ($qryCategories) {
  $rowCategories = mysqli_fetch_assoc($qryCategories);
  if ($rowCategories) {
    $categoryCount = $rowCategories['categoryCount'];
  }
}


// Assuming you have already established a database connection

$qryProducts = mysqli_query($conn, "SELECT COUNT(*) as productCount FROM product_list WHERE seller_id = '$sellerid'");
$productCount = 0;

if ($qryProducts) {
  $rowProducts = mysqli_fetch_assoc($qryProducts);
  if ($rowProducts) {
    $productCount = $rowProducts['productCount'];
  }
}








?>