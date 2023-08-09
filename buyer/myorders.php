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
                    <a class="nav-link active mx-3" href="#">My Orders</a>
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

    <!-- Modal for displaying order details -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
            </div>
            <div class="modal-body">
                <!-- Order details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div class="container flex-grow-1">

<div class="content py-3">
    <div class="card card-outline card-success rounded-0 shadow">
        <div class="card-header">
            <h5 class="card-title"><b>My Orders</b></h5>
        </div>
        <div class="card-body">
            <div class="w-100 overflow-auto">
            <table class="table table-bordered table-striped">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="20%">
                    <col width="20%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="p1 text-center">#</th>
                        <th class="p1 text-center">Date Ordered</th>
                        <th class="p1 text-center">Ref. Code</th>
                        <th class="p1 text-center">Total Amount</th>
                        <th class="p1 text-center">Status</th>
                        <th class="p1 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $stmt = $conn->prepare("SELECT * FROM `order_list` WHERE buyer_id = ? ORDER BY `order_status` ASC, unix_timestamp(date_created) DESC");
                    $stmt->bind_param("i", $id); // Assuming $id is an integer, change the type if it's not.
                    $stmt->execute();
                    $orders = $stmt->get_result();
                    while($row = $orders->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="px-2 py-1 align-middle text-center"><?= $i++; ?></td>
                        <td class="px-2 py-1 align-middle"><?= date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                        <td class="px-2 py-1 align-middle"><?= $row['order_code'] ?></td>
                        <td class="px-2 py-1 align-middle text-right"><?= $row['total_amount'] ?></td>
                        <td class="px-2 py-1 align-middle text-center">
                            <?php 
                                switch($row['order_status']){
                                    case 0:
                                        echo '<span class="badge badge-secondary bg-gradient-secondary px-3 rounded-pill">Pending</span>';
                                        break;
                                    case 1:
                                        echo '<span class="badge badge-primary bg-gradient-primary px-3 rounded-pill">Confirmed</span>';
                                        break;
                                    case 2:
                                        echo '<span class="badge badge-info bg-gradient-info px-3 rounded-pill">Packed</span>';
                                        break;
                                    case 3:
                                        echo '<span class="badge badge-warning bg-gradient-warning px-3 rounded-pill">Out for Delivery</span>';
                                        break;
                                    case 4:
                                        echo '<span class="badge badge-success bg-gradient-success px-3 rounded-pill">Delivered</span>';
                                        break;
                                    case 5:
                                        echo '<span class="badge badge-danger bg-gradient-danger px-3 rounded-pill">Cancelled</span>';
                                        break;
                                    default:
                                        echo '<span class="badge badge-light bg-gradient-light border px-3 rounded-pill">N/A</span>';
                                        break;
                                }
                            ?>
                        </td>
                        <td class="px-2 py-1 align-middle text-center">
    <div class="dropdown">
        <button class="btn btn-flat border btn-light btn-sm dropdown-toggle dropdown-icon" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Action
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-code="<?= $row['order_code'] ?>">
                <span class="fa-solid fa-eye text-dark"></span> View
            </a>
        </div>
    </div>
</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // DataTables initialization
    $('.table').DataTable({
    "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
  });

    // Click event for the "View" action button
    $('.view_data').click(function() {
        var orderId = $(this).data('id'); // Get the order ID from data-id attribute
        var orderCode = $(this).data('code'); // Get the order code from data-code attribute

        // Load the order details into the modal
        $.ajax({
            url: '../buyer/view_order.php', // Replace this with the actual file URL that fetches order details
            type: 'GET',
            data: { id: orderId },
            dataType: 'html',
            success: function(response) {
                // Update the modal body with the order details
                $('#orderModal .modal-body').html(response);

                // Show the modal
                $('#orderModal').modal('show');
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Error loading order details.');
            }
        });
    });

    


});


</script>


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
        </script>
    </div>
    <!-- Footer -->
    <footer class="copyright py-4 text-center text-white  p-2">
        <div class="container">
            <small>&copy; Pale-ngkihan 2023</small>
        </div>
    </footer>
</body>

</html>
