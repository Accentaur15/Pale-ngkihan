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
// Fetch category data
$categoryQuery = mysqli_query($conn, "SELECT * FROM category_list");
$categoryOptions = '';

while ($categoryRow = mysqli_fetch_assoc($categoryQuery)) {
    $categoryId = $categoryRow['id'];
    $categoryName = $categoryRow['name'];
    $categoryOptions .= "<option value='$categoryId'>$categoryName</option>";

}
?>
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seller | Product List</title>

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

  
    <!-- Modal -->
    <div class="this">
        <form action="../php/addproductlist.php" method="POST" enctype="multipart/form-data">
            <div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="error-text alert alert-danger text-center fs-5" style="display:none;">Error</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="sellerid" type="hidden" name="user_id" value="<?=$sellerid?>"/>

                                        <div class="form-group">
                                            <label class="form-label" for="">Product Name</label>
                                            <input name="productname" type="text" class="form-control" placeholder="Enter Product Name" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="price">Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="number" class="form-control" id="price" name="productprice" placeholder="Enter Product Price" required>
                                                <select class="form-control" id="unit" name="unit" required>
                                                    <option value="" selected disabled>Select Unit</option>
                                                    <option value="kg">Per Kg</option>
                                                    <option value="sack">Per Sack</option>
                                                    <!-- Add more options as needed -->
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="">Product Picture</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="productpicture" class="custom-file-input" type="file" onchange="previewFile('previewproduct', this);" required/>
                                                    <label class="custom-file-label" for="exampleInputFile"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <img src="../seller_profiles/no-image-available.png" alt="Product Picture" id="previewproduct" class="border border-gray img-thumbnail mb-2" onclick="showFullImage(this)">
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="">Category</label>
                                            <select name="productcategory" class="form-control" required>
                                                <option value="" selected disabled>Select Category</option>
                                                <?php echo $categoryOptions; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="">Quantity</label>
                                            <input name="quantity" min = "1" type="number" class="form-control" placeholder="Enter Product Quantity" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="">Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="2">Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="">Description</label>
                                            <textarea name="description" id="summernote" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button name="submit" type="submit" class="buttons btn btn-success">Save</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- Main content -->
    <div class="content mt-4">
      <div class="container-fluid">



        <div class="card">
        <?php include('../php/message.php');?>
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
                                    $query = "SELECT * FROM product_list WHERE seller_id = $sellerid";
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
                                                    <a class="dropdown-item keychainify-checked" href="../seller/edit-productseller.php?id=<?= $row['id'];?>"><span class="fa fa-edit text-primary"></span> Edit</a>
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