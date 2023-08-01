<?php



session_start();
include_once('../php/config.php');
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
    header("Location: ../admin/admin.php");
}

$qry = mysqli_query($conn, "SELECT * FROM admin_accounts WHERE id = '{$unique_id}'");

if (mysqli_num_rows($qry) > 0) {
    $row = mysqli_fetch_assoc($qry);
    if ($row) {
        $fname = $row['first_name'];
        $lname = $row['last_name'];
        $username = $row['username'];
        $profilePicture = $row['profile_picture'];
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
  <title>Admin | Product List</title>


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

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php 
include('../Assets/includes/topbaradmin.php');
include('../Assets/includes/sidebaradmin.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content mt-4">
      <div class="container-fluid">


      <div class="card">
            <div class="card-header">
                <h4>Edit Product</h4>
            </div>
            <div class="card-body">

            <?php 
                if(isset($_GET['id']))
                {
                    $user_id = $_GET['id'];
                    $users = "SELECT * FROM product_list WHERE id='$user_id'";
                    $users_run = (mysqli_query($conn, $users));

                    if(mysqli_num_rows($users_run) > 0)
                    {
                        foreach($users_run as $user)
                            ?>
                        <div class="this">
                        <form action="#" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?=$user['id'];?>"/>
                        <div class="error-text alert alert-danger text-center fs-5" style="display:none;">Error</div>
                            <div class="form-group">
                                <label class="form-label" for="">Product Name</label>
                                <input name="productname" type="text" class="form-control" placeholder="Enter Product Name" value="<?=$user['product_name'];?>"/>
                            </div>
                            <div class="form-group">
                                            <label class="form-label" for="">Category</label>
                                            <select name="productcategory" class="form-control">
                                                <option value="" selected disabled>Select Category</option>
                                                <?php
                                                $categoryQuery = mysqli_query($conn, "SELECT * FROM category_list");
                                                while ($categoryRow = mysqli_fetch_assoc($categoryQuery)) {
                                                    $categoryId = $categoryRow['id'];
                                                    $categoryName = $categoryRow['name'];
                                                    $selected = ($categoryId == $user['category_id']) ? 'selected' : '';
                                                    echo "<option value='$categoryId' $selected>$categoryName</option>";
                                                }
                                                ?>
                                            </select>
                            </div>
                            <div class="form-group">
                                            <label class="form-label" for="price">Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="number" class="form-control" id="price" name="productprice" placeholder="Enter Product Price" value="<?=$user['price'];?>"/>
                                                <select class="form-control" id="unit" name="unit" >
                                                <option value="kg" <?php echo ($user['unit'] === 'kg') ? 'selected' : ''; ?>>Per Kg</option>
                                                <option value="sack" <?php echo ($user['unit'] === 'sack') ? 'selected' : ''; ?>>Per Sack</option>
                                                    <!-- Add more options as needed -->
                                                </select>
                                            </div>
                            </div>

                            <div class="form-group">
                                            <label class="form-label" for="">Quantity</label>
                                            <input name="quantity" type="number" class="form-control" placeholder="Enter Product Quantity" value="<?=$user['quantity'];?>" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="">Description</label>
                                            <textarea name="description" id="summernoteproductedit" class="form-control" rows="4" data-value="<?= htmlentities($user['product_description']); ?>"></textarea>
                                        </div>
                                                <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                            <label class="form-label" for="">Product Picture</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="productpicture" class="custom-file-input" type="file" onchange="previewFile('previewproduct', this);" />
                                                    <label class="custom-file-label" for="exampleInputFile"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mx-auto">
                                        <img src="../<?=$user['product_image'];?>" alt="Product Picture" id="previewproduct" class="border border-gray img-thumbnail mb-2" onclick="showFullImage(this)">
                                        </div>
                                       
                                    </div>
                                                
                                                



                                <hr class="mx-n3">
                                <div class="col-md-2 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="" selected disabled>Select State</option>
                                    <option value="1" <?php if ($user['status'] === '1') echo 'selected'; ?>>Active</option>
                                    <option value="2" <?php if ($user['status'] === '2') echo 'selected'; ?>>Inactive</option>
                                 </select>
                            </div>
                                        
                        
            
                            <?php
                    }

                    
                }
            ?>

           
            
            <div class="card-footer">
               <button name="submit" type="submit" class="buttons btn btn-success btn-md">Update</button>
               <a href="../admin/productlist.php" class="btn btn-secondary btn-md">Cancel</a>
            </div>
            </form>
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
        const form = document.querySelector('.this form');
        const submitBtn = form.querySelector('.buttons');
        const errorText = form.querySelector('.error-text');

        form.onsubmit = (e) => {
            e.preventDefault();
        }

        submitBtn.onclick = () => {
            // Start AJAX
            let xhr = new XMLHttpRequest(); // Create XML object
            xhr.open("POST", "../php/editproductlist.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response.trim(); // Trim whitespace from the response
                        let comparisonResult = data.includes("updated");
                        if (comparisonResult) {
                            //errorText.textContent = data;
                           // errorText.style.display = "block";
                            window.location.href = "../admin/productlist.php";
                        } else {
                            errorText.textContent = data;
                            errorText.style.display = "block";
                        }
                    }
                }
            }
            // Send data through AJAX to PHP
            let formData = new FormData(form); // Create new FormData object from the form data
            xhr.send(formData); // Send data to PHP
        }


    </script>

<?php
    include('../Assets/includes/footer.php');
?>