<?php
session_start();
include '../php/config.php';
$Email = $_POST['email'];
$Password = md5($_POST['password']); // Encode the password

if (!empty($Email) && !empty($Password)) {
  $sql = mysqli_query($conn, "SELECT * FROM seller_accounts WHERE email = '{$Email}' AND password = '{$Password}'");
  if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
    if ($row) {
      $_SESSION['unique_id'] = $row['unique_id'];
      $_SESSION['email'] = $row['email'];

      // Update the online_status to 1 for the logged-in user
      $updateSql = mysqli_query($conn, "UPDATE seller_accounts SET online_status = 1 WHERE email = '{$Email}'");

      if ($updateSql) {
        echo "success";
      } else {
        echo "Failed to update online status.";
      }
    } 
  } else {
    echo "Email or Password is Incorrect";
  }
} else {
  echo "All input fields are required.";
}
?>
