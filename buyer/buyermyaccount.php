<?php

session_start();
include_once('../php/config.php');
include_once('../php/cart_functions.php'); 
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
  header("Location: ../buyerlogin.php");
}

$select = mysqli_query($conn, "SELECT * FROM buyer_accounts WHERE unique_id = '{$unique_id}'") or die('query failed');
  if (mysqli_num_rows($select) > 0) {
  $fetch = mysqli_fetch_assoc($select);
  }

  $qry = mysqli_query($conn, "SELECT * FROM buyer_accounts WHERE unique_id = '{$unique_id}'");

if (mysqli_num_rows($qry) > 0) {
    $row = mysqli_fetch_assoc($qry);
    if ($row) {
      $fname = $row['first_name'];
      $lname = $row['last_name'];
      $email = $row['email'];
      $profilePicture = $row['profile_picture'];
      $id = $row['id'];
    }
}

  $cartItemCount = getCartItemCount($conn, $id);
  include_once('../php/notifications.php'); 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buyer | My Cart</title>
</head>
<!-- SweetAlert2 -->
<link rel="stylesheet" href="../Assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="../Assets/plugins/fontawesome-free/css/all.min.css">
<script src="https://kit.fontawesome.com/0ad1512e05.js" crossorigin="anonymous"></script>
<!-- Theme style -->
<link rel="stylesheet" href="../Assets/dist/css/adminlte.min.css">
<!--css-->
<link href="buyermyaccount.css" rel="stylesheet">
<!--fontawesome-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png"/>
<link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png"/>
<link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png"/>
<!--Animation-->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
<!--Navigation Bar-->
<nav class="navbar navbar-expand-md navbar-light" style="background: rgb(229, 235, 232);">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.html">
            <img src="../Assets/logo/Artboard 1.png" class="logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
            <ul class="navbar-nav text-center">
                <li class="nav-item">
                    <a class="nav-link mx-3" aria-current="page" href="buyermain.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="../buyer/marketplace.php">Marketplace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  mx-3" href="../buyer/wholesale.php">Wholesale</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="buyeraboutus.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="#">My Orders</a>
                </li>
  <?php include('../Assets/includes/notification.php');?>
                <li class="nav-item">
                    <a class="nav-link active mx-3" href="../buyer/cart.php"><i class="fa-solid fa-cart-shopping"></i>
                    <?php
                      if ($cartItemCount > 0) {
                          echo '<span class="badge bg-success position-absolute top-0 end-0">' . $cartItemCount . '</span>';
                      }
                      ?>
                  </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        echo '<img src="../' . $profilePicture . '" alt="Profile Picture" class="avatar-image img-fluid">';
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <li class="dropdown-header text-center text-md font-weight-bold text-dark"><?php echo $fname . ' ' . $lname; ?></li>
                        <li class="text-center"><a class="dropdown-item" href="buyermyaccount.php">My Account</a></li>
                        <li class="text-center"><a class="dropdown-item" href="../php/logout.php?logout_id=<?php echo $unique_id ?>">Log out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
