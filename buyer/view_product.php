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
    <title>Buyer | Marketplace</title>
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
                    <a class="nav-link active mx-3" href="../buyer/marketplace.php">Marketplace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  mx-3" href="../buyer/wholesale.php">Wholesale</a>
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
    $product_id = $_GET['id']; // Assign the product ID to a variable

    $qry = $conn->query("SELECT p.*, s.shop_name AS vendor, c.name AS `category`
                    FROM `product_list` p
                    INNER JOIN `seller_accounts` s ON p.seller_id = s.id
                    INNER JOIN `category_list` c ON p.category_id = c.id
                    WHERE p.id = '{$product_id}'");

    if ($qry->num_rows > 0) {
        $row = $qry->fetch_assoc();
        foreach ($row as $k => $v) {
            if ($k == 'product_image') {
                $product_image = '../' . $v;
            } elseif ($k == 'product_description') {
                $description = $v;
            } elseif ($k == 'product_name') {
                $name = $v;
            } else {
                $$k = $v;
            }
        }
    } else {
        echo "<script> alert('Unknown Product ID.'); location.replace('./?page=products') </script>";
        exit;
    }
} else {
    echo "<script> alert('Product ID is required.'); location.replace('./?page=products') </script>";
    exit;
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
            <h5 class="card-title"><b>Product Details</b></h5>
        </div>
        <form id="add-to-cart-form">
        <input type="hidden" id="product_id" value="<?php echo $product_id; ?>">
        <div class="card-body">
            <div class="container-fluid">
                <div id="msg"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-5 col-sm-12 text-center">
                        <div class="position-relative overflow-hidden" id="prod-img-holder">
                        <img src="<?= $product_image ?>" alt="Product-image" class="img-thumbnail bg-gradient-gray" id="prod-img">
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-sm-12">
                        <h2><b><?= $name ?></b></h2>
                        <div class="d-flex w-100">
                            <div class="col-auto px-0"><small class="text-muted h6 mr-1">Seller: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0"><small class="text-muted font-italic h6"><?= $vendor ?></small></p></div>
                        </div>
                        <div class="d-flex">
                            <div class="col-auto px-0"><small class="text-muted h6 mr-1">Category: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0"><small class="text-muted font-italic h6"><?= $category ?></small></p></div>
                        </div>
                        <div class="d-flex">
                            <div class="col-auto px-0"><small class="text-muted h6">Available: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive font-weight-bold h6"><?= $quantity ?>&nbsp;<?= $unit ?></small></p></div>
                        </div>
                        <div class="d-flex">
                            <div class="col-auto px-0"><small class="text-muted h6 mr-1">Price: </small></div>
                            <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive font-weight-bold h6">₱<?= $price ?></small></p></div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-4 form-group">
                                <div class="d-flex align-items-center mt-2">
                                    <small class="text-muted h6 mb-0 mr-2">Quantity</small>
                                    <input type="number" min="1" max="<?= $quantity ?>" id="qty" value="1" class="form-control rounded-0 text-center">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <button class="btn btn-warning btn-flat text-white swalDefaultSuccess" type="submit" form="add-to-cart-form">
                                    <i class="fa fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                        <div class="w-100"><small class="font-weight-bold h6">Product Description</small><?= html_entity_decode($description) ?></div>
                        
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

    <div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card card-outline card-success rounded-0 shadow">
                <div class="card-header">
                    <h5 class="card-title"><b>Product Ratings and Reviews</b></h5>
                </div>
                <div class="card-body" id="rating-section">
                    <div id="loading-message" class="text-center">Loading ratings and reviews...</div>
                </div>
            </div>
        </div>
    </div>
</div>


    </section>
</div>

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
$(function() {
    $('#add-to-cart-form').on('submit', function(e) {
        e.preventDefault();

        var product_id = $('#product_id').val();
        var quantity = $('#qty').val(); // Get the quantity from the input field

        // Send an AJAX request to add the product to the cart
        $.ajax({
            url: '../php/add_to_cart.php',
            method: 'POST',
            data: { product_id: product_id, quantity: quantity }, // Include the quantity in the data object
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Display a success message using SweetAlert2 with a timer of 3 seconds
                    Swal.fire({
                        icon: 'success',
                        title: 'Notification',
                        text: 'Product has been added to the cart.',
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'swal-popup',
                            title: 'swal-title',
                            text: 'swal-text'
                        }
                    });

                    // Perform additional actions if needed
                    setTimeout(function() {
                location.reload();
            }, 3000);
                } else {
                    // Display an error message using SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error adding product to cart: ' + response.message,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true,
                        customClass: {
                            popup: 'swal-popup',
                            title: 'swal-title',
                            text: 'swal-text'
                        }
                    });
                }
            },
            error: function() {
                // Display a generic error message using SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error adding product to cart. Please try again.',
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    customClass: {
                        popup: 'swal-popup',
                        title: 'swal-title',
                        text: 'swal-text'
                    }
                });
            }
        });
    });
});

    $(function() {
    // Fetch and display ratings and reviews
    function createRatingElements(response) {
        var html = '';
        for (var i = 0; i < response.length; i++) {
            var rating = response[i].rating;
            var review = response[i].review;
            var reviewer = response[i].reviewer;
            var reviewDate = response[i].reviewDate;

            // Create HTML elements for each rating and review
            html += '<div class="card">';
        html += '<div class="card-header">';
        html += '<h5 class="card-title font-weight-bold">' + reviewer + '</h5>';
        html += '</div>';
        html += '<div class="card-body">';
        html += '<div class="star">' + getStarRating(rating) + '</div>';
        html += '<p class="card-text">';
        html += '<div class="review">' + review + '</div>';
        html += '</p>';
        html += '</div>';
        html += '<div class="card-footer text-muted text-right font-weight-bold">' + formatDate(reviewDate) + '</div>';
        html += '</div>';
        }
        return html;
    }

    // Fetch and display ratings and reviews
    function loadRatingsAndReviews() {
        var product_id = '<?php echo $product_id; ?>'; // Get the product ID from PHP variable
        $.ajax({
            url: '../php/get_ratings_reviews.php', // URL to fetch the ratings and reviews
            method: 'GET',
            data: { product_id: product_id }, // Pass the product ID to the PHP file
            dataType: 'json',
            success: function(response) {
                // Clear the loading message
                $('#loading-message').remove();

                // Check if there are ratings and reviews available
                if (response.length > 0) {
                    // Create HTML elements for each rating and review
                    var html = createRatingElements(response);
                    // Append the HTML elements to the rating-section div
                    $('#rating-section').html(html);
                } else {
                    // No ratings and reviews available
                    $('#rating-section').html('<div class="text-center">No ratings and reviews found.</div>');
                }
            },
            error: function() {
                // Error handling if the ratings and reviews couldn't be loaded
                $('#loading-message').html('Error loading ratings and reviews.');
            }
        });
    }
    

    // Convert the rating to a star icon
    function getStarRating(rating) {
        var stars = '';
        var fullStars = Math.floor(rating);
        var halfStar = rating % 1 >= 0.5 ? true : false;

        for (var i = 0; i < fullStars; i++) {
            stars += '<i class="fas fa-star"></i>';
        }

        if (halfStar) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        }

        return stars;
    }

    // Format the review date
    function formatDate(date) {
        var formattedDate = new Date(date);
        return formattedDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    // Call the loadRatingsAndReviews function to fetch and display the initial ratings and reviews
    loadRatingsAndReviews();
});
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