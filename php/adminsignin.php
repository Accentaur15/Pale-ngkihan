<?php
session_start();
include '../php/config.php';
$username = $_POST['uname'];
$password = md5($_POST['password']); // Encode the password

if (!empty($username) && !empty($password)) {
  $sql = mysqli_query($conn, "SELECT * FROM admin_accounts WHERE username = '{$username}' AND password = '{$password}'");
  if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
    if ($row) {
      $_SESSION['unique_id'] = $row['id'];
      echo "success";
    } 
  } else {
    echo "Email or Password is Incorrect";
  }
} else {
  echo "All input fields are required.";
}
?>
