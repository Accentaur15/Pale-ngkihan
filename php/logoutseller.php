<?php
session_start();
include_once('../php/config.php');
$value = $_GET['logout_id'];
echo $value;
if (isset($_SESSION['unique_id'])) {
    $unique_id = $_SESSION['unique_id'];

            session_unset();
            session_destroy();
            header("Location: ../seller/sellerlogin.php");
            exit();
        }

 else {
    // User is not logged in
    header("Location: ../seller/sellerlogin.php");
    exit();
}
?>
