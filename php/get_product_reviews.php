<?php
// Include your database connection configuration file
include_once('../php/config.php');

// Check if the product_id is set in the POST data
if (isset($_POST['product_id'])) {
  $product_id = $_POST['product_id'];

  // Check if pagination parameters are provided, otherwise use default values
  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $reviewsPerPage = isset($_POST['reviews_per_page']) ? intval($_POST['reviews_per_page']) : 5;

  // Calculate the starting index for the reviews to fetch
  $startIndex = ($page - 1) * $reviewsPerPage;

  // Prepare and execute the query to fetch product reviews based on the product_id and pagination parameters
  $reviewsQuery = mysqli_query($conn, "SELECT * FROM product_reviews WHERE product_id = '{$product_id}' LIMIT $startIndex, $reviewsPerPage");

  // Create an array to store the reviews
  $reviews = array();

  // Loop through the results and add each review to the array
  while ($reviewRow = mysqli_fetch_assoc($reviewsQuery)) {
    $reviews[] = $reviewRow;
  }

  // Send the total number of reviews as part of the response for pagination
  $totalReviewsQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_reviews FROM product_reviews WHERE product_id = '{$product_id}'");
  $totalReviewsResult = mysqli_fetch_assoc($totalReviewsQuery);
  $totalReviews = intval($totalReviewsResult['total_reviews']);

  // Convert the array and total reviews count to JSON and echo it as the response
  header('Content-Type: application/json');
  echo json_encode(['reviews' => $reviews, 'total_reviews' => $totalReviews]);
} else {
  // If the product_id is not set, return an empty response
  header('Content-Type: application/json');
  echo json_encode(['reviews' => array(), 'total_reviews' => 0]);
}

?>
