<?php
include_once('../php/config.php');

$outgoing_id = $_SESSION['unique_id'];

$sql = "SELECT DISTINCT seller_accounts.id AS seller_id, seller_accounts.shop_name, seller_accounts.shop_logo, seller_accounts.online_status
        FROM seller_accounts
        JOIN messages ON (messages.incoming_msg_id = seller_accounts.unique_id OR messages.outgoing_msg_id = seller_accounts.unique_id)
        AND (messages.outgoing_msg_id = '{$outgoing_id}' OR messages.incoming_msg_id = '{$outgoing_id}')
        WHERE seller_accounts.unique_id != '{$outgoing_id}' AND (messages.incoming_msg_id = '{$outgoing_id}' OR messages.outgoing_msg_id = '{$outgoing_id}')
        ORDER BY messages.msg_id DESC";

$query = mysqli_query($conn, $sql);
$output = "";

if (mysqli_num_rows($query) == 0) {
    $output .= "No conversations available with sellers.";
} elseif (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        // Now, you can directly use the seller information without checking messages
        $offline = ($row['online_status'] == "0") ? "offline" : "";

        $output .= '<a href="chat.php?user_id='. $row['seller_id'] .'">
                    <div class="content">
                    <img src="../'. $row['shop_logo'] .'" alt="Profile Picture">
                    <div class="details">
                        <span>'. $row['shop_name'] .'</span>
                        <p>'. $you . $msg .'</p>
                    </div>
                    </div>
                    <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                </a>';
    }
}

echo $output;
?>
