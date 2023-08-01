<?php
session_start();
include_once('../php/config.php');
include_once('../php/cart_functions.php');

// Function to get seller name
function getSellerName($seller_id, $conn)
{
    $seller_id = mysqli_real_escape_string($conn, $seller_id);
    $result = mysqli_query($conn, "SELECT shop_name FROM seller_accounts WHERE id = '{$seller_id}'");
    $row = mysqli_fetch_assoc($result);
    return $row['shop_name'] ?? '';
}

$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
    header("Location: ../buyerlogin.php");
    exit;
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
        $_SESSION['id'] = $id;
        $delivery_address = $row['address'];
    }
}

$category_ids = isset($_GET['cids']) ? $_GET['cids'] : 'all';

// Get the sort option from the URL parameter
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : '';

$cartItemCount = getCartItemCount($conn, $id);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buyer | Checkout</title>
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
<link href="../buyer/marketplace.css" rel="stylesheet">
<!--fontawesome-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png" />
<link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png" />
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
                    <a class="nav-link mx-3" href="buyeraboutus.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="#">My Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="#"><i class="fa-solid fa-bell"></i></a>
                </li>
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
                    <a class="nav-link mx-3" href="#"><i class="fas fa-calendar-day"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        echo '<img src="../' . $profilePicture . '" alt="Profile Picture" class="avatar-image img-fluid">';
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <li class="dropdown-header text-center text-md font-weight-bold text-dark">
                            <?php echo $fname . ' ' . $lname; ?>
                        </li>
                        <li class="text-center"><a class="dropdown-item" href="buyermyaccount.php">My Account</a></li>
                        <li class="text-center"><a class="dropdown-item"
                                href="../php/logout.php?logout_id=<?php echo $unique_id ?>">Log out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!--End of Navigation Bar-->

<body class="d-flex flex-column min-vh-100">
    <div class="container flex-grow-1">

        <style>
            .prod-img {
                width: calc(100%);
                height: auto;
                max-height: 10em;
                object-fit: scale-down;
                object-position: center center;
            }
        </style>

        <div class="content py-3">
            <div class="card card-outline card-success shadow rounded-0">
                <div class="card-header">
                    <div class="h5 card-title">Checkout</div>
                </div>
                <div class="card-body">
                    <div class="form-group">


                        <div class="row">



                            <div class="col-md-8">
                                <form action="" id="checkout-form">
