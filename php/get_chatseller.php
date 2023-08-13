<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['unique_id'])) {
    include_once "../php/config.php";

    // Get the outgoing and incoming user IDs
    $outgoing_id = $_SESSION['unique_id']; //sender
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']); //receiver

    $output = "";

    // Query to retrieve chat messages
    $sql = "SELECT * FROM messages 
    LEFT JOIN buyer_accounts ON buyer_accounts.unique_id = messages.outgoing_msg_id
    WHERE (messages.outgoing_msg_id = '{$outgoing_id}' AND messages.incoming_msg_id = '{$incoming_id}')
    OR (messages.outgoing_msg_id = '{$incoming_id}' AND messages.incoming_msg_id = '{$outgoing_id}')
    ORDER BY messages.msg_id";

    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            // Format the timestamp
            $timestamp = date("M j, Y, g:i A", strtotime($row['time_stamp']));

            // Check if the message is outgoing or incoming
            if ($row['outgoing_msg_id'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                            <div class="details">
                                <p>' . $row['msg'] . '</p>
                                <small class="text-muted"><i class="far fa-clock"></i> ' . $timestamp . '</small>
                            </div>
                            </div>';
            } else {
                $output .= '<div class="chat incoming">
                            <img src="../' . $row['profile_picture'] . '" alt="">
                            <div class="details">
                                <p>' . $row['msg'] . '</p>
                                <small class="text-muted"><i class="far fa-clock"></i> ' . $timestamp . '</small>
                            </div>
                            </div>';
            }
        }
    } else {
        // If no messages are available, display a message
        $output .= '<div class="text">No messages are available. Once you send messages, they will appear here.</div>';
    }

    // Output the formatted chat messages
    echo $output;
}
?>
