<?php
session_start();
include_once('../php/config.php');
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
    header("Location: ../admin/admin.php");
}
$id = $_POST['update_id'];
$cname = $_POST['cname'];
$description = $_POST['description'];
$status = isset($_POST['status']) ?$_POST['status'] : '';



       

$query = "UPDATE category_list SET name = '{$cname}', description = '{$description}', status = '{$status}' WHERE id = '{$id}'";

if (mysqli_query($conn, $query)) {
    // Update successful, redirect to the appropriate page
    $_SESSION['message'] = "Category Updated Successfully";
    header('Location: ../admin/admincategorylist.php');
    exit(0);
} else {
    $_SESSION['message'] = "Something Went Wrong";
    header('Location: ../admin/admincategorylist.php');
    exit(0);
}





?>