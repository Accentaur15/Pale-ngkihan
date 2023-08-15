<?php
session_start();
include_once('../php/config.php');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  // Retrieve and display the chat messages from the database
  $query = "SELECT * FROM support_messages ORDER BY date_sent ASC"; // Update the table name
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $message = $row['message'];
      $timestamp = $row['date_sent'];
      $senderType = $row['user_type'];

      // Determine the CSS class for styling based on sender type
      $messageClass = ($senderType === 'admin') ? 'admin-message' : 'user-message';

      // Format the message display
      $messageHtml = '<div class="chat">';
      $messageHtml .= '<p class="' . $messageClass . '">' . $message . '</p>';
      $messageHtml .= '<span class="timestamp">' . $timestamp . '</span>';
      $messageHtml .= '</div>';

      echo $messageHtml;
    }
  } else {
    echo '<div class="chat"><p class="no-messages">No messages yet.</p></div>';
  }
}
?>
