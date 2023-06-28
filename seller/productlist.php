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
  <title>Seller | Product List</title>


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
  <link rel="stylesheet" href="#">
  <!-- Image JS -->
  <script>
    function previewFile(imageId, input) {
        var file = input.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function () {
            var image = document.getElementById(imageId);
            image.src = reader.result;
            };
            reader.readAsDataURL(file);
        }
        }

        function showFullImage(img) {
        var overlay = document.createElement("div");
        overlay.className = "overlay";
        overlay.onclick = function() {
        document.body.removeChild(overlay);
        };

        var fullImage = document.createElement("img");
        fullImage.className = "full-image";
        fullImage.src = img.src;

        overlay.appendChild(fullImage);
        document.body.appendChild(overlay);
        }
        </script>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php 
include('../Assets/includes/topbar.php');
include('../Assets/includes/sidebar.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Modal -->
    <div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <form action="#">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label class="form-label" for="">Product Name</label>
                                    <input name="productname" type="text" class="form-control" placeholder="Enter Product Name" required>
                            </div>

                            <div class="form-group">
                                        <label class="form-label" for="">Price</label>
                                        <input name="productprice" type="number" class="form-control" placeholder="Enter Product Price" required>
                            </div>

                            <div class="form-group">
                                        <label class="form-label" for="">Product Picture</label>   
                                        <input name="proudctpicture" class="form-control form-control-sm" type="file"
                              onchange="previewFile('previewproduct', this);" required/>
                            </div>
                           
                                            <img src="../seller_profiles/no-image-available.png" alt="Product Picture"
                                    id="previewproduct" class="border border-gray img-thumbnail mb-2"
                                    onclick="showFullImage(this)">
                                        

                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="">Category</label>
                                    <select name="productcategory" class="form-control" required>
                                                <option value="" selected disabled>Select Category</option>
                                                <option value="">Example1</option>
                                                <option value="">Example2</option>
                                    </select>
                                </div>

                            <div class="form-group">
                                <label class="form-label" for="">Quantity</label>
                                <input type="number" class="form-control" placeholder="Enter Product Quantity" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="">Status</label>
                                    <select name="status" class="form-control" required>
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                    </select>
                                </div>

                        </div>

                        
                    </div>

                    <div class="form-group">
                    <label class="form-label" for="">Description</label>
                    <textarea name="description" id="summernote" class="form-control" rows="4"></textarea>
                    </div>
                    
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success">Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Main content -->
    <div class="content mt-4">
      <div class="container-fluid">




        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List of Products</h4>
                <a href="#" data-toggle="modal" data-target="#adduser" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i>&nbsp;Add Product</a>
            </div>
            <div class="card-body">
            <table id="productlist" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Created</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td>4</td>
                        <td>5</td>
                        <td>6</td>
                        <td>7</td>
                        <td>8</td>
                    </tr>
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