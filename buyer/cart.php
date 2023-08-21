<?php
session_start();
include_once('../php/config.php');
include_once('../php/cart_functions.php'); 

// Function to get seller name
function getSellerName($seller_id, $conn) {
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
        $_SESSION['id'] =  $id;
    }
}

$category_ids = isset($_GET['cids']) ? $_GET['cids'] : 'all';

// Get the sort option from the URL parameter
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : '';

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
<link href="../buyer/marketplace.css" rel="stylesheet">
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
                    <a class="nav-link mx-3" href="../buyer/wholesale.php">Wholesale</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="buyeraboutus.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="../buyer/myorders.php">My Orders</a>
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
    <div class="card card-outline card-success rounded-0 shadow-0">
        <div class="card-header">
            <h5 class="card-title">Cart List</h5>
        </div>
        <div class="card-body">
        <div id="cart-list">
            <div class="row">
                <?php 
                $gtotal = 0;
                $vendors = $conn->query("SELECT * FROM `seller_accounts` WHERE id IN (SELECT seller_id FROM product_list WHERE id IN (SELECT product_id FROM `cart_list` WHERE buyer_id ='{$id}') ORDER BY `shop_name` ASC)");
                while ($vrow = $vendors->fetch_assoc()):
                ?>
                    <div class="col-12 border">
                        <span>Vendor: <b><?= $vrow['unique_id'] . " - " . $vrow['shop_name'] ?></b></span>
                    </div>
                    <div class="col-12 border p-0">
                        <?php 
                        $vtotal = 0;
                        $products = $conn->query("SELECT c.*, p.product_name as `name`, p.price, p.product_image FROM `cart_list` c INNER JOIN product_list p ON c.product_id = p.id WHERE c.buyer_id = '{$id}' AND p.seller_id = '{$vrow['id']}' ORDER BY p.product_name ASC");
                        while ($prow = $products->fetch_assoc()):
                            $total = $prow['price'] * $prow['quantity'];
                            $gtotal += $total;
                            $vtotal += $total;
                        ?>
                        <div class="d-flex align-items-center border p-2">
                            <div class="col-2 text-center">
                                <a href="../buyer/view_product.php?id=<?= $prow['product_id'] ?>"><img src="../<?= $prow['product_image'] ?>" alt="" class="prod-img img-center border bg-gradient-gray"></a>
                            </div>
                            <div class="col-auto flex-shrink-1 flex-grow-1">
                                <h4><b><?= $prow['name'] ?></b></h4>
                                <div class="d-flex">
                                    <div class="col-auto px-0"><small class="text-muted">Price: </small></div>
                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive font-weight-bold"><?= $prow['price'] ?></small></p></div>
                                </div>
                                <div class="d-flex">
                                    <div class="col-auto px-0"><small class="text-muted">Qty: </small></div>
                                    <div class="col-auto">
                                        <div class="" style="width:10em">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend"><button class="btn btn-success min-qty" data-id="<?= $prow['id'] ?>" type="button"><i class="fa fa-minus"></i></button></div>
                                                <input type="text" value="<?= $prow['quantity'] ?>" class="form-control text-center" readonly="readonly">
                                                <div class="input-group-append"><button class="btn btn-success plus-qty" data-id="<?= $prow['id'] ?>" type="button"><i class="fa fa-plus"></i></button></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto flex-shrink-1 flex-grow-1">
                                        <button class="btn btn-flat btn-outline-danger btn-sm rem_item" data-id="<?= $prow['id'] ?>"><i class="fa fa-times"></i> Remove</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 text-right"><?= $total ?></div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="col-12 border">
                        <div class="d-flex">
                            <div class="col-9 text-right font-weight-bold text-muted">Total</div>
                            <div class="col-3 text-right font-weight-bold"><?= $vtotal ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <div class="col-12 border">
                    <div class="d-flex">
                        <div class="col-9 h4 font-weight-bold text-right text-muted">Grand Total</div>
                        <div class="col-3 h4 font-weight-bold text-right"><?= $gtotal ?></div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
    <div class="clear-fix mb-2"></div>
    <div class="text-right">
        <a href="../buyer/checkout.php" class="btn btn-flat btn-success btn-md"><i class="fa fa-money-bill-alt"></i> Checkout</a>
    </div>
</div>

<!-- Your existing HTML code ... -->

