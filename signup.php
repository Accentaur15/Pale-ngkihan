<?php 

include_once 'config.php'

//save inputs

$fname = $_POST['firstname'];
$mname = $_POST['middlename'];
$lname = $_POST['lastname'];
$gender = $_POST['gender'];
$cnumber = $_POST['contactnumber'];
$address = $_POST['address'];
$email = $_POST['email'];
$password = md5($_POST['password']);
$cpassword = md5($_POST['cpassword']);
$verification_status = '0';

//check input

if(!empty($fname) or !empty($mname) && !empty($lname) && !empty($gender) && !empty($cnumber) && !empty($address) && !empty($email) && !empty($password) && !empty($cpassword)){
    //if email is valid
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        //checking if email is already existing
        $sql= mysqli_query($conn,"SELECT email from buyer_accounts WHERE email ='($email)'");
        if(mysqli_num_rows($sql)>0){
            echo "$emali ~ Already Exists";
        }
        else{
            //checking password and confirm password match
            if($password == $cpassword){

            }
            else{
                echo "Confirm Password Don't Match";
            }
        }
    }
    else{
        echo "$email ~ This is not a Valid Email";
    }
}
else{
    echo "All Input fields are Required!";
}


?>