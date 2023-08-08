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
    <title>Buyer | Marketplace</title>
</head>
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
                    <a class="nav-link mx-3" href="../buyer/cart.php"><i class="fa-solid fa-cart-shopping"></i>
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

<!--End of Navigation Bar-->

<body class="d-flex flex-column min-vh-100">

    <div class="container flex-grow-1">
        <div class="content py-3">
            <div class="row">
                <div class="col-md-4">
                <div class="card card-outline rounded-0 card-success shadow">
                    <div class="card-header h5">
                        <h4 class="card-title">
                        <i class="fa fa-filter" style="color: #312817;">&nbsp;&nbsp;</i>Search Filter
                        </h4>
                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                        <label for="sort-select">Sort By:</label>
                        <div class="input-group">
                            <select class="form-control" id="sort-select">
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                            </select>
                            <div class="input-group-append">
                            <button class="btn btn-success" id="sort-button">Sort</button>
                            </div>
                        </div>
                        </div>
                        <hr class="mx-n3">
                        <label>Category</label>
                        <div class="list-group">
                        <div class="list-group-item list-group-item-action">
                            <div class="custom-control custom-checkbox">
                            <input class="custom-control-input cat_all" type="checkbox" id="cat_all" <?= $category_ids == 'all' ? "checked" : "" ?>>
                            <label class="custom-control-label" for="cat_all">All</label>
                            </div>
                        </div>
                        <?php
                        $categories = $conn->query("SELECT * FROM category_list WHERE status = 1 ORDER BY name ASC");
                        while ($row = $categories->fetch_assoc()):
                        ?>
                        <div class="list-group-item list-group-item-action">
                            <div class="custom-control custom-checkbox">
                            <input class="custom-control-input cat_item" type="checkbox" id="cat_item<?= $row['id'] ?>" <?= in_array($row['id'], explode(',', $category_ids)) ? "checked" : '' ?> value="<?= $row['id'] ?>">
                            <label class="custom-control-label" for="cat_item<?= $row['id'] ?>"><?= $row['name'] ?></label>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        </div>
                    </div>
                    </div>


                    
                </div>
                <div class="col-md-8">
                    <div class="card card-outline card-success shadow rounded-0">
                        <div class="card-body">
                            <div class="container-fluid">
                                <div class="row justify-content-center mb-3">
                                    <div class="col-lg-8 col-md-10 col-sm-12">
                                        <form action="" id="search-frm">
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">Search</span></div>
                                                <input type="search" id="search" class="form-control" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row" id="product_list">
                                    <?php 
                                    $swhere = "";
                                    if (!empty($category_ids)) {
                                        if ($category_ids != 'all') {
                                            $swhere = " and p.category_id in ({$category_ids}) ";
                                        }
                                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                                            $swhere .= " and (p.product_name LIKE '%{$_GET['search']}%' or p.product_description LIKE '%{$_GET['search']}%' or c.name LIKE '%{$_GET['search']}%' or s.shop_name LIKE '%{$_GET['search']}%') ";
                                        }

                                        // Sort option handling
                                        $sortSql = "";
                                        switch ($sort_option) {
                                            case 'price_asc':
                                                $sortSql = " ORDER BY p.price ASC";
                                                break;
                                            case 'price_desc':
                                                $sortSql = " ORDER BY p.price DESC";
                                                break;
                                            case 'name_asc':
                                                $sortSql = " ORDER BY p.product_name ASC";
                                                break;
                                            case 'name_desc':
                                                $sortSql = " ORDER BY p.product_name DESC";
                                                break;
                                            default:
                                                $sortSql = " ORDER BY RAND()";
                                                break;
                                        }

                                        $products = $conn->query("SELECT p.*, s.shop_name as vendor, c.name as `category` FROM `product_list` p 
                                        INNER JOIN seller_accounts s ON p.seller_id = s.id 
                                        INNER JOIN category_list c ON p.category_id = c.id 
                                        WHERE p.status = 1 {$swhere} 
                                        {$sortSql}");
                                        
                                        while ($row = $products->fetch_assoc()):
                                    ?>
                                    <div class="col-lg-4 col-md-6 col-sm-12 product-item">
                                        <a href="view_product.php?id=<?= $row['id'] ?>" class="card shadow rounded-0 text-reset text-decoration-none">
                                            <div class="product-img-holder position-relative">
                                                <img src="../<?= $row['product_image'] ?>" alt="Product-image" class="img-top product-img bg-gradient-gray">
                                            </div>
                                            <div class="card-body border-top border-gray">
                                                <h3 class="card-title text-truncate font-weight-bold display-1"><?= $row['product_name'] ?></h3> <br>
                                                <div class="d-flex">
                                                    <div class="col-auto px-0"><small class="text-muted">Seller: </small></div>
                                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                                        <p class="text-truncate m-0"><small class="text-muted"><?= getSellerName($row['seller_id'], $conn) ?></small></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="col-auto px-0"><small class="text-muted">Category: </small></div>
                                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                                        <p class="text-truncate m-0"><small class="text-muted"><?= $row['category'] ?></small></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="col-auto px-0"><small class="text-muted">Price: </small></div>
                                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                                        <p class="m-0 pl-3"><small class="text-olive font-weight-bold"><?= $row['price'] ?>/<?= $row['unit'] ?></small></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="col-auto px-0"><small class="text-muted">Quantity: </small></div>
                                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                                        <p class="m-0 pl-3"><small class="text-olive font-weight-bold">₱<?= $row['quantity'] ?></small></p>
                                                    </div>
                                                </div>
                                                <p class="card-text truncate-3"><?= strip_tags(html_entity_decode($row['product_description'])) ?></p>
                                            </div>
                                        </a>
                                    </div>
                                    <?php endwhile; ?>
                                    <?php } else { ?>
                                    <div class="col-12 text-center">
                                        Please select at least 1 product category
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="copyright py-4 text-center text-white  p-2">
        <div class="container">
            <small>&copy; Pale-ngkihan 2023</small>
        </div>
    </footer>

    <!-- ...existing code... -->

<!-- ...existing code... -->

<script>
$(function () {
  function updateCategorySelection() {
    var selectedCategories = $('.cat_item:checked').map(function () {
      return $(this).val();
    }).get();

    var allChecked = selectedCategories.length === $('.cat_item').length;
    $('#cat_all').prop('checked', allChecked);

    var urlParams = new URLSearchParams(window.location.search);
    urlParams.set('cids', selectedCategories.length ? selectedCategories.join(",") : 'all');

    if (selectedCategories.length > 0) {
      window.location.href = window.location.pathname + '?' + urlParams.toString();
    } else {
      $('#product_list').html('<div class="col-12 text-center">Please select at least 1 product category</div>');
    }
  }

  function updateSortOption() {
    var sortOption = $('#sort-select').val();

    var urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sortOption);

    window.location.href = window.location.pathname + '?' + urlParams.toString();
  }

  $('.cat_item').change(function () {
    updateCategorySelection();
  });

  $('#cat_all').change(function () {
    var isChecked = $(this).is(':checked');
    $('.cat_item').prop('checked', isChecked);
    updateCategorySelection();
  });

  $('#sort-button').click(function (e) {
    e.preventDefault();
    updateSortOption();
  });

  $('#search-frm').submit(function (e) {
    e.preventDefault();
    var selectedCategories = $('.cat_item:checked').map(function () {
      return $(this).val();
    }).get();
    var searchQuery = $('#search').val();

    var urlParams = new URLSearchParams(window.location.search);
    urlParams.set('search', searchQuery);
    urlParams.set('cids', selectedCategories.length ? selectedCategories.join(",") : 'all');

    if (selectedCategories.length > 0) {
      window.location.href = window.location.pathname + '?' + urlParams.toString();
    } else {
      $('#product_list').html('<div class="col-12 text-center">Please select at least 1 product category</div>');
    }
  });

  // Check if all checkboxes are unchecked and update the "All" checkbox
  function checkAllCheckbox() {
    var allUnchecked = $('.cat_item:not(:checked)').length === 0;
    $('#cat_all').prop('checked', allUnchecked);
  }

  // Check initial state of checkboxes based on URL parameter
  checkAllCheckbox();

  // Update the "All" checkbox when individual category checkboxes are clicked
  $('.cat_item').click(function() {
    checkAllCheckbox();
  });

});
</script>

<!-- ...remaining code... -->


    <!-- ...remaining code... -->

    <!-- REQUIRED SCRIPTS -->
            <!-- SweetAlert2 -->
            <script src="../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- jQuery -->
    <script src="../Assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- AdminLTE App -->
    <script src="../Assets/dist/js/adminlte.min.js"></script>
    <!--Animation java-->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
