<?php

session_start();
include_once('../php/config.php');
include_once('../php/cart_functions.php'); 
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
    header("Location: ../buyerlogin.php");
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
    <title>Buyer | Wholesale</title>
</head>
 <!-- Leaflet CSS -->
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
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
<link href="marketplace.css" rel="stylesheet">
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
                    <a class="nav-link active mx-3" href="../buyer/wholesale.php">Wholesale</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="buyeraboutus.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="../buyer/myorders.php">My Orders</a>
                </li>
                <?php include('../Assets/includes/notification.php');?>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="../buyer/cart.php"><i class="fa-solid fa-cart-shopping"></i>
                    <?php
                      if ($cartItemCount > 0) {
                          echo '<span class="badge bg-success position-absolute top-0 end-0">' . $cartItemCount . '</span>';
                      }
                      ?>
                </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" href="../buyer/chat_users.php"><i class="fa-solid fa-message"></i></a>
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

<!--End of Navigation Bar-->

<body>








<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $harvest_id = $_GET['id']; // Assign the product ID to a variable

    $qry = $conn->query("SELECT hs.*, sa.shop_name AS vendor
                    FROM harvest_schedule hs
                    INNER JOIN seller_accounts sa ON hs.seller_id = sa.id
                    WHERE hs.id = '{$harvest_id}'");

    if ($qry->num_rows > 0) {
        $row = $qry->fetch_assoc();
        foreach ($row as $k => $v) {
            $$k = $v;
        }


    } else {
        echo "No data found for the given ID.";
    }
} else {
    echo "Invalid ID.";
}
?>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }


        #prod-img-holder {
        height: 45vh !important;
        width: calc(100%);
        overflow: hidden;
    }

    #prod-img {
        object-fit: scale-down;
        height: calc(100%);
        width: calc(100%);
        transition: transform .3s ease-in;
    }
    #prod-img-holder:hover #prod-img{
        transform:scale(1.2);
    }
    </style>
<div class="content">
<section class="content">
<div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-xl-12">
<div class="content py-3">
    <div class="card card-outline card-success rounded-0 shadow">
        <div class="card-header">
            <h5 class="card-title"><b>Harvest Schedule Details</b></h5>
        </div>
        <form id="harvest-scehdule-form">
        <input type="hidden" id="product_id" value="<?php echo $harvest_id; ?>">
        <div class="card-body">
            <div class="container-fluid">
                <div id="msg"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-5 col-sm-12 text-center">
                        <div class="position-relative overflow-hidden" id="prod-img-holder">
                        <img src="<?= $harvest_image ?>" alt="Product-image" class="img-thumbnail bg-gradient-gray" id="prod-img">
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-sm-12">
                        <h2><b><?= $rice_type ?></b></h2>
                        <div class="d-flex w-100">
                            <div class="col-auto px-0"><small class="text-muted h6 mr-1">Seller: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0"><small class="text-muted font-italic h6"><?= $vendor ?></small></p></div>
                        </div>
                        <div class="d-flex">
                            <div class="col-auto px-0"><small class="text-muted h6">Date Scheduled: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive font-weight-bold h6"><?= date('F j, Y', strtotime($date_scheduled)) ?></small></p></div>
                        </div>
                        <div class="d-flex">
                            <div class="col-auto px-0"><small class="text-muted h6">Estimated Quantity: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive font-weight-bold h6"><?= $quantity_available ?> Sacks</small></p></div>
                        </div>
                        <div class="d-flex">
                            <div class="col-auto px-0"><small class="text-muted h6">Harvest Schedule Status: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive font-weight-bold h6"><?php
// Assuming $status contains one of the three enumerated values: 'upcoming', 'ongoing', or 'completed'
if ($status == 'upcoming') {
    // Output the specified badge for 'upcoming'
    echo '<span class="badge badge-info bg-gradient-info px-3 rounded-pill"> Upcoming </span>';
} elseif ($status == 'ongoing') {
    // Output the specified badge for 'ongoing'
    echo '<span class="badge badge-primary bg-gradient-primary px-3 rounded-pill"> Ongoing </span>';
} elseif ($status == 'completed') {
    // Output the specified badge for 'completed'
    echo '<span class="badge badge-success bg-gradient-success px-3 rounded-pill"> Completed</span>';
} else {
    // Output the status without the badge for other cases
    echo $status;
}
?>
</small></p></div>
                        </div>
                        <div class="d-flex">
                            <div class="col-auto px-0"><small class="text-muted h6">Bidding Status: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive font-weight-bold h6"><?php
