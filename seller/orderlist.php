<?php



session_start();
include_once('../php/config.php');
$unique_id = $_SESSION['unique_id'];


if (empty($unique_id)) {
  header("Location: sellerlogin.php");
}

$qry = mysqli_query($conn, "SELECT * FROM seller_accounts WHERE unique_id = '{$unique_id}'");

if (mysqli_num_rows($qry) > 0) {
  $row = mysqli_fetch_assoc($qry);
  if ($row) {
    $shopname = $row['shop_name'];
    $shoplogo = $row['shop_logo'];
    $sellerid = $row['id'];
  }
}

?>
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seller | Order List</title>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--title icon-->
  <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png"/>
  <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png"/>
  <!-- DataTables -->
  <link rel="stylesheet" href="../Assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!--title icon-->
  <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png"/>
  <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png"/>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../Assets/plugins/fontawesome-free/css/all.min.css">
  <script src="https://kit.fontawesome.com/0ad1512e05.js" crossorigin="anonymous"></script>
  <!-- Theme style -->
  <link rel="stylesheet" href="../Assets/dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
<link rel="stylesheet" href="../Assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<!-- css style -->
    <link rel="stylesheet" href="../Assets/avatar.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Image Handling -->
<script src="../js/image.js"></script>




</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php 
include('../Assets/includes/topbar.php');
include('../Assets/includes/sidebar.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">




    <!-- Main content -->









    <div class="content mt-4">



      <div class="container-fluid">
      <div class="card rounded-0 shadow">
        <div class="card-header">
            <h5 class="card-title"><b>My Orders</b></h5>
        </div>
        <div class="card-body">
            <div class="w-100 overflow-auto">
            <table class="table table-bordered" id="productlist">
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
                    $stmt = $conn->prepare("SELECT * FROM `order_list` WHERE seller_id = ? ORDER BY `order_status` ASC, unix_timestamp(date_created) DESC");
                    $stmt->bind_param("i", $sellerid); // Assuming $id is an integer, change the type if it's not.
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
        <a class="dropdown-item view_order" href="#" data-toggle="modal" data-target="#orderModal" data-id="<?= $row['id'] ?>" data-code="<?= $row['order_code'] ?>"> 
  <span class="fa-solid fa-eye text-dark"></span> View
</a>
        </div>
    </div>
</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <?php if(mysqli_num_rows($orders) == 0): ?>
    <tr>
        <td colspan="6" class="text-center">There are currently no orders.</td>
    </tr>
<?php endif; ?>
            </table>
            </div>
        </div>
    </div>


   

<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Here, you can show the order details fetched via AJAX -->
        <!-- Use appropriate HTML structure to display the order data -->
        <div id="orderDetails"></div>
      </div>
    </div>
  </div>
</div>


        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <script>
  $(document).ready(function() {
    $("#productlist").DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    // Handle the click event of the "View" link
    $('.view_order').click(function() {
      var orderId = $(this).data('id');
      var orderCode = $(this).data('code');
     // console.log(orderId);
      //console.log(orderCode);
      // Use AJAX to fetch the order details
        // Load the order details into the modal
        $.ajax({
            url: '../seller/vieworder.php', // Replace this with the actual file URL that fetches order details
            type: 'GET',
            data: { id: orderId },
            dataType: 'html',
            success: function(response) {
                // Update the modal body with the order details
                $('#orderModal .modal-body').html(response);
                //console.log("Modal content updated");
                // Show the modal
                $('#orderModal').modal('show');
                //console.log("Modal shown");
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Error loading order details.');
            }
        });
    });
  });

  $('#orderModal').on('hide.bs.modal', function () {
    // Remove the modal backdrop when the modal is closed
    $('.modal-backdrop').remove();
});


    </script>


  <!-- Main Footer -->
  <footer class="main-footer text-center text-white" style = "background-color: #678A71;">
    <!-- Default to the left -->
    <strong>Copyright &copy; Pale-ngkihan 2023.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->


<!-- REQUIRED SCRIPTS -->
<!-- Bootstrap 4 -->
<script src="../Assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../Assets/dist/js/adminlte.min.js"></script>
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
<!-- SweetAlert2 -->
<script src="../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>



</script>



</body>
</html>