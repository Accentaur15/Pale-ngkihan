<?php
    session_start();
    include_once "config.php";

    $outgoing_id = $_SESSION['unique_id'];
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);

    // SQL query to search for users with a matching shop name or shop owner
    $sql = "SELECT * FROM seller_accounts 
            WHERE NOT unique_id = '{$outgoing_id}' 
            AND (shop_name LIKE '%{$searchTerm}%'
            OR shop_owner LIKE '%{$searchTerm}%')";
    $query = mysqli_query($conn, $sql);
    $output = "";

    if (mysqli_num_rows($query) > 0) {
        // Include the data.php file to handle the display of user search results
        include_once "../php/data.php";
    } else {
        // No users found matching the search term
        $output .= 'No Seller found related to your search term';
    }

    echo $output;
?>
