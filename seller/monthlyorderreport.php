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
  <title>Seller | Monthly Order Report</title>

  <!--title icon-->
  <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png"/>
  <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png"/>
  <!-- summernote -->
  <link rel="stylesheet" href="../Assets/plugins/summernote/summernote-bs4.min.css">
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
  <!-- css style -->
  <link rel="stylesheet" href="../Assets/avatar.css">
  <!-- Image JS -->
  <script src="../js/imagehandling.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>




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
      <?php $month = isset($_GET['month']) ? $_GET['month'] : date("Y-m"); ?>
<div class="content py-3">
    <div class="card card-outline card-success shadow rounded-0">
        <div class="card-header">
            <h5 class="card-title">Monthly Order Reports</h5>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="callout callout-primary shadow rounded-0">
                    <form action="" id="filter">
                        <div class="row align-items-end">
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="month" class="control-label">Month</label>
                                    <input type="month" name="month" id="month" value="<?= $month ?>" class="form-control rounded-0" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <button class="btn btn-success btn-flat btn-sm"><i class="fa fa-filter"></i> Filter</button>
                                    <button class="btn btn-light border btn-flat btn-sm" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="clear-fix mb-3"></div>
                    <div id="outprint">
                    <table class="table table-bordered table-stripped" id="monthlyorder">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="20%">
                            <col width="25%">
                            <col width="20%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr class="">
                                <th class="text-center align-middle py-1">#</th>
                                <th class="text-center align-middle py-1">Date Created</th>
                                <th class="text-center align-middle py-1">Ref. Code</th>
                                <th class="text-center align-middle py-1">Buyer</th>
                                <th class="text-center align-middle py-1">Status</th>
                                <th class="text-center align-middle py-1">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $total = 0;
                            $orders = $conn->query("SELECT o.*, o.order_code as ordercode, CONCAT(c.last_name, ', ', c.first_name, ' ', COALESCE(c.middle_name, '')) as buyer FROM `order_list` o INNER JOIN buyer_accounts c ON o.buyer_id = c.id WHERE o.seller_id = '$sellerid' AND DATE_FORMAT(o.date_created, '%Y-%m') = '$month' ORDER BY UNIX_TIMESTAMP(o.date_created) DESC ");
                            while($row = $orders->fetch_assoc()):
                                $total += $row['total_amount'];
                            ?>
                                <tr>
                                    <td class="text-center align-middle px-2 py-1"><?php echo $i++; ?></td>
                                    <td class="align-middle px-2 py-1"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                    <td class="align-middle px-2 py-1"><?= $row['order_code'] ?></td>
                                    <td class="align-middle px-2 py-1"><?php echo ucwords($row['ordercode'].' - '.$row['buyer']) ?></td>
                                    <td class="text-center align-middle px-2 py-1">
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
                                    <td class="text-right align-middle px-2 py-1"><?php echo$row['total_amount'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-center px-1 py-1 align-middel" colspan="5">Total</th>
                                <th class="text-right px-1 py-1 align-middel"><?= $total ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<noscript id="print-header">
<style>
    #sys_logo{
        width:5em !important;
        height:5em !important;
        object-fit:scale-down !important;
        object-position:center center !important;
    }
</style>
<div class="d-flex align-items-center">
    <div class="col-auto text-center pl-4">
        <img src="../Assets/logo/logo P whitebg.png" alt=" System Logo" id="sys_logo" class="img-circle border border-dark">
    </div>
    <div class="col-auto flex-shrink-1 flex-grow-1 px-4">
        <h4 class="text-center m-0">PALE-NGKIHAN:ONLINE MARKET SYSTEM FOR ARAYAT RICE TRADERS</h4>
        <h3 class="text-center m-0"><b>Order Report</b></h3>
        <h5 class="text-center m-0">For the Month of</h5>
        <h5 class="text-center m-0"><?= date("F Y", strtotime($month)) ?></h5>
    </div>
</div>
<hr>
</noscript>


  



        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php
    include('../Assets/includes/footer.php');
?>
    <!-- REQUIRED SCRIPTS -->


    <script>
  $(document).ready(function() {
    $("#monthlyorder").DataTable({
     "paging": false,
      "lengthChange": true,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": true,
    });
});
    function start_loader() {
        // Add your code to show a loading indicator (if any)
        // For example, you can display a spinner or loading overlay.
    }

    function end_loader() {
        // Add your code to hide the loading indicator (if any)
        // For example, you can remove the spinner or loading overlay.
    }

    $(function(){
        $('#filter-btn').click(function(e){
            e.preventDefault();
            start_loader();
            var month = $("#month").val();
            location.href = "../seller/monthlyorderreport.php?month=" + month;
        });

        $('#print').click(function(){
            start_loader();
            var head = $('head').clone()
            var p = $('#outprint').clone()
            var el = $('<div>')
            var header =  $($('noscript#print-header').html()).clone()
            head.find('title').text("Orders Montly Report - Print View")
            el.append(head)
            el.append(header)
            el.append(p)
            var nw = window.open("","_blank","width=1000,height=900,top=50,left=75")
                    nw.document.write(el.html())
                    nw.document.close()
                    setTimeout(() => {
                        nw.print()
                        setTimeout(() => {
                            nw.close()
                            end_loader()
                        }, 200);
                    }, 500);
        })
    })
 
</script>