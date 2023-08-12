<?php
session_start();
include '../php/config.php';
$Email = $_POST['email'];
$Password = md5($_POST['password']); // Encode the password

if (!empty($Email) && !empty($Password)) {
  $sql = mysqli_query($conn, "SELECT * FROM buyer_accounts WHERE email = '{$Email}' AND password = '{$Password}'");
  if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
    if ($row) {
      $_SESSION['unique_id'] = $row['unique_id'];
      $_SESSION['email'] = $row['email'];

      // Update online_status to 1
      $update_query = "UPDATE buyer_accounts SET online_status = 1 WHERE email = '{$Email}'";
      mysqli_query($conn, $update_query);

      echo "success";
    } 
  } else {
    echo "Email or Password is Incorrect";
  }
} else {
  echo "All input fields are required.";
}
?>
