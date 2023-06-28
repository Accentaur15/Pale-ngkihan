<?php 
//echo "you're connected";

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "pale-ngkihan_db";

$conn = mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);


// Check connection
if(!$conn){
        echo"Connection Denied!".mysqli_connect_error();
}



?>


