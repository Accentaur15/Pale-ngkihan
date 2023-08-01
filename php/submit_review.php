<?php
// Include your config file to establish the database connection
require_once('../php/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the review data from the POST request
    $productId = $_POST['product_id']; // You should add a hidden input field for product_id in the review modal form
    $reviewText = $_POST['review_text'];
    $rating = $_POST['rating'];
    // Get the reviewer name from the hidden input fields
    $reviewerFname = $_POST['reviewer_fname']; // Get reviewer's first name
    $reviewerLname = $_POST['reviewer_lname']; // Get reviewer's last name
    $reviewerName = $reviewerFname . ' ' . $reviewerLname; // Concatenate first and last name

    // Perform any necessary validation on the review data (e.g., check for empty values)

    // Insert the review into the database
    $insertQuery = $conn->prepare("INSERT INTO product_reviews (product_id, reviewer_name, review_text, rating, review_date)
                                  VALUES (?, ?, ?, ?, NOW())");
    $insertQuery->bind_param("isss", $productId, $reviewerName, $reviewText, $rating);


    if ($insertQuery->execute()) {
        // Review inserted successfully
        $response = array('status' => 'success', 'message' => 'Review submitted successfully!');
    } else {
        // Error occurred while inserting the review
        $response = array('status' => 'error', 'message' => 'Failed to submit the review.');
    }

    // Close the database connection
    $insertQuery->close();

    // Update the review_status in the order_items table
$updateQuery = $conn->prepare("UPDATE order_items SET review_status = 1 WHERE product_id = ?");
$updateQuery->bind_param("i", $productId); // Assuming the product_id is of type integer, use "i" for integer parameter
$updateQuery->execute();
$updateQuery->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle invalid request method
    http_response_code(405); // Method Not Allowed
}
?>
