<?php
include_once('../php/config.php');


// Save inputs
$user_id = $_POST['user_id'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$lname = $_POST['lname'];
$gender = $_POST['gender'];
$cnumber = $_POST['cnumber'];
$address = $_POST['address'];
$email = $_POST['email'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$validIdFile = $_FILES['validid'];
$profilePictureFile = $_FILES['profilePicture'];
$status = $_POST['status'];

// Fetch the current password from the database
$sql = mysqli_query($conn, "SELECT email, password, profile_picture, valid_id, unique_id FROM buyer_accounts WHERE id = '{$user_id}'");
$row = mysqli_fetch_assoc($sql);
$existingemail = $row['email'];
$uniqueid = $row['unique_id'];
$currentPassword = $row['password'];
$existingProfilePicture = $row['profile_picture'];
$existingValidId = $row['valid_id'];



    // Checking password and confirm password
    if (!empty($password) && !empty($cpassword)) {
        if ($password == $cpassword) {
            $password = md5($password);
            $updatepassword = "UPDATE buyer_accounts SET password = '{$password}' WHERE id = '{$user_id}'";
            if (mysqli_query($conn, $updatepassword)) {
                // Update successful
                echo  "Password Updated";
                
                exit(0);
            } else {
                echo  "Error: " . mysqli_error($conn);
                exit(0);
            }
        } else {
            echo  "Password and Confirm Password don't match.";

            exit(0);
        }
    }

        // Process profile picture
        if (!empty($profilePictureFile['name'])) {
            $profilePictureName = $profilePictureFile['name'];
            $profilePictureTmpName = $profilePictureFile['tmp_name'];
            $profilePictureError = $profilePictureFile['error'];
            $profilePictureType = $profilePictureFile['type'];
    
            // Check if the uploaded file is an image
            if (strpos($profilePictureType, 'image') !== false) {
                // Remove the existing profile picture if it exists
                if (!empty($existingProfilePicture) && file_exists($existingProfilePicture)) {
                    unlink('../' . $existingProfilePicture);
                }
    
                // Generate a unique name for the profile picture
                $profilePictureExtension = pathinfo($profilePictureName, PATHINFO_EXTENSION);
                $newProfilePictureName = 'profilePicture.' . $profilePictureExtension;
                $userFolder = '../buyer_profiles/' . $uniqueid . '/';
                $profilePictureDestination = $userFolder . $newProfilePictureName;
                //echo"$profilePictureDestination";
    
    
                // Move the uploaded profile picture to the user's folder
                move_uploaded_file($profilePictureTmpName, $profilePictureDestination);
                echo  "Profile picture updated.";
                exit(0);
            } else {
                echo  "Profile picture must be an image file.";
                exit(0);
            }
        }
    
        // Process valid ID
        if (!empty($validIdFile['name'])) {
            $validIdName = $validIdFile['name'];
            $validIdTmpName = $validIdFile['tmp_name'];
            $validIdError = $validIdFile['error'];
            $validIdType = $validIdFile['type'];
    
            // Check if the uploaded file is an image
            if (strpos($validIdType, 'image') !== false) {
                // Remove the existing valid ID if it exists
                if (!empty($existingValidId) && file_exists($existingValidId)) {
                    unlink('../' . $existingValidId);
                }
    
                // Generate a unique name for the valid ID
                $validIdExtension = pathinfo($validIdName, PATHINFO_EXTENSION);
                $newValidIdName = 'validid.' . $validIdExtension;
                $userFolder = '../buyer_profiles/' . $uniqueid . '/';
                $validIdDestination = $userFolder . $newValidIdName;
    
                // Move the uploaded valid ID to the user's folder
                move_uploaded_file($validIdTmpName, $validIdDestination);
                echo "Valid ID updated.";
            } else {
                echo "Valid ID must be an image file.";
                exit(0);
            }
        }

    // Update the data in the table
    $updateQuery = "UPDATE buyer_accounts SET first_name = '{$fname}', middle_name = '{$mname}', last_name = '{$lname}', gender = '{$gender}', contact = '{$cnumber}', address = '{$address}', email = '{$email}', status = '{$status}' WHERE id = '{$user_id}'";
    if (mysqli_query($conn, $updateQuery)) {
        // Update successful
        echo  "updated";
        exit(0);
    } else {
        echo "Error: " . mysqli_error($conn);
    }






?>