<?php
session_start();
include_once('../php/config.php');

if(isset($_POST['category_delete'])){
    $delete_id = $_POST['category_delete'];

    $query = "DELETE FROM category_list WHERE id = '{$delete_id}'";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $_SESSION['message'] = "Category Deleted Successfully";
        header('Location: ../admin/admincategorylist.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: ../admin/admincategorylist.php');
        exit(0);
    }
}

?>
