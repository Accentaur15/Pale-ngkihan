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
  <title>Admin | Buyer List</title>


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
                <h4>List of Buyers</h4>
            </div>
            <div class="card-body">

            <?php 
                if(isset($_GET['id']))
                {
                    $user_id = $_GET['id'];
                    $users = "SELECT * FROM buyer_accounts WHERE id='$user_id'";
                    $users_run = (mysqli_query($conn, $users));

                    if(mysqli_num_rows($users_run) > 0)
                    {
                        foreach($users_run as $user)
                            ?>
                        <div class="this">
                        <form action="../php/adminbuyeredit.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" value="<?=$user['id'];?>"/>
                        <div class="error-text alert alert-danger text-center fs-5" style="display:none;">Error</div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="" style="font-weight: bold;">First Name</label>
                                <input name="fname" type="text" class="form-control form-control-md" value="<?=$user['first_name'];?>" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="" style="font-weight: bold;">Middle Name</label>
                                <input name="mname" type="text" class="form-control form-control-md" value="<?=$user['middle_name'];?>" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="" style="font-weight: bold;">Last Name</label>
                                <input name="lname" type="text" class="form-control form-control-md" value="<?=$user['last_name'];?>" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male" <?php if ($user['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if ($user['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                                 </select>
                            </div>
                            <div class="col-md-7 mb-3">
                                <label class="form-label" for="" style="font-weight: bold;">Contact Number</label>
                                <input name="cnumber" type="number" class="form-control form-control-md" value="<?=$user['contact'];?>" />
                            </div>
                            <div class="col-md-7 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" type="text" class="form-control form-control-md"><?=$user['address'];?></textarea>
                            </div>
                            <div class="col-md-7 mb-3">
                                <label class="form-label" for="" style="font-weight: bold;">Email</label>
                                <input name="email" type="email" class="form-control form-control-md" value="<?=$user['email'];?>" />
                            </div>
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
                            <hr class="mx-n3">
                            <h3>Proof of Details</h3>
                            <hr class="mx-n3">
                                    <div class="form-group col-sm-5 mt-2">
                                        <label for="exampleInputFile">Profile Picture</label>
                                        <div class="form-group col-md-6 text-center mb-2 mx-auto">
                                            <img src="<?php echo '../' . $user['profile_picture']; ?>" alt="DTI Permit"
                                                id="previewprofilepicture" class="border border-gray img-thumbnail"
                                                onclick="showFullImage(this)">
                                        </div>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="profilePicture" class="custom-file-input" id="dtipermit"
                                                    type="file" onchange="previewFile('previewprofilepicture', this);" />
                                                <label class="custom-file-label" for="exampleInputFile"></label>
                                            </div>
                                        </div>
                                        <div class="small text-muted mt-2 fst-italic">Profil Picture. Max
                                            file
                                            size 30 MB</div>
                                    </div>
                                    <hr class="mx-n3">
                                    <div class="form-group col-sm-5 mt-2">
                                    <label for="exampleInputFile">Valid I.D</label>
                                    <div class="form-group col-md-6 text-center mb-2 mx-auto">
                                        <img src="<?php echo '../' . $user['valid_id']; ?>" alt="Valid ID"
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
                                    <div class="small text-muted mt-2">Valid Identification
                                        (I.D). Max file
                                        size 30 MB</div>
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
               <a href="../admin/buyerlist.php" class="btn btn-secondary btn-md">Cancel</a>
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
            xhr.open("POST", "../php/adminbuyeredit.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response.trim(); // Trim whitespace from the response
                        let comparisonResult = data.includes("updated");
                        if (comparisonResult) {
                            //errorText.textContent = data;
                           // errorText.style.display = "block";
                            <?php $_SESSION['message'] = "Updated Succesfully"; ?>
                            window.location.href = "../admin/buyerlist.php";
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