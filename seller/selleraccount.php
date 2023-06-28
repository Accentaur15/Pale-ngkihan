<?php

session_start();
include_once('../php/config.php');
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
    header("Location: sellerlogin.php");
}


$select = mysqli_query($conn, "SELECT * FROM seller_accounts WHERE unique_id = '{$unique_id}'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
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
    <title>Seller | My Account</title>

    <!--javascript-->
    <script src="js/sellerupdateaccount.js"></script>
    <!--title icon-->
    <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <script src="https://kit.fontawesome.com/0ad1512e05.js" crossorigin="anonymous"></script>
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- css style -->
    <link rel="stylesheet" href="selleraccount.css">

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background: rgb(229, 235, 232);">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="sellermain.php" class="nav-link keychainify-checked">Pale-ngkihan - Seller Side</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto mr-2">
                <li class="nav-item">

                    <button type="button" class="btn btn-rounded badge dropdown-toggle dropdown-icon my-auto"
                        data-toggle="dropdown" aria-expanded="false">
                        <span><img src="../<?php echo $fetch['shop_logo']; ?>" class="avatar-image img-fluid"
                                alt="User Image"></span>
                        <span class="ml-1" style="font-size: 14px;">
                            <?php echo $fetch['shop_name']; ?>
                        </span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mr-2" role="menu">
                        <a class="dropdown-item keychainify-checked" href="#"><span class="fa fa-user"></span> My
                            Account</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item keychainify-checked" href="../php/logoutseller.php"><span class="fas fa-sign-out-alt"></span>
                            Logout</a>
                    </div>

                </li>
            </ul>
        </nav>

        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4 ">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link bg-success">
                <img src="../Assets/logo/logo P whitebg.png" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Pale-ngkihan</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-4">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-compact" data-widget="treeview"
                        role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="sellermain.php" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-box"></i>
                                <p>
                                    Product List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-calendar-days"></i>
                                <p>
                                    Harvest Schedule
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>
                                    Order List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-filter"></i>
                                <p>
                                    Category List
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">Reports</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Monthly Order
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-star"></i>
                                <p>
                                    Ratings & Reviews
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>
                                    Customer Messages
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">Maintenance</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-headset"></i>
                                <p>
                                    Admin Support
                                </p>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">My Account</h1>
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
                    <div class="card">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="this">
                        <form action="../php/sellerupdateaccount.php" method="POST" enctype="multipart/form-data">
                        <div class="error-text alert alert-danger text-center fs-5" style="display:none;">Error</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label" for="form3Example1m1"
                                                style="font-weight: bold;">Shop name</label>
                                            <input name="sname" type="text" id="shopname"
                                                class="form-control form-control-md"
                                                value="<?php echo $fetch['shop_name']; ?>" />
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label" for="form3Example1m1"
                                                style="font-weight: bold;">Username</label>
                                            <input name="uname" type="text" id="username"
                                                class="form-control form-control-md fst-italic"
                                                value="<?php echo $fetch['username']; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group mt-1">
                                        <label class="form-label" for="form3Example1m1" style="font-weight: bold;">Shop
                                            Owner Full
                                            name</label>
                                        <input name="shopowner" type="text" id="form3Examplev4"
                                            class="form-control form-control-md"
                                            value="<?php echo $fetch['shop_owner']; ?>" placeholder="Enter Shop Name"/>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="" selected disabled>Select Gender</option>
                                            <option value="Male" <?php if ($fetch['gender'] === 'Male')
                                                echo 'selected'; ?>>Male</option>
                                            <option value="Female" <?php if ($fetch['gender'] === 'Female')
                                                echo 'selected'; ?>>Female</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-5">
                                    <div class="form-group">
                                        <label class="form-label" for="form3Example1m1"
                                            style="font-weight: bold;">Contact
                                            Number</label>
                                        <input name="cnumber" type="number" id="form3Examplev4"
                                            class="form-control form-control-md" pattern="[0-9]{10}"
                                            value="<?php echo $fetch['contact']; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="form3Example1m1"
                                        style="font-weight: bold;">Address</label>
                                    <textarea name="address" type="text" id="form3Examplev4"
                                        class="form-control form-control-md"
                                       ><?php echo $fetch['address']; ?></textarea>
                                </div>

                                <div class="col-7">
                                    <div class="form-group">
                                        <label class="form-label" for="form3Example1m1" style="font-weight: bold;">Email
                                            Address</label>
                                        <input name="email" type="email" id="form3Examplev4"
                                            class="form-control form-control-md"
                                            value="<?php echo $fetch['email']; ?>" />
                                    </div>
                                </div>



                                <div class="form-group mt-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label" for="form3Example1m1"
                                                style="font-weight: bold;">Password</label>
                                            <input name="password" type="password" id="form3Example4cg"
                                                class="form-control form-control-md" placeholder="Enter New Password" />
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="form3Example1m1"
                                                style="font-weight: bold;">Confirm
                                                Password</label>
                                            <input name="cpassword" type="password" id="form3Example4cg"
                                                class="form-control form-control-md"
                                                placeholder="Enter Confirm New Password" />
                                        </div>
                                        <p class="pb-2 mt-1 ml-2" style="font-style: italic;">Leave the New Password
                                            Fields blank if you don't want to update it.</p>
                                    </div>
                                </div>
                                <hr class="mx-n3">
                                <h3>Additional Information</h3>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Do you have a
                                            Physical Store?</label>
                                        <select name="ispstore" class="form-control" id="inputGroupSelect01"
                                            onchange="showDiv(this)">
                                            <option value="" selected disabled>Choose...</option>
                                            <option value="Yes" <?php if ($fetch['has_pstore'] === 'Yes')
                                                echo 'selected'; ?>>Yes</option>
                                            <option value="No" <?php if ($fetch['has_pstore'] === 'No')
                                                echo 'selected'; ?>>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="showpermit" style="display:none;">
                                    <hr class="mx-n3">
                                    <div class="form-group col-sm-5 mt-2">
                                        <label for="exampleInputFile">DTI Permit</label>
                                        <div class="form-group col-md-6 text-center mb-2 mx-auto">
                                            <img src="<?php echo '../' . $fetch['dti_permit']; ?>" alt="DTI Permit"
                                                id="previewdtipermit" class="border border-gray img-thumbnail"
                                                onclick="showFullImage(this)">
                                        </div>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="dtipermit" class="custom-file-input" id="dtipermit"
                                                    type="file" onchange="previewFile('previewdtipermit', this);" />
                                                <label class="custom-file-label" for="exampleInputFile">Choose
                                                    File</label>
                                            </div>
                                        </div>
                                        <div class="small text-muted mt-2 fst-italic">Upload your DTI Permit. Max
                                            file
                                            size 30 MB</div>
                                    </div>

                                    <hr class="mx-n3">

                                    <div class="form-group col-sm-5 mt-2">
                                        <label for="exampleInputFile">Business Permit</label>
                                        <div class="form-group col-md-6 text-center mb-2 mx-auto">
                                            <img src="<?php echo '../' . $fetch['business_permit']; ?>"
                                                alt="Business Permit" id="previewbpermit"
                                                class="border border-gray img-thumbnail" onclick="showFullImage(this)">
                                        </div>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="bpermit" class="custom-file-input" id="businesspermit"
                                                    type="file" onchange="previewFile('previewbpermit', this);" />
                                                <label class="custom-file-label" for="exampleInputFile">Choose
                                                    File</label>
                                            </div>
                                        </div>
                                        <div class="small text-muted mt-2">Upload your Business Permit.
                                            Max file
                                            size 30 MB</div>
                                    </div>
                                    <hr class="mx-n3">

                                    <div class="form-group col-sm-5 mt-2">
                                        <label for="exampleInputFile">Mayor's Permit</label>
                                        <div class="form-group col-md-6 text-center mb-2 mx-auto">
                                            <img src="<?php echo '../' . $fetch['mayors_permit']; ?>"
                                                alt="Mayors Permit" id="previewmayorspermit"
                                                class="border border-gray img-thumbnail" onclick="showFullImage(this)">
                                        </div>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="mayorspermit" class="custom-file-input" id="mayorspermit"
                                                    type="file" onchange="previewFile('previewmayorspermit', this);" />
                                                <label class="custom-file-label" for="exampleInputFile">Choose
                                                    File</label>
                                            </div>
                                        </div>
                                        <div class="small text-muted mt-2">Upload your Mayor's Permit.
                                            Max file
                                            size 30 MB</div>
                                    </div>
                                    <hr class="mx-n3">


                                </div>

                                <hr class="mx-n3">
                                <h3>Proof of Details</h3>

                                <div class="form-group col-sm-5 mt-2">
                                    <label for="exampleInputFile">Valid I.D</label>
                                    <div class="form-group col-md-6 text-center mb-2 mx-auto">
                                        <img src="<?php echo '../' . $fetch['valid_id']; ?>" alt="Valid ID"
                                            id="previewValidID" class="border border-gray img-thumbnail"
                                            onclick="showFullImage(this)">
                                    </div>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input name="validid" class="custom-file-input" type="file"
                                                id="exampleInputFile" onchange="previewFile('previewValidID', this);" />
                                            <label class="custom-file-label" for="exampleInputFile"></label>
                                        </div>
                                    </div>
                                    <div class="small text-muted mt-2">Upload your Valid Identification
                                        (I.D). Max file
                                        size 30 MB</div>
                                </div>
                                <hr class="mx-n3">


                                <div class="form-group col-sm-5 mt-2">
                                    <label for="exampleInputFile">Shop Logo</label>
                                    <div class="form-group col-md-6 text-center mb-2 mx-auto">
                                        <img src="<?php echo '../' . $fetch['shop_logo']; ?>" alt="Shop Logo"
                                            id="previewShopLogo" class="border border-gray img-thumbnail"
                                            onclick="showFullImage(this)">
                                    </div>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input name="shoplogo" class="custom-file-input" id="formFileLg" type="file"
                                                onchange="previewFile('previewShopLogo', this);" />
                                            <label class="custom-file-label" for="exampleInputFile"></label>
                                        </div>
                                    </div>
                                    <div class="small text-muted mt-2">Upload your Shop Logo. Max file
                                        size 30 MB</div>
                                </div>
                                <hr class="mx-n3">

                                <div class="form-group mt-2">
                                    <div class="col-6">
                                        <label class="form-label" for="form3Example1m1"
                                            style="font-weight: bold;">Current Password</label>
                                        <input name="oldpassword" type="password" id="form3Example4cg"
                                            class="form-control form-control-md" required
                                            placeholder="Enter Current Password" />
                                    </div>
                                </div>






                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button name="submit" type="submit"
                                        class="buttons btn btn-success btn-lg btn-block">Update</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>

                    <!-- /.card -->
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->



        <!-- Main Footer -->
        <footer class="main-footer text-center text-white" style="background-color: #678A71;">
            <!-- Default to the left -->
            <strong>Copyright &copy; Pale-ngkihan 2023.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

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
            xhr.open("POST", "../php/sellerupdateaccount.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response.trim(); // Trim whitespace from the response
                        let comparisonResult = data === "success";
                        if (comparisonResult) {
                            //location.href = "../seller/selleraccount.php";
                            errorText.textContent = data;
                            errorText.style.display = "block";
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
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>