<script>
$(function () {
  $('.plus-qty').click(function () {
    var group = $(this).closest('.input-group');
    var qty = parseFloat(group.find('input').val()) + 1;
    group.find('input').val(qty);
    var cart_id = $(this).attr('data-id');
    var buyer_id = '<?php echo isset($_SESSION["id"]) ? $_SESSION["id"] : ""; ?>'; // Get the PHP session variable

    updateCartQuantity(cart_id, qty, 'add_quantity', buyer_id); // Call the function with action and buyer_id parameter
  });

  $('.min-qty').click(function () {
    var group = $(this).closest('.input-group');
    var currentQuantity = parseFloat(group.find('input').val());
    if (parseFloat(group.find('input').val()) === 1) {
      showAlert('Error', 'Minimum quantity reached.', 'error');
        return false;
    }
    //console.log('Quantity before subtraction:', currentQuantity); // Add this line to log the current quantity before subtraction
    var qty = parseFloat(group.find('input').val()) - 1;
    group.find('input').val(qty);
    //console.log('Quantity after subtraction:', qty); // Add this line to check the updated quantity
    var cart_id = $(this).attr('data-id');
    var buyer_id = '<?php echo isset($_SESSION["id"]) ? $_SESSION["id"] : ""; ?>'; // Get the PHP session variable

    // Update the AJAX request to include the 'quantity' parameter
    updateCartQuantity(cart_id, qty, 'subtract_quantity', buyer_id);  
});

  $('.rem_item').click(function () {
    var cart_id = $(this).attr('data-id');
    var buyer_id = '<?php echo isset($_SESSION["id"]) ? $_SESSION["id"] : ""; ?>'; // Get the PHP session variable

    deleteCartItem(cart_id, buyer_id); // Call the function to delete the cart item with buyer_id parameter
  });
});

function updateCartQuantity(cart_id, quantity, action, buyer_id) {
  start_loader();


  $.ajax({
    url: '../php/master.php', // Update the URL to match the correct path of your PHP file
    method: 'POST',
    data: { action: action, cart_id: cart_id, quantity: quantity, buyer_id: buyer_id }, // Include the action and buyer_id in the data object
    dataType: 'json',

    error: function (err) {
      console.error(err);
      showAlert('Error', 'An error occurred while updating quantity.', 'error');
      end_loader();
    },
    success: function (resp) {
      console.log('Response:', resp);
      end_loader();
      if (resp.status === 'success') {
        showAlert('Quantity Updated', 'The quantity has been updated successfully.', 'success');
      } else if (resp.status === 'error') {
        showAlert('Error', resp.msg, 'error');
        //console.log('Received Quantitycart:', resp.received_quantity); // Access the current quantity here
      } else {
        showAlert('Error', 'An error occurred. Please try to refresh this page.', 'error');
      }
    },
  });
}

function deleteCartItem(cart_id, buyer_id) {
  _conf('Are you sure you want to delete this item from the cart?', function () {
    start_loader();

    $.ajax({
      url: '../php/master.php',
      method: 'POST',
      data: { action: 'delete_cart_item', cart_id: cart_id, buyer_id: buyer_id },
      dataType: 'json',
      success: function (resp) {
        console.log('Response:', resp);
        end_loader();
        if (resp.status === 'success') {
          showAlert('Item Removed', 'The item has been removed from the cart.', 'success');
        } else if (resp.status === 'error') {
          showAlert('Error', resp.msg, 'error');
        } else {
          showAlert('Error', 'An error occurred. Please try to refresh this page.', 'error');
        }
      },
      error: function (err) {
        console.error(err);
        alertError('An error occurred while deleting item from the cart.');
        end_loader();
      },
    });
  });
}


// Rest of the JavaScript functions remain unchanged.

function start_loader() {
  // Implement your loader display logic here (if needed).
}

function end_loader() {
  // Implement your loader hide logic here (if needed).
}

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

function alertError(text) {
  showAlert('Error', text, 'error');
}

function _conf(text, callback) {
  Swal.fire({
    title: 'Confirmation',
    text: text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes',
  }).then(function (result) {
    if (result.isConfirmed) {
      if (typeof callback === 'function') {
        callback();
      } else {
        showAlert('Error', 'Invalid function callback.', 'error');
      }
    }
  });
}
</script>
<!-- Rest of your HTML code ... -->

    </div>

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
</body>
</html>
