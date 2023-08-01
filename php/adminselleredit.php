<?php
session_start();
include_once('../php/config.php');




// Save inputs
$user_id = $_POST['user_id'];
$shopname = $_POST['sname'];
$username = $_POST['uname'];
$shopowner = $_POST['shopowner'];
$gender = $_POST['gender'];
$cnumber = $_POST['cnumber'];
$address = $_POST['address'];
$email = $_POST['email'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$ispstore = $_POST['ispstore'];
$businesspermit = isset($_FILES['bpermit']) ? $_FILES['bpermit'] : '';
$dtipermit = isset($_FILES['dtipermit']) ? $_FILES['dtipermit'] : '';
$mayorspermit = isset($_FILES['mayorspermit']) ? $_FILES['mayorspermit'] : '';
$validIdFile = $_FILES['validid'];
$shoplogo = $_FILES['shoplogo'] ;
$status = $_POST['status'];



// Fetch the current password from the database
$sql = mysqli_query($conn, "SELECT unique_id, email, shop_logo, valid_id, dti_permit, business_permit, mayors_permit FROM seller_accounts WHERE id = '{$user_id}'");
$row = mysqli_fetch_assoc($sql);
$unique_id = $row['unique_id'];
$existingemail = $row['email'];
$existingShopLogo = $row['shop_logo'];
$existingValidId = $row['valid_id'];
$existingdtipermit = $row['dti_permit'];
$existingbusinesspermit = $row['business_permit'];
$existingmayorspermit = $row['mayors_permit'];
$userFolder = '../seller_profiles/' . $unique_id . '/';
$userproperFolder = 'seller_profiles/' . $unique_id . '/';



        
        // Checking password and confirm password
            if (!empty($password) && !empty($cpassword)) {
                if ($password == $cpassword) {
                $password = md5($password);
                $updatepassword = "UPDATE seller_accounts SET password = '{$password}' WHERE id = '{$user_id}'";
                if (mysqli_query($conn, $updatepassword)) {
                    // Update successful
                    echo "Password updated";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Password and Confirm Password don't match.";
            }
        }
        
        //$dataType = gettype($validIdFile);
        //echo "The data type of \$validIdFile is: $dataType";
       // echo "Using print_r():\n";
        //print_r($validIdFile);
            // Check if the user uploaded the validid   
            if (!empty($validIdFile['name'])) {
                $validIdName = $_FILES['validid']['name'];
                $validIdTmpName = $_FILES['validid']['tmp_name'];
                $validIdError = $_FILES['validid']['error'];
                $validIdType = $_FILES['validid']['type'];

                // Check if the uploaded file is an image
                if (strpos($validIdType, 'image') !== false) {
                    // Remove the existing profile picture if it exists
                    if (!empty($existingProfilePicture) && file_exists($existingProfilePicture)) {
                        unlink('../' . $existingValidId);
                    }

                    // Generate a unique name for the profile picture
                    $validIdExtension = pathinfo($validIdName, PATHINFO_EXTENSION);
                    $newValidIdName = 'validid.' . $validIdExtension;
                    $userFolder = '../seller_profiles/' . $unique_id . '/';
                    $validIdDestination = $userFolder . $newValidIdName;


                    // Move the uploaded profile picture to the user's folder
                    move_uploaded_file($validIdTmpName, $validIdDestination);
                    echo "Profile picture updated.";
                } else {
                    echo "Valid ID must be an image file.";
                }
            } 

            // Check if the user uploaded the shop logo
            if (!empty($shoplogo['name'])) {
                $shoplogoName = $_FILES['shoplogo']['name'];
                $shoplogoTmpName = $_FILES['shoplogo']['tmp_name'];
                $shoplogoError = $_FILES['shoplogo']['error'];
                $shoplogoType = $_FILES['shoplogo']['type'];

                // Check if the uploaded file is an image
                if (strpos($shoplogoType, 'image') !== false) {
                    // Remove the existing shop logo if it exists
                    if (!empty($existingShopLogo) && file_exists($existingShopLogo)) {
                        unlink('../' . $existingShopLogo);
                    }

                    // Generate a unique name for the shop logo
                    $shoplogoExtension = pathinfo($shoplogoName, PATHINFO_EXTENSION);
                    $newShopLogoName = 'shoplogo.' . $shoplogoExtension;
                    $shoplogoDestination = $userFolder . $newShopLogoName;

                    // Move the uploaded shop logo to the user's folder
                    move_uploaded_file($shoplogoTmpName, $shoplogoDestination);
                    echo "Shop logo updated.";
                } else {
                    echo "Shop logo must be an image file.";
                }
            } 


            // Check if ispstore is Yes
            if ($ispstore == 'Yes') {
                // Check if the user uploaded the business permit 
                if (!empty($businesspermit['name'])) {
                    $businessPermitName = $_FILES['bpermit']['name'];
                    $businessPermitTmpName = $_FILES['bpermit']['tmp_name'];
                    $businessPermitError = $_FILES['bpermit']['error'];
                    $businessPermitType = $_FILES['bpermit']['type'];

                    // Check if the uploaded file is an image
                    if (strpos($businessPermitType, 'image') !== false) {
                        // Remove the existing business permit if it exists
                        if (!empty($existingbusinesspermit) && file_exists($existingbusinesspermit)) {
                            unlink('../' . $existingbusinesspermit);
                        }

                        // Generate a unique name for the business permit
                        $businessPermitExtension = pathinfo($businessPermitName, PATHINFO_EXTENSION);
                        $newBusinessPermitName = 'businesspermit.' . $businessPermitExtension;
                        $businessPermitDestination = $userFolder . $newBusinessPermitName;
                        $businessPermitDestination2 = $userproperFolder . $newBusinessPermitName;

                        // Move the uploaded business permit to the user's folder
                        move_uploaded_file($businessPermitTmpName, $businessPermitDestination);
                        $updateQuery = "UPDATE seller_accounts SET business_permit = '{$businessPermitDestination2}' WHERE id = '{$user_id}'";
                        if (mysqli_query($conn, $updateQuery)) {
                            // Update successful
                            echo "Business permit updated.";
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Business permit must be an image file.";
                    }
                } 


                // Check if the user uploaded the DTI permit
                if (!empty($dtipermit ['name'])) {
                    $dtiPermitName = $_FILES['dtipermit']['name'];
                    $dtiPermitTmpName = $_FILES['dtipermit']['tmp_name'];
                    $dtiPermitError = $_FILES['dtipermit']['error'];
                    $dtiPermitType = $_FILES['dtipermit']['type'];

                    // Check if the uploaded file is an image
                    if (strpos($dtiPermitType, 'image') !== false) {
                        // Remove the existing DTI permit if it exists
                        if (!empty($existingdtipermit) && file_exists($existingdtipermit)) {
                            unlink('../' . $existingdtipermit);
                        }

                        // Generate a unique name for the DTI permit
                        $dtiPermitExtension = pathinfo($dtiPermitName, PATHINFO_EXTENSION);
                        $newDTIPermitName = 'dtipermit.' . $dtiPermitExtension;
                        $dtiPermitDestination = $userFolder . $newDTIPermitName;
                        $dtiPermitDestination2 = $userproperFolder . $newDTIPermitName;

                        // Move the uploaded DTI permit to the user's folder
                        move_uploaded_file($dtiPermitTmpName, $dtiPermitDestination);
                        $updateQuery = "UPDATE seller_accounts SET dti_permit = '{$dtiPermitDestination2}' WHERE id = '{$user_id}'";
                        if (mysqli_query($conn, $updateQuery)) {
                            // Update successful
                            echo "DTI permit updated.";
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        echo "DTI permit must be an image file.";
                    }
                } 


                // Check if the user uploaded the mayor's permit
                if (!empty($mayorspermit ['name'])) {
                    $mayorsPermitName = $_FILES['mayorspermit']['name'];
                    $mayorsPermitTmpName = $_FILES['mayorspermit']['tmp_name'];
                    $mayorsPermitError = $_FILES['mayorspermit']['error'];
                    $mayorsPermitType = $_FILES['mayorspermit']['type'];

                    // Check if the uploaded file is an image
                    if (strpos($mayorsPermitType, 'image') !== false) {
                        // Remove the existing mayor's permit if it exists
                        if (!empty($existingmayorspermit) && file_exists($existingmayorspermit)) {
                            unlink('../' . $existingmayorspermit);
                        }

                        // Generate a unique name for the mayor's permit
                        $mayorsPermitExtension = pathinfo($mayorsPermitName, PATHINFO_EXTENSION);
                        $newMayorsPermitName = 'mayorspermit.' . $mayorsPermitExtension;
                        $mayorsPermitDestination = $userFolder . $newMayorsPermitName;
                        $mayorsPermitDestination2 = $userproperFolder . $newMayorsPermitName;

                        // Move the uploaded mayor's permit to the user's folder
                        move_uploaded_file($mayorsPermitTmpName, $mayorsPermitDestination);
                         $updateQuery = "UPDATE seller_accounts SET mayors_permit = '{$mayorsPermitDestination2}' WHERE id = '{$user_id}'";
                        if (mysqli_query($conn, $updateQuery)) {
                            // Update successful
                            echo "Mayor's permit updated.";
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Mayor's permit must be an image file.";
                    }
                } 

            }

            // Update query
            $updateQuery = "UPDATE seller_accounts SET username = '{$username}', shop_name = '{$shopname}', shop_owner = '{$shopowner}', gender = '{$gender}', contact = '{$cnumber}', address = '{$address}', email = '{$email}', has_pstore = '{$ispstore}', status = '{$status}'  WHERE id = '{$user_id}'";

            if (mysqli_query($conn, $updateQuery)) {
                // Update successful
                echo "Account updated";
            } else {
                echo "Error: " . mysqli_error($conn);
            }

?>
