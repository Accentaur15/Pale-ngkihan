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



?>