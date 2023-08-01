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
include_once('../php/sellercount.php');

?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seller | Dashboard</title>
  
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

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php 
include('../Assets/includes/topbar.php');
include('../Assets/includes/sidebar.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Seller Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
          </div>
          <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">

          <div class="col-12 col-lg-6 col-xl-6">
            <div class="info-box custom-info-box">
              <span class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-th-list"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Categories</span>
                <span class="info-box-number text-right h4"><?php echo $categoryCount;?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>
          
          <div class="col-12 col-lg-6 col-lg-6">
            <div class="info-box custom-info-box">
              <span class="info-box-icon bg-gradient elevation-1" style="background-color: #C5BA92;"><i class="fa-solid fa-boxes-stacked" style="color: #ffffff;"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Products</span>
                <span class="info-box-number text-right h4"><?php echo $productCount?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>
          
          <div class="col-12 col-lg-6 col-lg-6">
            <div class="info-box custom-info-box">
              <span class="info-box-icon bg-gradient-warning elevation-1"><i class="fas fa-list"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Pending Orders</span>
                <span class="info-box-number text-right h4">4</span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-12 col-lg-6 col-lg-6">
            <div class="info-box custom-info-box">
              <span class="info-box-icon bg-gradient-info elevation-1"> <i class="nav-icon fas fa-star"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Ratings & Reviews</span>
                <span class="info-box-number text-right h4">4</span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>
          


          <!-- /.col-md-6 -->
          <div class="col-lg-6">

          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php
    include('../Assets/includes/footer.php');
?>