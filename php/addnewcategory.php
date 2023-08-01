<?php



session_start();
include_once('../php/config.php');
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
    header("Location: ../admin/admin.php");
}


$cname = $_POST['cname'];
$description = $_POST['description'];
$status = isset($_POST['status']) ?$_POST['status'] : '';



if (!empty($cname) && !empty($description) && !empty($status) ) {

       

                    // Insert data into the table
                    $insertQuery = "INSERT INTO category_list (name, description, status) VALUES ('{$cname}', '{$description}', '{$status}')";


                    if (mysqli_query($conn, $insertQuery)) {
                        // Registration successful, redirect to the buyerlogin.html page
                        $_SESSION['message'] = "New Category Successfully Added";
                        echo "updated";
                        exit(0);
    
                    } 
                    else {
                        $_SESSION['message'] = "Something Went Wrong";
                        header('Location: ../admin/admincategorylist.php');
                        exit(0);
                    }   


} else {
    echo "All Input Fields are Required";
}


?>