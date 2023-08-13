<?php
session_start();
include_once('../php/config.php');
$outgoing_id = $_SESSION['unique_id'];

// Check if a search term is provided
if (isset($_POST['searchTerm'])) {
    // Handle search results for buyers
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
    // Modify the SQL query to search for buyers based on attributes like first_name, last_name, or contact
    $sql = "SELECT * FROM buyer_accounts 
            WHERE (first_name LIKE '%{$searchTerm}%' OR
                   last_name LIKE '%{$searchTerm}%' OR
                   middle_name LIKE '%{$searchTerm}%')";

    $query = mysqli_query($conn, $sql);
    $output = "";

    if (mysqli_num_rows($query) == 0) {
        // No matching buyers found
        $output .= "No matching buyers found.";
    } else {
        // Display search results for buyers
        while ($row = mysqli_fetch_assoc($query)) {
            // Generate HTML for each search result (clickable to start conversation)
            $offline = ($row['online_status'] == "0") ? "offline" : "";
            $output .= '<a href="chat.php?unique_id='. $row['unique_id'] .'">
                        <div class="content">
                        <img src="../'. $row['profile_picture'] .'" alt="Profile Picture">
                        <div class="details">
                            <span>'. $row['first_name'] .' '. $row['last_name'] .'</span>
                            <p>No messages yet</p> <!-- Placeholder for message content -->
                        </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
        }
    }

    // Output the search results for buyers
    echo $output;
} else {
    // If no search term is provided, handle existing conversations with buyers
    $sql = "SELECT DISTINCT buyer_accounts.id AS buyer_id, buyer_accounts.first_name, buyer_accounts.last_name, buyer_accounts.profile_picture, buyer_accounts.online_status, buyer_accounts.unique_id
            FROM buyer_accounts
            JOIN messages ON (messages.incoming_msg_id = buyer_accounts.unique_id OR messages.outgoing_msg_id = buyer_accounts.unique_id)
            AND (messages.outgoing_msg_id = '{$outgoing_id}' OR messages.incoming_msg_id = '{$outgoing_id}')
            WHERE buyer_accounts.unique_id != '{$outgoing_id}' AND (messages.incoming_msg_id = '{$outgoing_id}' OR messages.outgoing_msg_id = '{$outgoing_id}')
            ORDER BY messages.msg_id DESC";

    $query = mysqli_query($conn, $sql);
    $output = "";

    if (mysqli_num_rows($query) == 0) {
        // No conversations available with buyers
        $output .= "No conversations available with buyers.";
    } else {
        // Display existing conversations with buyers
        while ($row = mysqli_fetch_assoc($query)) {
            // Generate HTML for each conversation
            $offline = ($row['online_status'] == "0") ? "offline" : "";

            // Retrieve the last message for each conversation (assuming the message is stored in the 'msg' column)
            $last_message = ""; // Initialize the last message
            $is_outgoing = false; // Initialize the flag to check if the last message is outgoing

            $message_query = mysqli_query($conn, "SELECT msg, outgoing_msg_id FROM messages WHERE 
                    (outgoing_msg_id = '{$outgoing_id}' AND incoming_msg_id = '{$row['unique_id']}') 
                    OR 
                    (outgoing_msg_id = '{$row['unique_id']}' AND incoming_msg_id = '{$outgoing_id}')
                    ORDER BY msg_id DESC LIMIT 1");
            if (mysqli_num_rows($message_query) > 0) {
                $last_message_row = mysqli_fetch_assoc($message_query);
                $last_message = $last_message_row['msg']; // Retrieve the last message
                $is_outgoing = ($last_message_row['outgoing_msg_id'] == $outgoing_id);
            }

            // Generate the conversation list item for buyers
            $output .= '<a href="chat.php?unique_id='. $row['unique_id'] .'" data-buyer-id="'. $row['unique_id'] .'">
                        <div class="content">
                        <img src="../'. $row['profile_picture'] .'" alt="Profile Picture">
                        <div class="details">
                            <span>'. $row['first_name'] .' '. $row['last_name'] .'</span>
                            <p>'. ($last_message ? ($is_outgoing ? 'You: ' : '') . $last_message : 'No messages yet') .'</p>
                        </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
        }
    }

    // Output the conversation list (existing conversations with buyers)
    echo $output;
}
?>
