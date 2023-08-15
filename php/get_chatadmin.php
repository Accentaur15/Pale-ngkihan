<?php
session_start();
include_once('../php/config.php');

if (isset($_POST['incoming_id'])) {
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $outgoing_id = "admin";

    // Fetch chat messages between admin and the other user
    $sql = "SELECT * FROM support_messages 
            WHERE (user_type = 'admin' AND unique_id = '$outgoing_id') 
            OR (user_type != 'admin' AND unique_id = '$incoming_id') 
            ORDER BY date_sent ASC";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $message = htmlspecialchars($row['message']);
            $timestamp = $row['date_sent'];
            $user_type = $row['user_type'];

            if ($user_type == "admin") {
                echo "<div class='chat outgoing'>
                          <div class='details'>
                              <p>$message</p>
                              <small class='text-muted'><i class='far fa-clock'></i> $timestamp</small>
                          </div>
                      </div>";
            } else {
                if ($user_type == "buyer") {
                    $user_query = mysqli_query($conn, "SELECT * FROM buyer_accounts WHERE unique_id = '{$incoming_id}'");
                } elseif ($user_type == "seller") {
                    $user_query = mysqli_query($conn, "SELECT * FROM seller_accounts WHERE unique_id = '{$incoming_id}'");
                }

                if (mysqli_num_rows($user_query) > 0) {
                    $user_row = mysqli_fetch_assoc($user_query);
                    $imageSrc = ($user_type == "buyer") ? "../" . $user_row['profile_picture'] : "../" . $user_row['shop_logo'];

                    echo "<div class='chat incoming'>
                              <img src='$imageSrc' alt='Profile Picture or Shop Logo'>
                              <div class='details'>
                                  <p>$message</p>
                                  <small class='text-muted'><i class='far fa-clock'></i> $timestamp</small>
                              </div>
                          </div>";
                }
            }
        }
    } else {
        echo "<p class='no-messages'>No messages yet</p>";
    }
}
?>
