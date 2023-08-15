<?php
session_start();
include_once('../php/config.php');
$outgoing_id = $_SESSION['unique_id'];

// Check if a search term is provided
if (isset($_POST['searchTerm'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);


// Modify the SQL query to search for both buyers and sellers
$sql = "SELECT unique_id, 'buyer' AS user_type, first_name, last_name, middle_name, profile_picture, NULL AS shop_owner, NULL AS shop_name, NULL AS shop_logo
        FROM buyer_accounts 
        WHERE (first_name LIKE '%{$searchTerm}%' OR
               last_name LIKE '%{$searchTerm}%' OR
               middle_name LIKE '%{$searchTerm}%')
        UNION
        SELECT unique_id, 'seller' AS user_type, NULL AS first_name, NULL AS last_name, NULL AS middle_name, NULL AS profile_picture, shop_owner, shop_name, shop_logo
        FROM seller_accounts
        WHERE (shop_name LIKE '%{$searchTerm}%'
        OR shop_owner LIKE '%{$searchTerm}%')";


    $query = mysqli_query($conn, $sql);
    $output = "";

    if (mysqli_num_rows($query) == 0) {
        // No matching users found
        $output .= "No matching users found.";
    } else {
        // Display search results for users
        while ($row = mysqli_fetch_assoc($query)) {
            // Determine the appropriate profile picture or shop logo based on user type
            $imageSource = ucfirst($row['user_type']) == 'Seller' ? $row['shop_logo'] : $row['profile_picture'];
            
            // Generate HTML for each search result (clickable to start conversation)
            $senderName = ucfirst($row['user_type']) == 'Seller' ? $row['shop_owner'] : $row['first_name'] .' '. $row['last_name'];
            
            $output .= '<a href="chat.php?unique_id='. $row['unique_id'] .'">
                        <div class="content">
                        <img src="../'. $imageSource .'" alt="Profile Picture">
                        <div class="details">
                            <span class="user-sender">'. ucfirst($row['user_type']) .': '. $senderName .'</span>
                        </div>
                        </div>
                    </a>';
        }
    }


    // Output the search results for users
    echo $output;
} else {
    // If no search term is provided, handle existing conversations with both buyers and sellers
    $sql = "SELECT m.id AS message_id, m.user_type, m.unique_id, m.message, m.date_sent,
    CASE WHEN m.user_type = 'buyer' THEN b.first_name
         WHEN m.user_type = 'seller' THEN s.shop_name
    END AS sender_name,
    CASE WHEN m.user_type = 'buyer' THEN b.profile_picture
         WHEN m.user_type = 'seller' THEN s.shop_logo
    END AS sender_picture
FROM support_messages AS m
LEFT JOIN buyer_accounts AS b ON m.user_type = 'buyer' AND m.unique_id = b.unique_id
LEFT JOIN seller_accounts AS s ON m.user_type = 'seller' AND m.unique_id = s.unique_id
WHERE m.user_type IN ('buyer', 'seller')
GROUP BY m.unique_id
ORDER BY m.date_sent DESC";


    $query = mysqli_query($conn, $sql);
    $output = "";

    if (mysqli_num_rows($query) == 0) {
        // No conversations available with users
        $output .= "No conversations available with users.";
    } else {
        // Display existing conversations with users
        while ($row = mysqli_fetch_assoc($query)) {
            // Generate HTML for each conversation
       

            // Retrieve the last message for each conversation (assuming the message is stored in the 'message' column)
            $last_message = $row['message'];

            // Generate the conversation list item for users
            $output .= '<a href="chat.php?unique_id='. $row['unique_id'] .'" data-user-id="'. $row['unique_id'] .'">
                        <div class="content">
                        <img src="../'. $row['sender_picture'] .'" alt="Profile Picture">
                        <div class="details">
                        <span class="user-sender">'. ucfirst($row['user_type']) .': '. $row['sender_name'] .'</span>
                            <p>'. $last_message .'</p>
                        </div>
                        </div>
                    </a>';
        }
    }

    // Output the conversation list (existing conversations with users)
    echo $output;
}
?>
