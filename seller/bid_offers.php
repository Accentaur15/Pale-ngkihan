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
  <title>Seller | Bid Offers List</title>

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
<!-- SweetAlert2 -->
<link rel="stylesheet" href="../Assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">



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


      <div class="card">
            <div class="card-header">
              <h4 class="card-title">Bid Offers List</h4>
             
            </div>
            <div class="card-body">
              <table id="BidOffersListTable" class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Bid Code</th>
                    <th>Harvest Schedule</th>
                    <th>Date</th>
                    <th>Buyer</th>
                    <th>Bid Amount</th>
                    <th>Bid Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php

$bidQuery = mysqli_query($conn, "
SELECT bb.id AS bid_id, bb.bid_code, bb.bid_amount, bb.buyer_id, hs.date_scheduled, bb.bid_status, hs.rice_type, hs.date_scheduled, hs.id AS harvest_schedule_id
FROM buyer_bids bb
INNER JOIN harvest_schedule hs ON bb.harvest_schedule_id = hs.id
WHERE hs.seller_id = '{$sellerid}'
");

if (mysqli_num_rows($bidQuery) > 0) {
    $count = 1;
    while ($bidRow = mysqli_fetch_assoc($bidQuery)) {
      $harvestScheduleId = $bidRow['harvest_schedule_id'];
      $bidId = $bidRow['bid_id'];
      $bidCode = $bidRow['bid_code'];
      $buyerId = $bidRow['buyer_id'];
      $bidAmount = $bidRow['bid_amount'];
      $bidstatus = $bidRow['bid_status'];
      $typeofrice = $bidRow['rice_type'];
      $date = $bidRow['date_scheduled'];
      $dateFormatted = date("F j, Y", strtotime($date));

      $buyerInfoQuery = mysqli_query($conn, "SELECT first_name, middle_name, last_name FROM buyer_accounts WHERE id = '{$buyerId}'");
      if (mysqli_num_rows($buyerInfoQuery) > 0) {
        $buyerInfo = mysqli_fetch_assoc($buyerInfoQuery);
        $buyerName = $buyerInfo['first_name'] . ' ' . $buyerInfo['middle_name']. '. ' . $buyerInfo['last_name'];
      } else {
        $buyerName = 'N/A';
      }
     
      
      ?>
                      <tr>
                        <td><?= $count++; ?></td>
                        <td><?=$bidCode ?></td>
                        <td><?=$typeofrice ?></td>
                        <td><?=$dateFormatted?></td>
                        <td><?=$buyerName ?></td>
                        <td><?= ($bidRow['bid_amount'] !== null ? '₱' . $bidRow['bid_amount'] : 'None') ?></td>
                       <td class="text-center">  <?php
  // Check the status value and set the appropriate badge class
  $bidstatus = strtolower($bidstatus);
  switch ($bidstatus) {
    case 'pending':
      echo '<span class="badge badge-warning px-3 rounded-pill">Pending</span>';
      break;
    case 'accepted':
      echo '<span class="badge badge-success px-3 rounded-pill">Accepted</span>';
      break;
    case 'rejected':
      echo '<span class="badge badge-danger px-3 rounded-pill">Rejected</span>';
      break;
      case 'canceled':
        echo '<span class="badge badge-secondary px-3 rounded-pill">Canceled</span>';
        break;
    default:
      echo '<span class="badge badge-secondary px-3 rounded-pill">Unknown</span>';
      break;
  }
  ?></td>
                      
                      <td class="text-center">
  <div class="btn-group">
    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
      Action
      <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu dropdown-menu-right" role="menu">
      <button class="dropdown-item accept-bid-btn" data-bid-id="<?= $bidId; ?>" data-harvest-schedule-id="<?= $harvestScheduleId; ?>">
        <i class="fas fa-check text-success"></i> Accept
      </button>
      <div class="dropdown-divider"></div>
      <button class="dropdown-item reject-bid-btn" data-bid-id="<?= $bidId; ?>">
        <i class="fas fa-times text-danger"></i> Reject
      </button>
      <div class="dropdown-divider"></div>
      <button class="dropdown-item cancel-bid-btn" data-bid-id="<?= $bidId; ?>">
  <i class="fas fa-ban text-warning"></i> Cancel
</button>
    </div>
  </div>
</td>


                      </tr>
                      <?php
                    }
                  } else {
                    echo '<tr><td colspan="7" class="text-center">No Bid Offers Found.</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
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

// Define the showerror function
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


<?php
    include('../Assets/includes/footer.php');
?>