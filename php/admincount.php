<?php
// Count buyer accounts
$qryBuyer = mysqli_query($conn, "SELECT COUNT(*) as buyerCount FROM buyer_accounts");
$buyerCount = 0;

if ($qryBuyer) {
  $rowBuyer = mysqli_fetch_assoc($qryBuyer);
  if ($rowBuyer) {
    $buyerCount = $rowBuyer['buyerCount'];
  }
}

// Count seller accounts
$qrySeller = mysqli_query($conn, "SELECT COUNT(*) as sellerCount FROM seller_accounts");
$sellerCount = 0;

if ($qrySeller) {
  $rowSeller = mysqli_fetch_assoc($qrySeller);
  if ($rowSeller) {
    $sellerCount = $rowSeller['sellerCount'];
  }
}

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

$qryProducts = mysqli_query($conn, "SELECT COUNT(*) as productCount FROM product_list");
$productCount = 0;

if ($qryProducts) {
  $rowProducts = mysqli_fetch_assoc($qryProducts);
  if ($rowProducts) {
    $productCount = $rowProducts['productCount'];
  }
}

$qryPendingorder = mysqli_query($conn, "SELECT COUNT(*) as pendingCount FROM order_list WHERE order_status = 0");
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

$qryHarvestSchedule = mysqli_query($conn, "SELECT COUNT(*) as HSCount FROM harvest_schedule");
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