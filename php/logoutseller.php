<?php
session_start();
include_once('../php/config.php');
$value = $_GET['logout_id'];

if (isset($_SESSION['unique_id'])) {
    $unique_id = $_SESSION['unique_id'];

    // Update the online_status to 0 for the logged-out user
    $updateSql = mysqli_query($conn, "UPDATE seller_accounts SET online_status = 0 WHERE unique_id = '$unique_id'");

    // Unset and destroy the session
    session_unset();
    session_destroy();

    header("Location: ../seller/sellerlogin.php");
    exit();
} else {
    // User is not logged in
    header("Location: ../seller/sellerlogin.php");
    exit();
}
?>
