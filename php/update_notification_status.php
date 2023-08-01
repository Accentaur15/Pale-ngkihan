<?php
// Assuming you have established the database connection with PDO
// and stored it in the $pdo variable

// update_notification_status.php

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the "seen" parameter is set in the POST data
    if (isset($_POST["seen"]) && $_POST["seen"] === "1") {
        // Perform the necessary logic to update the notifications' seen status in your database
        // Assuming you have the buyer ID stored in a session
        $buyer_id = $_SESSION["user_id"];

        // Perform an SQL update query to set the "is_seen" column to 1 for all notifications related to the current buyer
        $stmt = $pdo->prepare("UPDATE notifications SET is_seen = 1 WHERE buyer_id = :buyer_id");
        $stmt->execute(['buyer_id' => $buyer_id]);

        // Return a success response
        echo json_encode(["status" => "success"]);
        exit;
    }
}

// Return an error response if the request method or parameters are invalid
http_response_code(400); // Bad Request
echo json_encode(["status" => "error", "message" => "Invalid request"]);
exit;
?>
