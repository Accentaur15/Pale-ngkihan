<?php
require_once('../php/config.php');

// ... (previous code)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['payment_receipt'])) {
    $order_code = $_POST['order_code'];
    // Process the uploaded file
    $file = $_FILES['payment_receipt'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_error = $file['error'];

    // Check for errors during the file upload
    if ($file_error === 0) {
        // Get the current online_payment_receipt value from the database
        $query = "SELECT `online_payment_receipt` FROM `order_list` WHERE `order_code` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $order_code);
        $stmt->execute();
        $stmt->bind_result($current_file_path);
        $stmt->fetch();
        $stmt->close();

        // If a file already exists, delete it
        if (!empty($current_file_path) && file_exists($current_file_path)) {
            unlink($current_file_path);
        }

        // Generate a unique file name to prevent overwriting existing files
        $unique_file_name = uniqid('payment_receipt_', true) . '_' . $file_name;
        $upload_path = '../online_payment_receipts/' . $unique_file_name;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // File successfully uploaded, update the database if required
            // Store the file location in the online_payment_receipt column
            // Instead of directly including the order_code in the SQL query,
            // use prepared statements to prevent SQL injection
            $query = "UPDATE `order_list` SET `online_payment_receipt` = ? WHERE `order_code` = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $upload_path, $order_code);

            if ($stmt->execute()) {
                // Return a JSON response indicating success
                echo json_encode(array('status' => 'success'));
            } else {
                // Return a JSON response indicating database update failure
                echo json_encode(array('status' => 'error', 'message' => 'Failed to update the database.'));
            }
        } else {
            // Return a JSON response indicating file upload error
            echo json_encode(array('status' => 'error', 'message' => 'Failed to move the uploaded file.'));
        }
    } else {
        // Return a JSON response indicating file upload error
        echo json_encode(array('status' => 'error', 'message' => 'There was an error during file upload.'));
    }
}

// ... (previous code)
?>