function previewFile(imageId, input) {
  var file = input.files[0];
  if (file) {
    var reader = new FileReader();
    reader.onload = function () {
      var image = document.getElementById(imageId);
      image.src = reader.result;
      image.onclick = function () {
        var overlay = document.createElement("div");
        overlay.className = "overlay";
        var fullImage = document.createElement("img");
        fullImage.className = "full-image";
        fullImage.src = reader.result;
        overlay.appendChild(fullImage);
        document.body.appendChild(overlay);
        overlay.onclick = function () {
          document.body.removeChild(overlay);
        };
      };
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

    <!--End of Navigation Bar-->
    
<body>
            <section class="h-100 h-custom gradient-custom-2 border" style="background-image: url(../Assets/images/Background-palay.png);">
            <div class="container py-5 h-100 ">
                <div class="row d-flex justify-content-center align-items-center h-100 ">
                <div class="col-12">
                    <div class="card card-registration card-registration-2 " style="border-radius: 15px;">
                    <form action="../php/updatebuyer.php" method="POST" enctype="multipart/form-data">
                        <div class="card-body p-0 ">
                        <div class="row g-0">
                            <div class="col-lg-6" style="background: #F3EFE2;">
                            <div class="p-5">
                                <h1 class="fw-bolder mb-3">Update Buyer Account</h1>
                                <hr class="mx-n3">
                                <div class="error-text alert alert-danger text-center fs-5" style="display:none;">Error</div>
                                <div class="row">
                                <div class="col-md-6 mb-4 pb-2">
                                    <div class="form-outline">
                                    <label class="form-label" for="firstname" style="font-weight: bold;">First Name</label>
                                    <input type="text" name="fname" class="form-control form-control-md" pattern="[a-zA-Z'-'\s]*" value="<?php echo $fetch['first_name']?>" />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 pb-2">
                                    <div class="form-outline">
                                    <label class="form-label" for="middlename" style="font-weight: bold;">Middle Name</label>
                                    <input type="text" name="mname" class="form-control form-control-md" value="<?php echo $fetch['middle_name']?>" />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 pb-2">
                                    <div class="form-outline">
                                    <label class="form-label" for="lastname" style="font-weight: bold;">Last Name</label>
                                    <input type="text" name="lname" class="form-control form-control-md" pattern="[a-zA-Z'-'\s]*" value="<?php echo $fetch['last_name']?>" />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h5 class="mb-2 pb-1"><i class="fa-solid fa-venus-mars"></i>&nbsp;Gender:</h5>
                                    <select name="gender" class="form-control">
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male" <?php if ($fetch['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if ($fetch['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="form-outline">
                                    <i class="fa-regular fa-address-book"></i><label class="form-label" for="contactnumber" style="font-weight: bold;">&nbsp;Contact Number</label>
                                    <input name="cnumber" type="tel" class="form-control form-control-md" value="<?php echo $fetch['contact']?>" pattern="[0-9]{10}" />
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="form-outline">
                                    <i class="fa-solid fa-location-dot"></i> <label class="form-label" for="address" style="font-weight: bold;">&nbsp;Address</label>
                                    <input name="address" type="text" class="form-control form-control-md" pattern="[a-zA-Z'-'\s]*" value="<?php echo $fetch['address']?>" />
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="form-outline">
                                    <i class="fa-regular fa-envelope"></i> <label class="form-label" for="email" style="font-weight: bold;">&nbsp;Email Address</label>
                                    <input name="email" type="email" class="form-control form-control-md" value="<?php echo $fetch['email']?>" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-1 pb-1">
                                    <i class="fa-sharp fa-solid fa-lock"></i> <label class="form-label" for="password" style="font-weight:bold;">&nbsp;Password</label>
                                    <input name="password" type="password" class="form-control form-control-md" placeholder="Enter New Password" />
                                    </div>
                                    <div class="col-md-6 mb-2 pb-2">
                                    <i class="fa-solid fa-key"></i> <label class="form-label" for="form3Example1m1" style="font-weight: bold;">&nbsp;Confirm Password</label>
                                    <input type="password" name="cpassword" class="form-control form-control-md" placeholder="Confirm your New Password" />
                                    </div>
                                    <p class="small text-muted pb-2  ml-2">Leave the New Password Fields blank if you don't want to update it.</p>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-6 text-black fw-bold batman" style="background-color: #D5D8C5;";>
                            <div class="p-5">
                                <h3 class="fw-bold mb-2 mt-2">Proof of Details</h3>
                                <hr class="mx-n3">
                                <div class="row align-items-center py-3 mx-auto">
                                <div class="col-md-3 ps-5">
                                    <div class="text-center mb-2"><i class="fa-solid fa-id-card fa-2xl"></i></div>
                                    <h6 class="mb-1 fw-bold text-center mb-2">Valid I.D</h6>
                                </div>
                                <div class="col-md-9 pe-5">
                                    <div class="form-group col-lg-6 text-center mb-2 mx-auto">
                                    <img src="<?php echo '../' . $fetch['valid_id']; ?>" alt="Valid ID" id="previewImg" class="border border-gray img-thumbnail" onclick="showFullImage(this)">
                                    </div>
                                    <div class="custom-file">
                                      <input class="custom-file-input" name="validid" type="file" onchange="previewFile('previewImg', this);"/>
                                      <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                      <div class="small text-muted mt-2">Upload your Valid Identification (I.D). Max file size 30 MB</div>
                                    </div>
                                </div>
                                </div>
                                <hr class="mx-n3">
                                <div class="row align-items-center py-3">
                                <div class="col-md-3 ps-5 mx-auto">
                                    <div class="text-center mb-2"><i class="fa-solid fa-circle-user fa-2xl"></i></div>
                                    <h6 class="mb-1 fw-bold text-center mb-2">Profile Picture</h6>
                                </div>
                                <div class="col-md-9 pe-5">
                                    <div class="form-group col-lg-6 text-center mb-2 mx-auto">
                                    <img src="<?php echo '../' . $fetch['profile_picture']; ?>" alt="Profile Picture" id="previewProfile" class="border border-gray img-thumbnail" onclick="showFullImage(this)">
                                    </div>
                                     <div class="custom-file">
                                       <input name="profilePicture" class="custom-file-input" name="profilePictureInput" type="file" onchange="previewFile('previewProfile', this);" />
                                       <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                       <div class="small text-muted mt-2">Upload your Profile Picture. Max file size 30 MB</div>
                                      </div>
                                </div>
                                </div>
                                <hr class="mx-n3">
                                <div class="mb-4 pb-2">
                                <label class="form-label" for="form3Example1m1" style="font-weight: bold;">&nbsp;Enter Current Password</label>
                                <input type="password" name="oldpassword" class="form-control form-control-md" required/>
                                </div>
                                <button type="submit" name="submit" class="button btn btn-lg text-white w-100 mb-3 mt-4" style="background: #6BB25A;">Update Account</button>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    

                    

                    </form>
                </div>
                </div>
            </div>
            
            </section>

            

	<!-- footer section end-->

    <!-- Copyright Section-->
    
    <div class="copyright py-4 text-center text-white d-flex p-2">
      <div class="container"><small>Copyright &copy; Pale-ngkihan 2023</small></div>
  </div>
  <!-- bs-custom-file-input -->
<script src="../Assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!--Animation java-->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      AOS.init();

      $(function () {
  bsCustomFileInput.init();
});
    </script>
        <!--Java-->
        <script src="../js/update.js"></script>
    <!--Bootstrap java-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>

