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
        <?php include('../php/message.php');?>
            <div class="card-header">
                <h4 class="card-title">List of Products</h4>
            </div>
            <div class="card-body">
            <table id="productlist" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Created</th>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                                    $query = "SELECT * FROM product_list";
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
                                                    <td class="text-center align-middle"><img class="product-image border border-gray img-thumbnail product-img" src="<?="../" . $row['product_image']; ?>"  alt="Profile Image"  onclick="showFullImage(this)"></td>
                                                    <td><?= $row['product_name']; ?></td>
                                                    <td>
                                                    <?php
                                                        // Retrieve the category name based on the category ID
                                                        $category_id = $row['category_id'];
                                                        $selectQuery = "SELECT name FROM category_list WHERE id = $category_id";
                                                        $result = mysqli_query($conn, $selectQuery);
                                                        if ($result && mysqli_num_rows($result) > 0) {
                                                            $category = mysqli_fetch_assoc($result);
                                                            $category_name = $category['name'];
                                                            echo $category_name;
                                                        } else {
                                                            echo "Category not found";
                                                        }
                                                    ?>
                                                    </td>
                                                    <td><?= $row['product_description']; ?></td>
                                                    <td><?= $row['price']; ?></td>
                                                    <td>                                                
                                                    <?php
                                                        if ($row['unit'] == 'kg') {
                                                            echo 'Per Kg';
                                                        } else if ($row['unit'] == 'sack') {
                                                            echo 'Per Sack';
                                                        }
                                                    ?>
                                                        </td>
                                                    <td class="text-center align-content-center"><?= $row['quantity']; ?></td>
                                                    <td class="text-center align-content-center">
                                                        <?php
                                                        if ($row['status'] == '1') {
                                                            echo '<span class="badge badge-success px-3 rounded-pill">Active</span>';
                                                        } else if ($row['status'] == '2') {
                                                            echo '<span class="badge badge-danger px-3 rounded-pill">Inactive</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"
                                                        aria-expanded="false" >
                                                        Action
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item keychainify-checked" href="../admin/edit-productadmin.php?id=<?= $row['id'];?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="../php/deleteproduct.php" method="POST">
                                                            <button type="submit" name="product_delete" value="<?= $row['id']; ?>"
                                                                class="dropdown-item delete_data keychainify-checked">
                                                                <span class="fa fa-trash text-danger"></span> Delete
                                                            </button>
                                                        </form>
                                                    </div>
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
            xhr.open("POST", "../php/addproductlist.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response.trim(); // Trim whitespace from the response
                        let comparisonResult = data.includes("updated");
                        console.log(data);
                        console.log(comparisonResult);
                        if (comparisonResult) {    
                            window.location.reload(true);
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