<?php 

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "pale-ngkihan_db";

$conn = mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);

/*
// Check connection
if($conn){
    echo "Database Connected!";
}
    else{
        echo"Connection Denied!";
}
*/
function validate_image($file){
    $file = explode("?",$file)[0];
	if(!empty($file)){
			// exit;
		if(is_file(base_app.$file)){
			return base_url.$file;
		}else{
			return base_url.'dist/img/no-image-available.png';
		}
	}else{
		return base_url.'dist/img/no-image-available.png';
	}
}

?>


