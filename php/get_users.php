<?php
session_start();
include_once('../php/config.php');
$outgoing_id = $_SESSION['unique_id'];

// Check if a search term is provided
if (isset($_POST['searchTerm'])) {
    // Handle search results for sellers
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
    // Modify the SQL query to search for sellers based on shop name or shop owner
    $sql = "SELECT * FROM seller_accounts 
            WHERE (shop_name LIKE '%{$searchTerm}%'
            OR shop_owner LIKE '%{$searchTerm}%')";

    $query = mysqli_query($conn, $sql);
    $output = "";

    if (mysqli_num_rows($query) == 0) {
        // No matching sellers found
        $output .= "No matching sellers found.";
    } else {
        // Display search results for sellers
        while ($row = mysqli_fetch_assoc($query)) {
            // Generate HTML for each search result (clickable to start conversation)
            $output .= '<a href="chat.php?user_id='. $row['id'] .'">
                        <div class="content">
                        <img src="../'. $row['shop_logo'] .'" alt="Profile Picture">
                        <div class="details">
                            <span>'. $row['shop_name'] .'</span>
                            <p>No messages yet</p> <!-- Placeholder for message content -->
                        </div>
                        </div>
                    </a>';
        }
    }

    // Output the search results for sellers
    echo $output;
} else {
    // If no search term is provided, handle existing conversations
    $sql = "SELECT DISTINCT seller_accounts.id AS seller_id, seller_accounts.shop_name, seller_accounts.shop_logo, seller_accounts.online_status
            FROM seller_accounts
            JOIN messages ON (messages.incoming_msg_id = seller_accounts.unique_id OR messages.outgoing_msg_id = seller_accounts.unique_id)
            AND (messages.outgoing_msg_id = '{$outgoing_id}' OR messages.incoming_msg_id = '{$outgoing_id}')
            WHERE seller_accounts.unique_id != '{$outgoing_id}' AND (messages.incoming_msg_id = '{$outgoing_id}' OR messages.outgoing_msg_id = '{$outgoing_id}')
            ORDER BY messages.msg_id DESC";

    $query = mysqli_query($conn, $sql);
    $output = "";

    if (mysqli_num_rows($query) == 0) {
        // No conversations available with sellers
        $output .= "No conversations available with sellers.";
    } else {
        // Display existing conversations
        while ($row = mysqli_fetch_assoc($query)) {
            // Generate HTML for each conversation (similar to existing conversation display)
            $offline = ($row['online_status'] == "0") ? "offline" : "";
            // Use data-seller-id attribute to store the seller_id for clicking functionality
            $output .= '<a href="chat.php?user_id='. $row['seller_id'] .'" data-seller-id="'. $row['seller_id'] .'">
                        <div class="content">
                        <img src="../'. $row['shop_logo'] .'" alt="Profile Picture">
                        <div class="details">
                            <span>'. $row['shop_name'] .'</span>
                            <p>No messages yet</p> <!-- Placeholder for message content -->
                        </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
        }
    }

    // Output the conversation list (existing conversations)
    echo $output;
}
?>
