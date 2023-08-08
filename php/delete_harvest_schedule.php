<?php
// delete_harvest_schedule.php

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Include the database configuration file
    include_once "../php/config.php";

    // Validate the schedule ID sent from the client-side
    if (isset($_POST["schedule_id"]) && !empty($_POST["schedule_id"])) {
        $scheduleId = $_POST["schedule_id"];

        // Prepare the select query to get the image path before deletion
        $selectQuery = "SELECT harvest_image FROM harvest_schedule WHERE id = ?";
        $stmtSelect = $conn->prepare($selectQuery);
        $stmtSelect->bind_param("i", $scheduleId);

        // Execute the select query
        $stmtSelect->execute();
        $stmtSelect->store_result();

        if ($stmtSelect->num_rows > 0) {
            $stmtSelect->bind_result($harvestImage);
            $stmtSelect->fetch();

            // Unlink the harvest image from the server
            if (file_exists($harvestImage)) {
                unlink($harvestImage);
            }

            // Close the prepared statement for the select query
            $stmtSelect->close();

            // Prepare the delete query
            $deleteQuery = "DELETE FROM harvest_schedule WHERE id = ?";
            $stmtDelete = $conn->prepare($deleteQuery);
            $stmtDelete->bind_param("i", $scheduleId);

            // Execute the delete query
            if ($stmtDelete->execute()) {
                // If the deletion is successful, set the response message
                $response = [
                    "success" => true,
                    "message" => "Harvest schedule has been deleted successfully.",
                ];
            } else {
                // If there's an error with the deletion, set the response message
                $response = [
                    "success" => false,
                    "message" => "Failed to delete the harvest schedule.",
                ];
            }

            // Close the prepared statement for the delete query
            $stmtDelete->close();
        } else {
            // If the schedule ID does not exist, set the response message
            $response = [
                "success" => false,
                "message" => "Harvest schedule not found.",
            ];
        }
    } else {
        // If the schedule ID is not provided, set the response message
        $response = [
            "success" => false,
            "message" => "Invalid schedule ID.",
        ];
    }

    // Close the database connection
    $conn->close();

    // Return the response as JSON
    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    // If the request is not a POST request, redirect to the homepage or display an error page
    // For example:
    // header("Location: index.php");
    // Or display an error message: echo "Invalid request method";
    exit();
}
