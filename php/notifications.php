<?php
// Modify this section after fetching the user's details
// Add the following code to fetch notifications


function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
  
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
  
    $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
    );
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
        unset($string[$k]);
      }
    }
  
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

$qry_notifications = mysqli_query($conn, "SELECT * FROM notifications WHERE buyer_id = '{$id}' ORDER BY timestamp DESC");
$notifications = mysqli_fetch_all($qry_notifications, MYSQLI_ASSOC);

?>