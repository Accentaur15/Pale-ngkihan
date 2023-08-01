<!-- Notifications Dropdown Menu -->
<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <?php
        // Count only unseen notifications
        $unseenNotificationCount = 0;
        foreach ($notifications as $notification) {
            if ($notification['is_seen'] !== '1') {
                $unseenNotificationCount++;
            }
        }

        if ($unseenNotificationCount > 0) {
            echo '<span class="badge badge-warning navbar-badge">' . $unseenNotificationCount . '</span>';
        }
        ?>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right custom-dropdown-menu">
        <?php
        if ($unseenNotificationCount > 0) {
            echo '<span class="dropdown-header">' . $unseenNotificationCount . ' Unseen Notifications</span>';
            echo '<div class="dropdown-divider"></div>';

            // Set the maximum number of unseen notifications to show
            $maxUnseenNotificationsToShow = 5;
            $unseenNotificationCounter = 0;

            foreach ($notifications as $notification) {
                if ($unseenNotificationCounter >= $maxUnseenNotificationsToShow) {
                    break; // Stop the loop if we reach the maximum limit
                }

                // Check if the buyer_id matches the current buyer's id and is_unseen is not equal to 1
                if ($notification['buyer_id'] === $id && $notification['is_seen'] !== '1') {
                    $notificationTitle = $notification['notification_title']; // Get the notification title
                    $notificationMessage = $notification['message'];
                    $truncatedMessage = (strlen($notificationMessage) > 30) ? substr($notificationMessage, 0, 30) . "..." : $notificationMessage;
                    echo '<a href="../buyer/myorders.php" class="dropdown-item notification-item" data-notification-id="' . $notification['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($notificationMessage) . '">';
                    echo '  <strong>' . $notificationTitle . '</strong>';
                    echo '  <span class="float-right text-sm text-muted">' . time_elapsed_string($notification['timestamp']) . '</span>';
                    echo '  <div class="dropdown-divider"></div>';
                    echo '  <span class="d-inline-block text-muted" style="max-width: 200px;">' . $truncatedMessage . '</span>';
                    echo '</a>';
                    echo '<div class="dropdown-divider"></div>';

                    $unseenNotificationCounter++;
                }
            }
        } else {
            echo '<a href="#" class="dropdown-item">No new notifications</a>';
        }
        ?>
        <a href="../buyer/see_all_notifications.php" class="dropdown-footer">See All Notifications</a>
    </div>
</li>

<script>
    // Add event listener for the click on the notification item
    document.addEventListener('DOMContentLoaded', function () {
        const notificationItems = document.querySelectorAll('.notification-item');

        notificationItems.forEach(function (item) {
            item.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the default link behavior

                // Get the notification ID from the data attribute
                const notificationId = item.getAttribute('data-notification-id');

                // Make an AJAX request to update the is_seen status
                fetch('../php/seennotification.php', {
                    method: 'POST', // Or 'GET', depending on your server-side implementation
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'notification_id=' + encodeURIComponent(notificationId)
                })
                .then(function (response) {
                    // Parse the response as JSON
                    return response.json();
                })
                .then(function (data) {
                    // Handle the response data
                    if (data.status === 'success') {
                        // Update the UI or perform any other actions to indicate that the notification has been seen
                        console.log('Notification with ID ' + notificationId + ' marked as seen.');
                        // For example, you can remove the notification item from the dropdown
                        window.location.href = "../buyer/myorders.php";
                        // Update the badge count
                        $unseenNotificationCount--;
                        if ($unseenNotificationCount > 0) {
                            document.querySelector('.navbar-badge').innerText = $unseenNotificationCount;
                        } else {
                            document.querySelector('.navbar-badge').remove();
                        }
                    } else {
                        console.error('Failed to mark notification as seen.');
                    }
                })
                .catch(function (error) {
                    console.error('An error occurred:', error);
                });
            });
        });
    });
</script>