// Assuming $bidding_status contains one of the two values: 0 or 1
if ($bidding_status == 0) {
    // Output the specified badge for bidding_status = 0
    echo '<span class="badge badge-danger px-3 py-1 rounded-pill">Closed</span>';
} elseif ($bidding_status == 1) {
    // Output the specified badge for bidding_status = 1
    echo '<span class="badge badge-success px-3 py-1 rounded-pill">Open</span>';
} else {
    // Output a default badge for other cases (though this should not happen with only two possible values)
    echo '<span class="badge badge-danger px-3 py-1 rounded-pill">N/A</span>';
}
?>
</small></p></div>
                        </div>
                        <div class="d-flex">
                            <div class="col-auto px-0"><small class="text-muted h6 mr-1">Starting Bid: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive font-weight-bold h6">₱<?= $starting_bid ?> per kg</small></p></div>
                        </div>
                        
<div class="col-auto px-0"><small class="text-muted h6">Location: </small></div>
<?php
function isCoordinateFormat($location) {
    return preg_match('/Latitude:\s*-?\d+\.\d+,\s*Longitude:\s*-?\d+\.\d+/', $location);
}
// Function to extract latitude and longitude from the location string
function extractCoordinates($location) {
    $pattern = '/Latitude:\s*(-?\d+\.\d+),\s*Longitude:\s*(-?\d+\.\d+)/';
    preg_match($pattern, $location, $matches);
    return array('latitude' => $matches[1], 'longitude' => $matches[2]);
}

// Assuming $location contains the location value
if (isCoordinateFormat($location)) {
    // Extract latitude and longitude values
    $coordinates = extractCoordinates($location);
    $latitude = $coordinates['latitude'];
    $longitude = $coordinates['longitude'];

    // Output the map
    echo '<div id="map" style="height: 300px;"></div>';
    echo '<script>
        var map = L.map("map").setView([' . $latitude . ', ' . $longitude . '], 15);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap contributors"
        }).addTo(map);
        L.marker([' . $latitude . ', ' . $longitude . ']).addTo(map);
    </script>';
} else {
    // Output the location without the map
    echo '<p class="m-0 pl-3"><small class="text-olive font-weight-bold h6">' . $location . '</small></p>';
}
?>

                        <?php
// Assuming $bidding_status contains one of the two values: 0 or 1
if ($bidding_status == 1) {
    // Display the bidding input field if bidding_status = 1 (Open for Bidding)
    ?>
    <div class="d-flex mt-2">
        <div class="col-auto px-0"><small class="text-muted h6 mr-1">Enter Your Bid:</small></div>
    </div>
    <div class="d-flex">
        <div class="col-auto px-0"></div>
        <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">₱</span>
                   
                </div>
                <input type="number" class="form-control" id="bidAmount" min="<?= ($starting_bid == 0) ? 1 : $starting_bid ?>" placeholder="Enter your bid amount" required>
                <div class="input-group-append">
                    <span class="input-group-text">Per KG</span>
                    <button class="btn btn-warning text-white " id="submitBid" data-harvest-id="<?php echo $harvest_id; ?>">Submit Bid</button>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <!-- Display the "Get the Schedule" button if bidding_status is not 1 (Closed for Bidding) -->
    <div class="d-flex mt-2">
        <div class="col-auto px-0"></div>
        <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
            <!-- Add margin using the mt-2 class -->
            <button class="btn btn-warning text-white mt-2 font-weight-bold" id="getScheduleBtn" data-harvest-id="<?php echo $harvest_id; ?>"><i class="fa-solid fa-comments-dollar"></i>&nbsp;Make Offer</button>
        </div>
    </div>
<?php } ?>






                        
                    </div>
                </div>
            </div>
        </div>
