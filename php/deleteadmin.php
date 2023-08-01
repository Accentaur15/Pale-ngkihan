<?php
session_start();
include_once('../php/config.php');

if(isset($_POST['user_delete'])){
    $delete_id = $_POST['user_delete'];

    // Get the username associated with the user ID
    $usernameQuery = "SELECT unique_id FROM admin_accounts WHERE id = '$delete_id'";
    $usernameResult = mysqli_query($conn, $usernameQuery);
    $row = mysqli_fetch_assoc($usernameResult);
    $uniid = $row['unique_id'];

    // Delete the folder and its contents
    $folderPath = '../admin_profiles/' . $uniid;
    deleteFolder($folderPath);

    $query = "DELETE FROM admin_accounts WHERE id = '$delete_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $_SESSION['message'] = "Moderator/Administrator Deleted Successfully";
        header('Location: ../admin/adminlist.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: ../admin/adminlist.php');
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
