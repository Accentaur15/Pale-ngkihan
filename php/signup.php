<?php
include_once('config.php');

//check if its working
/*echo '<script>alert("signup.php is working")</script>';*/

//check if database is connected
/*if($conn){
    echo"Connected".mysqli_connect_error();
}*/

// Start the session
session_start();

// Save inputs
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$lname = $_POST['lname'];
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$cnumber = $_POST['cnumber'];
$address = $_POST['address'];
$email = $_POST['email'];
$password = md5($_POST['password']);
$cpassword = md5($_POST['cpassword']);
$status = '0';
$delete_flag = '0';
$validIdFile = $_FILES['validid'];
$profilePictureFile = $_FILES['profilePicture'];


// Checking if all required fields are not empty
if (!empty($fname) && !empty($mname) && !empty($lname) && !empty($gender) && !empty($cnumber) && !empty($address) && !empty($email) && !empty($password) && !empty($cpassword) && !empty($validIdFile) && !empty($profilePictureFile)) {
    // If email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Checking if email already exists
        $sql = mysqli_query($conn, "SELECT email FROM buyer_accounts WHERE email = '{$email}'");
        if (mysqli_num_rows($sql) > 0) {
            echo "$email already exists";
        } else {
            // Checking password and confirm password
            if ($password == $cpassword) {
                                // Get the current year, month, and date
                                $currentYear = date('Y');
                                $currentMonth = date('m');
                                $currentDate = date('d');
                
                                // Fetch the last registered user ID from the database
                                $query = "SELECT unique_id FROM buyer_accounts ORDER BY unique_id DESC LIMIT 1";
                                $result = mysqli_query($conn, $query);
                
                                if (mysqli_num_rows($result) > 0) {
                                    $lastUserId = mysqli_fetch_assoc($result)['unique_id'];
                                    // Extract the number increment from the last user ID
                                    $lastIncrement = explode('-', $lastUserId)[1];
                
                                    // Generate the new increment by incrementing the last increment value
                                    $newIncrement = $lastIncrement + 1;
                                } else {
                                    // This is the first user being registered
                                    $newIncrement = 1;
                                }
                
                                // Create the new unique user ID in the format: year-month-date-increment
                                $random_id = 'B-' . $currentYear . '-' . $currentMonth . '-' . $currentDate . '-' . $newIncrement;

                // Check if the user uploaded the profile picture
                if (isset($_FILES['profilePicture'])) {
                    $profilePictureName = $_FILES['profilePicture']['name'];
                    $profilePictureTmpName = $_FILES['profilePicture']['tmp_name'];
                    $profilePictureError = $_FILES['profilePicture']['error'];
                    $profilePictureType = $_FILES['profilePicture']['type'];

                    // Check if the uploaded file is an image
                    if (strpos($profilePictureType, 'image') !== false) {
                        // Create a unique folder for each user
                        $userFolder = '../buyer_profiles/' . $email . '/';
                        if (!file_exists($userFolder)) {
                            mkdir($userFolder, 0777, true);
                        }

                        // Generate a unique name for the profile picture
                        $profilePictureExtension = pathinfo($profilePictureName, PATHINFO_EXTENSION);
                        $newProfilePictureName = 'profilePicture.' . $profilePictureExtension;
                        $userproperFolder = 'buyer_profiles/' . $random_id . '/';
                        $profilePictureDestination = $userproperFolder . $newProfilePictureName;
                        $profilePictureDestination2 = $userFolder . $newProfilePictureName;

                        // Move the uploaded profile picture to the user's folder
                        move_uploaded_file($profilePictureTmpName, $profilePictureDestination2);
                    } else {
                        echo "Profile picture must be an image file.";
                        exit; // Stop execution if profile picture is not selected
                    }
                } else {
                    echo "Please select a profile picture.";
                    exit; // Stop execution if profile picture is not selected

                }

                // Check if the user uploaded the valid ID
                if (isset($_FILES['validid'])) {
                    $validIdName = $_FILES['validid']['name'];
                    $validIdTmpName = $_FILES['validid']['tmp_name'];
                    $validIdError = $_FILES['validid']['error'];
                    $validIdType = $_FILES['validid']['type'];

                    // Check if the uploaded file is an image
                    if (strpos($validIdType, 'image') !== false) {
                        // Generate a unique name for the valid ID
                        $validIdExtension = pathinfo($validIdName, PATHINFO_EXTENSION);
                        $newValidIdName = 'validid.' . $validIdExtension;
                        $validIdDestination = $userproperFolder . $newValidIdName;
                        $validIdDestination2 = $userFolder . $newValidIdName;

                        // Move the uploaded valid ID to the user's folder
                        move_uploaded_file($validIdTmpName, $validIdDestination2);
                    } else {
                        echo "Valid ID must be an image file.";
                    }
                } else {
                    echo "Please select a valid ID.";
                }

                // Insert data into the table
                $insertQuery = "INSERT INTO buyer_accounts (unique_id, first_name, middle_name, last_name, gender, contact, address, email, password, status, profile_picture, valid_id, delete_flag, date_updated) VALUES ('{$random_id}','{$fname}', '{$mname}', '{$lname}', '{$gender}', '{$cnumber}', '{$address}', '{$email}', '{$password}', '{$status}', '{$profilePictureDestination}', '{$validIdDestination}', '{$delete_flag}', NULL)";

                if (mysqli_query($conn, $insertQuery)) {

                    // Registration successful, redirect to the buyerlogin.html page
                    echo "success";

                } 
                else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Password and Confirm Password don't match.";
            }
        }
    } else {
        echo "$email is not a valid email.";
    }
} else {
    echo "All input fields are required.";
}