</form>
    </div>
</div>
</div>
        </div>
    </div>




    </section>
</div>

        <!-- Footer -->
        <div class="copyright text-center text-white d-flex p-2">
    <div class="container">
        <small>Copyright &copy; Pale-ngkihan 2023</small>
        <hr class="mx-2">
        
        <a href="../buyer/support.php" class=" text-warning">
            <i class="fas fa-life-ring"></i> Get Support
        </a>
    </div>
</div>

<script>
  $(document).ready(function() {
    // Add a click event handler to the "Get Schedule" button
    $("#getScheduleBtn").click(function(event) {
        event.preventDefault();

        var harvestId = $(this).data("harvest-id"); // Extract the harvest_id from the button's data attribute

        // Display a confirmation dialog
        Swal.fire({
            title: 'Get Schedule Confirmation',
            text: 'Are you sure you want to get the schedule?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, get it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform an AJAX request to get the schedule
                $.ajax({
                    url: '../php/schedule.php',
                    method: 'GET',
                    data: { harvest_id: harvestId }, // Pass the harvest_id in the request
                    success: function(response) {
                        // Handle the response here, maybe display a success message
                        var responseData = JSON.parse(response);
                        if (responseData.message) {
                            showAlert('Schedule Obtained', responseData.message, 'success');
                        } else if (responseData.error) {
                            showerror('Error', responseData.error, 'error');
                        } else {
                            showerror('Error', 'An error occurred while getting the schedule.', 'error');
                        }
                    },
                    error: function(error) {
                        // Handle errors here, maybe display an error message
                        showError('Error', 'An error occurred while getting the schedule.', 'error');
                    }
                });
            }
        });
    });

    $("#submitBid").click(function(event) {
        event.preventDefault(); // Prevent the default click behavior

        var harvestId = $(this).data("harvest-id"); // Extract the harvest_id from the button's data attribute
        var bidAmount = $("#bidAmount").val(); // Get the bid amount from the input field

        // Validate the bid amount, you can add more validation logic here
        if (bidAmount.trim() === '') {
            showerror('Error', 'Please enter a valid bid amount.', 'error');
            return;
        }

        // Perform an AJAX request to submit the bid
        $.ajax({
            url: '../php/schedule.php',
            method: 'POST',
            data: { harvest_id: harvestId, bidAmount: bidAmount }, // Pass the harvest_id and bidAmount in the request
            success: function(response) {
                //console.log(response);
                // Handle the response here, maybe display a success message
                var responseData = JSON.parse(response);
                if (responseData.message) {
                    showAlert('Bid Submitted', responseData.message, 'success');
                } else if (responseData.error) {
                    showerror('Error', responseData.error, 'error');
                } else {
                    showerror('Error', 'An error occurred while submitting the bid.', 'error');
                }
            },
            error: function(error) {
                // Handle errors here, maybe display an error message
                showerror('Error', 'An error occurred while submitting the bid.', 'error');
            }
        });
    });
});

  // Define the showAlert function
  function showAlert(title, text, icon) {
    Swal.fire({
      title: title,
      text: text,
      icon: icon,
      position: 'top',
      timer: 2000,
      showConfirmButton: false,
      toast: true,
      timerProgressBar: true,
      customClass: {
        popup: 'swal-popup',
        title: 'swal-title',
        content: 'swal-text'
      }
    }).then(function () {
      // Reload the page after the SweetAlert is closed
      location.reload();
    });
  }

    // Define the showAlert function
    function showerror(title, text, icon) {
    Swal.fire({
      title: title,
      text: text,
      icon: icon,
      position: 'top',
      timer: 2000,
      showConfirmButton: false,
      toast: true,
      timerProgressBar: true,
      customClass: {
        popup: 'swal-popup',
        title: 'swal-title',
        content: 'swal-text'
      }
    });
  }

 

</script>


 <!-- REQUIRED SCRIPTS -->


    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- AdminLTE App -->
    <script src="../Assets/dist/js/adminlte.min.js"></script>
    <!--Animation java-->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- SweetAlert2 -->
    <script src="../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>