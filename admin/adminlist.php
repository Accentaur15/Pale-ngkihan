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
        $role = $row['role'];
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
    <title>Admin | Admin List</title>


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
        include('../Assets/includes/topbaradmin.php');
        include('../Assets/includes/sidebaradmin.php');
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <div class="content mt-4">
                <div class="container-fluid">

                    <!-- ADD NEW ADMIN MODAL -->
                    <div class="this">
                        <form action="../php/addnewadmin.php" method="POST" enctype="multipart/form-data">
                            <!-- Modal -->
                            <div class="modal fade" id="addadmin" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New Admin Account</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container-fluid">


                                                <div class="error-text alert alert-warning text-center fs-5 text-white"
                                                    style="display:none;">Error</div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="">First Name</label>
                                                            <input name="fname" type="text" class="form-control"
                                                                placeholder="Enter First Name" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="form-label" for="">Last Name</label>
                                                            <input name="lname" type="text" class="form-control"
                                                                placeholder="Enter Last Name" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="form-label" for="">Password</label>
                                                            <input name="password" type="password" class="form-control"
                                                                placeholder="Enter Password" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="exampleInputFile">Profile Picture</label>

                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input name="profilepicture"
                                                                        class="custom-file-input" id="profilepicture"
                                                                        type="file"
                                                                        onchange="previewFile('previewprofilepicture', this);"
                                                                        required />
                                                                    <label class="custom-file-label"
                                                                        for="exampleInputFile">Choose
                                                                        File</label>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="form-group col-md-6 text-center mb-2 mx-auto mt-2">
                                                                <img src="../seller_profiles/no-image-available.png"
                                                                    alt="Profile Picture" id="previewprofilepicture"
                                                                    class="border border-gray img-thumbnail"
                                                                    onclick="showFullImage(this)">
                                                            </div>



                                                        </div>



                                                    </div>



                                                    <div class="col-md-6">

                                                        <div class="form-group">
                                                            <label class="form-label" for="">User Name</label>
                                                            <input name="uname" type="text" class="form-control"
                                                                placeholder="Enter Username" required>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="form-label" for="">Role</label>
                                                            <select name="adminrole" class="form-control" required>
                                                                <option value="" selected disabled>Select Role</option>
                                                                <option value="2">Moderator</option>
                                                                <option value="1">Administrator</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="form-label" for="">Confirm Password</label>
                                                            <input name="cpassword" type="password" class="form-control"
                                                                placeholder="Confirm Password" required>
                                                        </div>





                                                    </div>


                                                </div>




                                            </div>
                                            <div class="modal-footer">
                                                <button name="submit" type="submit"
                                                    class="buttons btn btn-success">Save</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- END OF ADD NEW ADMIN MODAL -->


                    <div class="card">
                        <?php include('../php/message.php'); ?>
                        <div class="card-header">
                            <h4 class="card-title">List of Admin</h4>
                            <button type="button" data-toggle="modal" data-target="#addadmin"
                                class="btn btn-success btn-sm float-right" <?php if ($role != 1)
                                    echo 'disabled'; ?>>
                                <i class="fas fa-plus"></i>&nbsp;Create New Admin
                            </button>

                        </div>
                        <div class="card-body">
                            <table id="productlist" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Profile Picture</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM admin_accounts";
                                    $query_run = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        foreach ($query_run as $row) {
                                            if ($row['id'] != $unique_id) { // Exclude the row with the specific $unique_id
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?= $row['id']; ?>
                                                    </td>
                                                    <td><img class="avatar-image mx-auto d-block"
                                                            src="<?= "../" . $row['profile_picture']; ?>" alt="Profile Picture"
                                                            onclick="showFullImage(this)"></td>
                                                    <td>
                                                        <?= $row['last_name'] . ", " . $row['first_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['username']; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($row['role'] == '1') {
                                                            echo 'Administrator';
                                                        } else if ($row['role'] == '2') {
                                                            echo 'Moderator';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon"
                                                            data-toggle="dropdown" aria-expanded="false" <?php if ($role != 1)
                                                                echo 'disabled'; ?>>
                                                            Action
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                        <a class="dropdown-item keychainify-checked" href="../admin/editadminlist.php?id=<?= $row['id'];?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                                            <div class="dropdown-divider"></div>
                                                            <form action="../php/deleteadmin.php" method="POST">
                                                                <button type="submit" name="user_delete" value="<?= $row['id']; ?>"
                                                                    class="dropdown-item delete_data keychainify-checked"><span
                                                                        class="fa fa-trash text-danger"></span> Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
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
                xhr.open("POST", "../php/addnewadmin.php", true);
                xhr.onload = () => {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            let data = xhr.response.trim(); // Trim whitespace from the response
                            let comparisonResult = data.includes("updated");
                            if (comparisonResult) {
                                setTimeout(function () {
                                    location.reload(true);
                                }, 1000);
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