<?php
session_start();
include_once('../php/config.php');


// Save inputs
$user_id = $_POST['user_id'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$uname = $_POST['uname'];
$role = $_POST['adminrole'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$profilePictureFile = $_FILES['profilePicture'];


// Fetch the current password from the database
$sql = mysqli_query($conn, "SELECT username, profile_picture  FROM admin_accounts WHERE id = '{$user_id}'");
$row = mysqli_fetch_assoc($sql);
$username = $row['username'];
$existingProfilePicture = $row['profile_picture'];





    // Checking password and confirm password
    if (!empty($password) && !empty($cpassword)) {
        if ($password == $cpassword) {
            $password = md5($password);
            $updatepassword = "UPDATE admin_accounts SET password = '{$password}' WHERE id = '{$user_id}'";
            if (mysqli_query($conn, $updatepassword)) {
                // Update successful
                echo "updated";
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
                $newProfilePictureName = 'profilepicture.' . $profilePictureExtension;
                $userFolder = '../admin_profiles/' . $username . '/';
                $profilePictureDestination = $userFolder . $newProfilePictureName;
                //echo"$profilePictureDestination";
    
    
                // Move the uploaded profile picture to the user's folder
                move_uploaded_file($profilePictureTmpName, $profilePictureDestination);
                //echo  "Profile picture updated.";
                exit(0);
            } else {
                echo  "Profile picture must be an image file.";
                exit(0);
            }
        }
    


    // Update the data in the table
    $updateQuery = "UPDATE admin_accounts SET first_name = '{$fname}', last_name = '{$lname}', role = '{$role}', username = '{$uname}' WHERE id = '{$user_id}'";
    if (mysqli_query($conn, $updateQuery)) {
        // Update successful

        echo "updated";
        exit(0);
    } else {
        echo "Error: " . mysqli_error($conn);
    }






?>