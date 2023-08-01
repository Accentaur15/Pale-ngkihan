<?php



session_start();
include_once('../php/config.php');

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$uname = $_POST['uname'];
$password = md5($_POST['password']);
$cpassword = md5($_POST['cpassword']);
$role = isset($_POST['adminrole']) ? $_POST['adminrole'] : '';
$profilepicture = $_FILES['profilepicture'];


if (!empty($fname) && !empty($lname) && !empty($uname) && !empty($password) && !empty($cpassword) && !empty($role) && !empty($profilepicture)) {

            // Checking password and confirm password
            if ($password == $cpassword) {

                
        // Get the current year, month, and date
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentDate = date('d');

        // Fetch the last registered user ID from the database
        $query = "SELECT unique_id FROM admin_accounts ORDER BY unique_id DESC LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $lastUserId = mysqli_fetch_assoc($result)['unique_id'];
            // Extract the number increment from the last user ID
            $lastIncrement = explode('-', $lastUserId)[4];

            // Generate the new increment by incrementing the last increment value
            $newIncrement = $lastIncrement + 1;
        } else {
            // This is the first user being registered
            $newIncrement = 1;
        }

        // Create the new unique user ID in the format: A-year-month-date-increment
        $random_id = 'A-' . $currentYear . '-' . $currentMonth . '-' . $currentDate . '-' . $newIncrement;

               
                 // Check if the user uploaded the shop logo
    if (isset($_FILES['profilepicture'])) {
        $profilepictureName = $_FILES['profilepicture']['name'];
        $profilepictureTmpName = $_FILES['profilepicture']['tmp_name'];
        $profilepictureError = $_FILES['profilepicture']['error'];
        $profilepictureType = $_FILES['profilepicture']['type'];

        // Check if the uploaded file is an image
        if (strpos($profilepictureType, 'image') !== false) {
            // Create a unique folder for each user
            $userFolder = '../admin_profiles/' . $random_id . '/';
            $userproperFolder = 'admin_profiles/' . $random_id . '/';
            if (!file_exists($userFolder)) {
                mkdir($userFolder, 0777, true);
            }


            // Generate a unique name for the shop logo
            $profilepictureExtension = pathinfo($profilepictureName, PATHINFO_EXTENSION);
            $newprofilepictureName = 'profilepicture.' . $profilepictureExtension;
            $profilepictureDestination = $userproperFolder . $newprofilepictureName;
            $profilepictureDestination2 = $userFolder . $newprofilepictureName;

            // Move the uploaded shop logo to the user's folder
            move_uploaded_file($profilepictureTmpName, $profilepictureDestination2);
        } else {
            echo "Profile Picture must be an image file.";

        }
    } else {
        echo "Please select a Profile Picture.";

    }



                    // Insert data into the table
                    $insertQuery = "INSERT INTO admin_accounts (first_name, last_name, username, password, profile_picture, role, unique_id) VALUES ('{$fname}','{$lname}', '{$uname}', '{$password}', '{$profilepictureDestination}', '{$role}', '{$random_id}')";

                    if (mysqli_query($conn, $insertQuery)) {
                        // Registration successful, redirect to the buyerlogin.html page
                        echo "updated";
    
                    } 
                    else {
                        echo "Error: " . mysqli_error($conn);
                    }   

               
            } else {
                echo "Password and Confirm Password don't match.";
            }
        

   

} else {
    echo "All Input Fields are Required";
}


?>