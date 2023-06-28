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
$shopname = $_POST['sname'];
$username = $_POST['uname'];
$shopowner = $_POST['shopowner'];
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$cnumber = $_POST['cnumber'];
$address = $_POST['address'];
$email = $_POST['email'];
$password = md5($_POST['password']);
$cpassword = md5($_POST['cpassword']);
$ispstore = isset($_POST['ispstore']) ? $_POST['ispstore'] : '';
$status = '0';
$delete_flag = '0';
$businesspermit = isset($_FILES['bpermit']) ? $_FILES['bpermit'] : '';
$dtipermit = isset($_FILES['dtipermit']) ? $_FILES['dtipermit'] : '';
$mayorspermit = isset($_FILES['mayorspermit']) ? $_FILES['mayorspermit'] : '';
$validIdFile = $_FILES['validid'];
$shoplogo = $_FILES['shoplogo'];
$userproperFolder = 'seller_profiles/' . $email . '/';

// Checking if all required fields are not empty
if (!empty($shopname) && !empty($username) && !empty($shopowner) && !empty($gender) && !empty($cnumber) && !empty($address) && !empty($email) && !empty($password) && !empty($cpassword) && !empty($ispstore) && !empty($validIdFile) && !empty($shoplogo)) {
    // If email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Checking if email already exists
        $sql = mysqli_query($conn, "SELECT email FROM seller_accounts WHERE email = '{$email}'");
        if (mysqli_num_rows($sql) > 0) {
            echo "$email already exists";
        } else{
            // Checking password and confirm password
            if ($password == $cpassword){
                                    // Get the current year, month, and date
                $currentYear = date('Y');
                $currentMonth = date('m');
                $currentDate = date('d');

                // Fetch the last registered user ID from the database
                $query = "SELECT unique_id FROM seller_accounts ORDER BY unique_id DESC LIMIT 1";
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

                // Create the new unique user ID in the format: s-year-month-date-increment
                $random_id = 'S-' . $currentYear . '-' . $currentMonth . '-' . $currentDate . '-' . $newIncrement;


                                // Check if the user uploaded the profile picture
                                if (isset($_FILES['validid'])) {
                                    $validIdName = $_FILES['validid']['name'];
                                    $validIdTmpName = $_FILES['validid']['tmp_name'];
                                    $validIdError = $_FILES['validid']['error'];
                                    $validIdType = $_FILES['validid']['type'];
                
                                    // Check if the uploaded file is an image
                                    if (strpos($validIdType, 'image') !== false) {
                                        // Create a unique folder for each user
                                        $userFolder = '../seller_profiles/' . $random_id . '/';
                                        if (!file_exists($userFolder)) {
                                            mkdir($userFolder, 0777, true);
                                        }
                
                                        // Generate a unique name for the profile picture
                                        $validIdExtension = pathinfo($validIdName, PATHINFO_EXTENSION);
                                        $newValidIdName = 'validid.' . $validIdExtension;
                                        $validIdDestination = $userproperFolder . $newValidIdName;
                                        $validIdDestination2 = $userFolder . $newValidIdName;

                                        // Move the uploaded profile picture to the user's folder
                                          move_uploaded_file($validIdTmpName, $validIdDestination2);
                                    } else {
                                        echo "Valid ID must be an image file.";
                                       
                                    }
                                } else {
                                    echo "Please select a valid ID.";
                                   
                
                                }

                                // Check if the user uploaded the shop logo
                                if (isset($_FILES['shoplogo'])) {
                                    $shoplogoName = $_FILES['shoplogo']['name'];
                                    $shoplogoTmpName = $_FILES['shoplogo']['tmp_name'];
                                    $shoplogoError = $_FILES['shoplogo']['error'];
                                    $shoplogoType = $_FILES['shoplogo']['type'];

                                    // Check if the uploaded file is an image
                                    if (strpos($shoplogoType, 'image') !== false) {
                                        // Create a unique folder for each user
                                        $userFolder = '../seller_profiles/' . $email . '/';


                                        // Generate a unique name for the shop logo
                                        $shoplogoExtension = pathinfo($shoplogoName, PATHINFO_EXTENSION);
                                        $newShoplogoName = 'shoplogo.' . $shoplogoExtension;
                                        $shoplogoDestination = $userproperFolder . $newShoplogoName;
                                        $shoplogoDestination2 = $userFolder . $newShoplogoName;

                                        // Move the uploaded shop logo to the user's folder
                                        move_uploaded_file($shoplogoTmpName, $shoplogoDestination2);
                                    } else {
                                        echo "Shop logo must be an image file.";
                                      
                                    }
                                } else {
                                    echo "Please select a shop logo.";
                                   
                                }
                                
                                            // Check if ispstore is Yes
                                            if ($ispstore == 'Yes') {
                                                // Check if the user uploaded the business permit
                                                if (isset($_FILES['bpermit'])) {
                                                    $businessPermitName = $_FILES['bpermit']['name'];
                                                    $businessPermitTmpName = $_FILES['bpermit']['tmp_name'];
                                                    $businessPermitError = $_FILES['bpermit']['error'];
                                                    $businessPermitType = $_FILES['bpermit']['type'];

                                                    // Check if the uploaded file is an image
                                                    if (strpos($businessPermitType, 'image') !== false) {
                                                        // Create a unique folder for each user
                                                        $userFolder = '../seller_profiles/' . $email . '/';
                                                        if (!file_exists($userFolder)) {
                                                            mkdir($userFolder, 0777, true);
                                                        }

                                                        // Generate a unique name for the business permit
                                                        $businessPermitExtension = pathinfo($businessPermitName, PATHINFO_EXTENSION);
                                                        $newBusinessPermitName = 'businesspermit.' . $businessPermitExtension;
                                                        $businessPermitDestination = $userproperFolder . $newBusinessPermitName;
                                                        $businessPermitDestination2 = $userFolder . $newBusinessPermitName;

                                                        // Move the uploaded business permit to the user's folder
                                                        move_uploaded_file($businessPermitTmpName, $businessPermitDestination2);
                                                    } else {
                                                        echo "Business permit must be an image file.";
                                                    }
                                                } else {
                                                    echo "Please select a business permit.";
                                                }

                                                // Check if the user uploaded the DTI permit
                                                if (isset($_FILES['dtipermit'])) {
                                                    $dtiPermitName = $_FILES['dtipermit']['name'];
                                                    $dtiPermitTmpName = $_FILES['dtipermit']['tmp_name'];
                                                    $dtiPermitError = $_FILES['dtipermit']['error'];
                                                    $dtiPermitType = $_FILES['dtipermit']['type'];

                                                    // Check if the uploaded file is an image
                                                    if (strpos($dtiPermitType, 'image') !== false) {
                                                        // Generate a unique name for the DTI permit
                                                        $dtiPermitExtension = pathinfo($dtiPermitName, PATHINFO_EXTENSION);
                                                        $newDtiPermitName = 'dtipermit.' . $dtiPermitExtension;
                                                        $dtiPermitDestination = $userproperFolder . $newDtiPermitName;
                                                        $dtiPermitDestination2 = $userFolder . $newDtiPermitName;

                                                        // Move the uploaded DTI permit to the user's folder
                                                        move_uploaded_file($dtiPermitTmpName, $dtiPermitDestination2);
                                                    } else {
                                                        echo "DTI permit must be an image file.";
                                                    }
                                                } else {
                                                    echo "Please select a DTI permit.";
                                                }

                                                // Check if the user uploaded the mayor's permit
                                                if (isset($_FILES['mayorspermit'])) {
                                                    $mayorsPermitName = $_FILES['mayorspermit']['name'];
                                                    $mayorsPermitTmpName = $_FILES['mayorspermit']['tmp_name'];
                                                    $mayorsPermitError = $_FILES['mayorspermit']['error'];
                                                    $mayorsPermitType = $_FILES['mayorspermit']['type'];

                                                    // Check if the uploaded file is an image
                                                    if (strpos($mayorsPermitType, 'image') !== false) {
                                                        // Generate a unique name for the mayor's permit
                                                        $mayorsPermitExtension = pathinfo($mayorsPermitName, PATHINFO_EXTENSION);
                                                        $newMayorsPermitName = 'mayorspermit.' . $mayorsPermitExtension;
                                                        $mayorsPermitDestination = $userproperFolder . $newMayorsPermitName;
                                                        $mayorsPermitDestination2 = $userFolder . $newMayorsPermitName;

                                                        // Move the uploaded mayor's permit to the user's folder
                                                        move_uploaded_file($mayorsPermitTmpName, $mayorsPermitDestination2);
                                                    } else {
                                                        echo "Mayor's permit must be an image file.";
                                                    }
                                                } else {
                                                    echo "Please select a mayor's permit.";
                                                }
                                            } else {
                                                $businessPermitDestination = null;
                                                $dtiPermitDestination = null;
                                                $mayorsPermitDestination = null;
                                            }


                // Get the current year, month, and date
                $currentYear = date('Y');
                $currentMonth = date('m');
                $currentDate = date('d');

                // Fetch the last registered user ID from the database
                $query = "SELECT unique_id FROM seller_accounts ORDER BY unique_id DESC LIMIT 1";
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

                // Create the new unique user ID in the format: s-year-month-date-increment
                $random_id = 'S-' . $currentYear . '-' . $currentMonth . '-' . $currentDate . '-' . $newIncrement;
                // Insert data into the table
                $insertQuery = "INSERT INTO seller_accounts (unique_id, username, shop_name, shop_owner, gender, contact, address, email, password, has_pstore, business_permit, dti_permit, mayors_permit, valid_id, shop_logo, status, delete_flag, date_updated) VALUES ('{$random_id}','{$username}', '{$shopname}', '{$shopowner}', '{$gender}', '{$cnumber}', '{$address}', '{$email}', '{$password}', '{$ispstore}', '{$businessPermitDestination}', '{$dtiPermitDestination}', '{$mayorsPermitDestination}', '{$validIdDestination}', '{$shoplogoDestination}', '{$status}', '{$delete_flag}', NULL)";

                if (mysqli_query($conn, $insertQuery)) {
                    // Registration successful, redirect to the buyerlogin.html page
                    echo "success";

                } 
                else {
                    echo "Error: " . mysqli_error($conn);
                }                

            }
            else{
                echo "Password and Confirm Password don't match.";
            }

        }  


    } else {
        echo "$email is not a valid email.";
    }
} else {
    echo "All input fields are required.";
}