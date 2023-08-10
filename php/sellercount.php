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


$qryPendingorder = mysqli_query($conn, "SELECT COUNT(*) as pendingCount FROM order_list WHERE order_status = 0 AND seller_id = '$sellerid'");
$pendingCount = 0;

if ($qryPendingorder) {
  $rowPending = mysqli_fetch_assoc($qryPendingorder);
  // Checking the correct variable
  if ($rowPending) {
    $pendingCount = $rowPending['pendingCount'];
  }
} else {
  // Handle the error if the query fails
  // For example: echo "Error: " . mysqli_error($conn);
}

$qryHarvestSchedule = mysqli_query($conn, "SELECT COUNT(*) as HSCount FROM harvest_schedule WHERE  seller_id = '$sellerid'");
$HarvestScheduleCount = 0;

if ($qryHarvestSchedule) {
  $rowHarvestSchedule = mysqli_fetch_assoc($qryHarvestSchedule);
  // Checking the correct variable
  if ($rowHarvestSchedule) {
    $HarvestScheduleCount = $rowHarvestSchedule['HSCount'];
  }
} else {
  // Handle the error if the query fails
  // For example: echo "Error: " . mysqli_error($conn);
}








?>