<?php
session_start();
include_once('../php/config.php');
include_once('../php/cart_functions.php');

// Function to get seller name
function getSellerName($seller_id, $conn)
{
    $seller_id = mysqli_real_escape_string($conn, $seller_id);
    $result = mysqli_query($conn, "SELECT shop_name FROM seller_accounts WHERE id = '{$seller_id}'");
    $row = mysqli_fetch_assoc($result);
    return $row['shop_name'] ?? '';
}

$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
    header("Location: ../buyerlogin.php");
    exit;
}

$qry = mysqli_query($conn, "SELECT * FROM buyer_accounts WHERE unique_id = '{$unique_id}'");

if (mysqli_num_rows($qry) > 0) {
    $row = mysqli_fetch_assoc($qry);
    if ($row) {
        $fname = $row['first_name'];
        $lname = $row['last_name'];
        $email = $row['email'];
        $profilePicture = $row['profile_picture'];
        $id = $row['id'];
        $_SESSION['id'] = $id;
        $delivery_address = $row['address'];
    }
}

$category_ids = isset($_GET['cids']) ? $_GET['cids'] : 'all';

// Get the sort option from the URL parameter
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : '';

$cartItemCount = getCartItemCount($conn, $id);
include_once('../php/notifications.php'); 
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buyer | Checkout</title>
</head>
  <!-- css style -->
  <link rel="stylesheet" href="../Assets/avatar.css">
<!-- Image Handling -->
<script src="../js/image.js"></script>
  <!-- DataTables -->
  <link rel="stylesheet" href="../Assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="../Assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="../Assets/plugins/fontawesome-free/css/all.min.css">
<script src="https://kit.fontawesome.com/0ad1512e05.js" crossorigin="anonymous"></script>
<!-- Theme style -->
<link rel="stylesheet" href="../Assets/dist/css/adminlte.min.css">
<!--css-->
<link href="../buyer/my_order.css" rel="stylesheet">
<!--fontawesome-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png" />
<link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png" />
<!--Animation-->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">




<!--Navigation Bar-->
<nav class="navbar navbar-expand-md navbar-light" style="background: rgb(229, 235, 232);">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.html">
            <img src="../Assets/logo/Artboard 1.png" class="logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
            <ul class="navbar-nav text-center">
                <li class="nav-item">
                    <a class="nav-link mx-3" aria-current="page" href="buyermain.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="../buyer/marketplace.php">Marketplace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  mx-3" href="../buyer/wholesale.php">Wholesale</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="buyeraboutus.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="../buyer/myorders.php">My Orders</a>
                </li>
                <?php include('../Assets/includes/notification.php');?>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="../buyer/cart.php"><i class="fa-solid fa-cart-shopping"></i>
                        <?php
                        if ($cartItemCount > 0) {
                            echo '<span class="badge bg-success position-absolute top-0 end-0">' . $cartItemCount . '</span>';
                        }
                        ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" href="../buyer/chat_users.php"><i class="fa-solid fa-message"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        echo '<img src="../' . $profilePicture . '" alt="Profile Picture" class="avatar-image img-fluid">';
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right custom-dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li class="dropdown-header text-center text-md font-weight-bold text-dark">
                            <?php echo $fname . ' ' . $lname; ?>
                        </li>
                        <li class="text-center"><a class="dropdown-item" href="buyermyaccount.php">My Account</a></li>
                        <li class="text-center"><a class="dropdown-item"
                                href="../php/logout.php?logout_id=<?php echo $unique_id ?>">Log out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!--End of Navigation Bar-->

<body class="d-flex flex-column min-vh-100">

<!-- Notification Section -->
<div class="container flex-grow-1">

<div class="content py-3">
    <div class="card card-outline card-success rounded-0 shadow">
        <div class="card-header">
            <h5 class="card-title"><b>All Notifications</b></h5>
        </div>
        <div class="card-body">


                        

               
                              <?php if (count($notifications) > 0) : ?>
<!-- Inside the loop -->
<?php foreach ($notifications as $notification) : ?>
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="card-text"><?php echo $notification['message']; ?></p>
                    <small class="text-muted"><?php echo $notification['timestamp']; ?></small>
                </div>
     
                    <a href="#" class="delete-notification" data-notification-id="<?php echo $notification['id']; ?>">
                        <i class="fas fa-trash-alt text-danger"></i> <!-- White delete icon -->
                    </a>
                
            </div>
        </div>
    </div>
<?php endforeach; ?>


                                <?php else : ?>
                                    <p>No notifications to display.</p>
                                <?php endif; ?>
                        
    
        </div>
    </div>
</div>

       <!-- REQUIRED SCRIPTS -->
        <!-- Bootstrap 5 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
            crossorigin="anonymous"></script>
        <!-- AdminLTE App -->
        <script src="../Assets/dist/js/adminlte.min.js"></script>
        <!--Animation java-->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <!-- SweetAlert2 -->
        <script src="../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="../Assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../Assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../Assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="../Assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="../Assets/plugins/jszip/jszip.min.js"></script>
        <script src="../Assets/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="../Assets/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
          <!-- bs-custom-file-input -->
<script src="../Assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script>
            AOS.init();

            $(document).ready(function() {
        // Define the showAlert function
        function showAlert(title, text, icon) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                position: 'top',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal-popup',
                    title: 'swal-title',
                    content: 'swal-text'
                }
            }).then(function () {
                // Reload the page after the SweetAlert is closed
                location.reload();
            });
        }

        // Handle notification deletion
        $('.delete-notification').click(function(e) {
            e.preventDefault();
            var notificationId = $(this).data('notification-id');

            // Show SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Confirm Deletion',
                text: 'Are you sure you want to delete this notification?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user confirms, send AJAX request to delete the notification
                    $.post('../php/delete_notification.php', { notification_id: notificationId }, function(data) {
                        if (data.success) {
                            console.log(data);
                            // Remove the deleted notification card from the DOM
                            $(e.target).closest('.card').remove();
                            // Show success notification
                            showAlert('Deleted!', 'The notification has been deleted.', 'success');
                        } else {
                            // Show error notification
                            console.error(data.error); 
                            showAlert('Deleted!', 'The notification has been deleted.', 'success');
                        }
                    }).done(function(response) {
    console.log(response); // Log the response to the console
});;
                }
            });
        });
    });

        // Define the showAlert function
        function showerror(title, text, icon) {
    Swal.fire({
      title: title,
      text: text,
      icon: icon,
      position: 'top',
      timer: 2000,
      showConfirmButton: false,
      toast: true,
      timerProgressBar: true,
      customClass: {
        popup: 'swal-popup',
        title: 'swal-title',
        content: 'swal-text'
      }
    });
  }

        </script>
    </div>
    <!-- Footer -->
        <!-- Footer -->
        <div class="copyright text-center text-white d-flex p-2">
    <div class="container">
        <small>Copyright &copy; Pale-ngkihan 2023</small>
        <hr class="mx-2">
        
        <a href="../buyer/support.php" class=" text-warning">
            <i class="fas fa-life-ring"></i> Get Support
        </a>
    </div>
</div>
</body>

</html>