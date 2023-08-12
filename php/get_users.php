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
            $offline = ($row['online_status'] == "0") ? "offline" : "";
            $output .= '<a href="chat.php?unique_id='. $row['unique_id'] .'">
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

    // Output the search results for sellers
    echo $output;
} else {
    // If no search term is provided, handle existing conversations
    $sql = "SELECT DISTINCT seller_accounts.id AS seller_id, seller_accounts.shop_name, seller_accounts.shop_logo, seller_accounts.online_status, seller_accounts.unique_id
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
            // Generate HTML for each conversation
            $offline = ($row['online_status'] == "0") ? "offline" : "";

            // Retrieve the last message for each conversation (assuming the message is stored in the 'msg' column)
            $last_message = ""; // Initialize the last message

            $message_query = mysqli_query($conn, "SELECT msg FROM messages WHERE 
                    (outgoing_msg_id = '{$outgoing_id}' AND incoming_msg_id = '{$row['unique_id']}') 
                    OR 
                    (outgoing_msg_id = '{$row['unique_id']}' AND incoming_msg_id = '{$outgoing_id}')
                    ORDER BY msg_id DESC LIMIT 1");
            if (mysqli_num_rows($message_query) > 0) {
                $last_message_row = mysqli_fetch_assoc($message_query);
                $last_message = $last_message_row['msg']; // Retrieve the last message
            }

            // Generate the conversation list item
            $output .= '<a href="chat.php?unique_id='. $row['unique_id'] .'" data-seller-id="'. $row['unique_id'] .'">
                        <div class="content">
                        <img src="../'. $row['shop_logo'] .'" alt="Profile Picture">
                        <div class="details">
                            <span>'. $row['shop_name'] .'</span>
                            <p>'. ($last_message ? $last_message : 'No messages yet') .'</p>
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
