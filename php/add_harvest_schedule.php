<?php
session_start();
include_once('../php/config.php');
//ini_set('log_errors', 1);
//ini_set('error_log', '../error_log_file.log'); // Replace with the desired file path and name
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $seller_id = $_POST['sellerid'];
    $selectQuery = "SELECT unique_id FROM seller_accounts WHERE id = '$seller_id'";
    $result = mysqli_query($conn, $selectQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $unique_id = $row['unique_id'];
    } else {
        $unique_id = null;
    }

    if (
        isset($_POST['type_rice']) && !empty($_POST['type_rice']) &&
        isset($_POST['schedule_date']) && !empty($_POST['schedule_date']) &&
        isset($_POST['location']) && !empty($_POST['location']) &&
        isset($_POST['quantity_available']) && !empty($_POST['quantity_available']) &&
        isset($_POST['status']) && !empty($_POST['status']) &&
        isset($_POST['bidding_status'])
    ) {
        $type_rice = mysqli_real_escape_string($conn, $_POST['type_rice']);
        $schedule_date = mysqli_real_escape_string($conn, $_POST['schedule_date']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $quantity_available = (float)$_POST['quantity_available'];
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $bidding_status = (int)$_POST['bidding_status'];
        $starting_bid = null;

        if ($bidding_status === 0) {
            if (isset($_POST['starting_bid']) && !empty($_POST['starting_bid'])) {
                $starting_bid = (float)$_POST['starting_bid'];
            } else {
                $response = array("success" => false, "message" => "Starting bid is required when bidding status is set to close.");
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
        }

        if (isset($_FILES['field_image']) && $_FILES['field_image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['field_image']['tmp_name'];
            $file_name = $_FILES['field_image']['name'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

            if (in_array(strtolower($file_ext), $allowed_extensions)) {
                $file_destination = '../seller_profiles/'.$unique_id.'/-' . uniqid() . '.' . $file_ext;

                if (move_uploaded_file($tmp_name, $file_destination)) {
                    // Modify the INSERT query to consider starting_bid only when bidding_status is close (0)
                    $insertQuery = "INSERT INTO harvest_schedule (seller_id, rice_type, date_scheduled, location, quantity_available, status, bidding_status";

                    if ($bidding_status === 0) {
                        $insertQuery .= ", starting_bid";
                    }

                    $insertQuery .= ", harvest_image)
                                    VALUES ('$seller_id', '$type_rice', '$schedule_date', '$location', $quantity_available, '$status', $bidding_status";

                    if ($bidding_status === 0) {
                        $insertQuery .= ", $starting_bid";
                    }

                    $insertQuery .= ", '$file_destination')";

                    if (mysqli_query($conn, $insertQuery)) {
                        $response = array("success" => true);
                    } else {
                        $response = array("success" => false, "message" => "Error inserting data into the database.");
                    }
                } else {
                    $response = array("success" => false, "message" => "Error moving the uploaded file.");
                }
            } else {
                $response = array("success" => false, "message" => "Invalid file extension. Only JPG, JPEG, PNG, and GIF files are allowed.");
            }
        } else {
            $response = array("success" => false, "message" => "Please upload a field image.");
        }
    } else {
        $response = array("success" => false, "message" => "Please fill all the required fields.");
    }
} else {
    $response = array("success" => false, "message" => "Invalid request method.");
}

header('Content-Type: application/json');
echo json_encode($response);
?>
