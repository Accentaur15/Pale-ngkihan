<?php
session_start();
include_once('../php/config.php');

if(isset($_POST['buyer_delete'])){
    $delete_id = $_POST['buyer_delete'];

    // Get the username associated with the user ID
    $usernameQuery = "SELECT unique_id FROM buyer_accounts WHERE id = '$delete_id'";
    $usernameResult = mysqli_query($conn, $usernameQuery);
    $row = mysqli_fetch_assoc($usernameResult);
    $uniid = $row['unique_id'];

    // Delete the folder and its contents
    $folderPath = '../buyer_profiles/' . $uniid;
    deleteFolder($folderPath);

    $query = "DELETE FROM buyer_accounts WHERE id = '$delete_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $_SESSION['message'] = "Buyer Account Deleted Successfully";
        header('Location: ../admin/buyerlist.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: ../admin/buyerlist.php');
        exit(0);
    }
}

function deleteFolder($folderPath) {
    if (is_dir($folderPath)) {
        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            is_dir($file) ? deleteFolder($file) : unlink($file);
        }
        rmdir($folderPath);
    }
}
?>
