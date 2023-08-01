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
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seller | Category List</title>

    


    <!-- summernote -->
    <link rel="stylesheet" href="../Assets/plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../Assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../Assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../Assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!--title icon-->
    <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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


          
       

                    
                   
                    <div class="card">
                  
                        <div class="card-header">
                            <h4 class="card-title">List of Categories</h4>
                           

                        </div>
                        <div class="card-body">
                            <table id="productlist" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date Created</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM category_list";
                                    $query_run = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        foreach ($query_run as $row) {
                                            // Exclude the row with the specific $unique_id
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?= $row['id']; ?>
                                                    </td>
                                                    <td><?= $row['date_created']; ?></td>
                                                    <td><?= $row['name']; ?></td>
                                                    <td><?= $row['description']; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($row['status'] == '1') {
                                                            echo '<span class="badge badge-success px-3 rounded-pill">Active</span>';
                                                        } else if ($row['status'] == '2') {
                                                            echo '<span class="badge badge-danger px-3 rounded-pill">Inactive</span>';
                                                        }
                                                        ?>
                                                    </td>

                                                </tr>
                                                <?php
                                            
                                        }
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




        <?php
        include('../Assets/includes/footer.php');
        ?>