<!-- Add this div to display error messages -->
<div class="alert alert-danger" id="error-message" style="display: none;"></div>

                                    <!-- Payment Method Section -->
                                    <div class="form-group">
                                        <div class="payment-method-box border rounded p-2">
                                            <div class="row">
                                                <label for="payment_method" class="control-label col-md-3">Payment
                                                    Method</label>
                                                <div class="col-md-9">
                                                    <select name="payment_method" id="payment_method"
                                                        class="form-control form-control-sm" required>
                                                        <option value="" disabled selected>Select Payment Method
                                                        </option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="Online Payment">Online Payment</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Payment Method Section -->

                                    <!-- Delivery Method Section -->
                                    <div class="form-group">
                                        <div class="delivery-method-box border rounded p-2">
                                            <div class="row">
                                                <label for="delivery_method" class="control-label col-md-3">Delivery
                                                    Method</label>
                                                <div class="col-md-9">
                                                    <select name="delivery_method" id="delivery_method"
                                                        class="form-control form-control-sm" required>
                                                        <option value="" disabled selected>Select Delivery Method
                                                        </option>
                                                        <option value="Pick Up">Pick Up</option>
                                                        <option value="Delivery">Delivery</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Delivery Method Section -->




                                    <div class="form-group">
                                        <label for="delivery_address" class="control-label">Delivery Address</label>
                                        <textarea name="delivery_address" id="delivery_address" rows="4"
                                            class="form-control rounded-0"
                                            required><?php echo $delivery_address; ?></textarea>
                                    </div>






                                    <div class="form-group text-right">
                                        <button class="btn btn-flat btn-default btn-sm bg-success">Place Order</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="row" id="summary">
                                    <div class="col-12 border rounded">
                                        <h2 class="text-center"><b>Summary</b></h2>
                                    </div>
                                    <?php
                                    $gtotal = 0;
                                    $vendors = $conn->query("SELECT * FROM `seller_accounts` WHERE id IN (SELECT seller_id FROM product_list WHERE id IN (SELECT product_id FROM cart_list WHERE buyer_id = '{$id}')) ORDER BY `shop_name` ASC");
                                    while ($vrow = $vendors->fetch_assoc()):
                                        $vtotal = $conn->query("SELECT sum(c.quantity * p.price) FROM `cart_list` c inner join product_list p on c.product_id = p.id where c.buyer_id = '{$id}' and p.seller_id = '{$vrow['id']}'")->fetch_array()[0];
                                        $vtotal = $vtotal > 0 ? $vtotal : 0;
                                        $gtotal += $vtotal;

                                        ?>
                                        <div class="col-12 border item">
                                            <b class="text-muted"><small>
                                                    <?= $vrow['unique_id'] . " - " . $vrow['shop_name'] ?>
                                                </small></b>
                                            <div class="text-right"><b>
                                                    <?= $vtotal ?>
                                                </b></div>
                                        </div>
                                    <?php endwhile; ?>
                                    <div class="col-12 border">
                                        <b class="text-muted">Grand Total</b>
                                        <div class="text-right h3" id="total"><b>
                                                <?= $gtotal ?>
                                            </b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Your existing HTML code ... -->

            <script>
                // Function to show the loading indicator
                function start_loader() {
                    // Show your loading indicator or spinner here
                    // For example, you can display an overlay or a loading icon
                    // based on your UI design and requirements.
                }

                // Function to hide the loading indicator
                function end_loader() {
                    // Hide your loading indicator or spinner here
                    // Remove the overlay or loading icon that was displayed during loading.
                    // This function should be called when the AJAX request is completed.
                }


                function showAlert(title, text, icon, isError = false) {
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
    // Display error message on the form
    if (isError) {
        $("#error-message").text(text).show();
    }
}

                $('#checkout-form').submit(function (e) {
                    e.preventDefault();
                    var _this = $(this);
                    if (_this[0].checkValidity() == false) {
                        _this[0].reportValidity();
                        return false;
                    }
                    if ($('#summary .item').length <= 0) {
                        showAlert("Error", "There are no items listed in the cart yet.", "error");
                        return false;
                    }
                    $('.pop_msg').remove();
                    var el = $('<div>');
                    el.addClass("alert alert-danger pop_msg");
                    el.hide();

                    // Perform AJAX form submission
                    $.ajax({
                        url: '../php/process_checkout.php', // Change this to the path of the PHP script handling the form submission
                        method: 'POST',
                        data: _this.serialize(),
                        dataType: 'json',
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText); // Log the responseText, which should contain the error message sent from the server
                            showAlert("Error", "An error occurred.", "error");
                            end_loader();
                        },
                        success: function (resp) {
                        if (resp.status == 'success') {
                            // Order placed successfully, show the success message and redirect if needed
                            showAlert("Success", "Order placed successfully!", "success");
                            // You can choose to redirect to a different page after the successful order placement.
                            //location.reload();

                            // Delay the redirection by 2 seconds (adjust the time as needed)
                            setTimeout(function () {
                                // Redirect to the buyermain.php page after the delay
                                window.location.href = '../buyer/buyermain.php';
                            }, 2000); // 2000 milliseconds = 2 seconds
                        } else if (!!resp.msg) {
                            // Display error message
                            showAlert("Error", resp.msg, "error", true);
                        } else {
                            // Default error message if no specific error message is returned
                            showAlert("Error", "An error occurred.", "error", true);
                        }
                        end_loader();
                    }
                    });
                });
            </script>

            <!-- Rest of your HTML code ... -->

        </div>

        <!-- REQUIRED SCRIPTS -->
        <!-- Bootstrap 5 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
            crossorigin="anonymous"></script>
        <!-- AdminLTE App -->
        <script src="../Assets/dist/js/adminlte.min.js"></script>
        <!--Animation java-->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <!-- SweetAlert2 -->
        <script src="../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script>
            AOS.init();
        </script>
    </div>
    <!-- Footer -->
    <footer class="copyright py-4 text-center text-white  p-2">
        <div class="container">
            <small>&copy; Pale-ngkihan 2023</small>
        </div>
    </footer>
</body>

</html>