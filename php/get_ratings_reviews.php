<?php
// Include your config file to establish the database connection
require_once('../php/config.php');

// Check if the product ID is provided via the GET request
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch the ratings and reviews for the product from the product_reviews table
    $qry = $conn->query("SELECT * FROM product_reviews WHERE product_id = '{$product_id}' ORDER BY review_date DESC");

    if ($qry->num_rows > 0) {
        // Create an array to store the ratings and reviews data
        $ratingsReviews = array();

        // Loop through the result and add each row to the ratings and reviews array
        while ($row = $qry->fetch_assoc()) {
            $ratingsReviews[] = array(
                'rating' => $row['rating'],
                'review' => $row['review_text'],
                'reviewer' => $row['reviewer_name'],
                'reviewDate' => $row['review_date']
            );
        }

        // Convert the array to JSON and echo it
        echo json_encode($ratingsReviews);
    } else {
        // If no ratings and reviews are found, return an empty array
        echo json_encode(array());
    }
} else {
    // If the product ID is not provided, return an error message
    echo json_encode(array('error' => 'Product ID is required.'));
}
?>
