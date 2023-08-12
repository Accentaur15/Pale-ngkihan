<?php
session_start();
include_once('../php/config.php');

if(isset($_SESSION['unique_id'])){
    $unique_id = $_SESSION['unique_id'];
    
    if(isset($_GET['logout_id'])){
        $logout_id = mysqli_real_escape_string($conn, $_GET['logout_id']);
        if($logout_id === $unique_id){
            // Update online_status to 0
            $update_query = "UPDATE buyer_accounts SET online_status = 0 WHERE unique_id = '$unique_id'";
            mysqli_query($conn, $update_query);
            
            // Clear session data and destroy session
            session_unset();
            session_destroy();
            
            // Redirect to the login page
            header("Location: ../buyerlogin.php");
            exit();
        }
    }
    else {
        // User is logged in and not requesting to logout
        header("Location: buyermain.php");
        exit();
    }
}   
else {
    // User is not logged in
    header("Location: ../buyerlogin.php");
    exit();
}
?>
