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

// Get the sort option from the URL parameter
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : '';
$cartItemCount = getCartItemCount($conn, $id);
include_once('../php/notifications.php'); 

// Function to retrieve bids offered by the user
function getBidsOffered($conn, $userId) {
    $userId = mysqli_real_escape_string($conn, $userId);
    $bids = array();

    $query = "SELECT bb.*, hs.rice_type 
              FROM buyer_bids bb
              JOIN harvest_schedule hs ON bb.harvest_schedule_id = hs.id
              WHERE bb.buyer_id = '{$userId}'";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $bids[] = $row;
    }

    return $bids;
}


// Function to get bid status color
function getBidStatusColor($status) {
    switch ($status) {
        case 'pending':
            return 'warning';
        case 'accepted':
            return 'success';
        case 'rejected':
            return 'danger';
        case 'canceled':
            return 'secondary';
        default:
            return 'info';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buyer | Wholesale</title>
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
    <!-- CSS -->
    <link href="wholesale.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png"/>
    <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png"/>
    <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png"/>
      <!-- DataTables -->
  <link rel="stylesheet" href="../Assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">


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
                    <a class="nav-link  mx-3" href="../buyer/marketplace.php">Marketplace</a>
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
                    <ul class="dropdown-menu dropdown-menu-right custom-dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li class="dropdown-header text-center text-md font-weight-bold text-dark"><?php echo $fname . ' ' . $lname; ?></li>
                        <li class="text-center"><a class="dropdown-item" href="buyermyaccount.php">My Account</a></li>
                        <li class="text-center"><a class="dropdown-item" href="../php/logout.php?logout_id=<?php echo $unique_id ?>">Log out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- End of Navigation Bar -->

<div class="container flex-grow-1">
    <div class="content py-3">

        <div class="card card-outline rounded-0 card-success shadow">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                <a class="nav-link active" id="schedules-tab" data-bs-toggle="tab" href="#schedules" role="tab" aria-controls="schedules" aria-selected="true">Schedules</a>

                    </li>
                    <li class="nav-item" role="presentation">
                       <a class="nav-link" id="bids-offered-tab" data-bs-toggle="tab" href="#bids-offered" role="tab" aria-controls="bids-offered" aria-selected="false">Bids Offered</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="schedules" role="tabpanel" aria-labelledby="schedules-tab">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-outline rounded-0 border-2 mt-3">
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
                            <option value="starting_bid_asc">Starting Bid: Low to High</option>
    <option value="starting_bid_desc">Starting Bid: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                            <option value="date_asc">Date: Old to New</option>
    <option value="date_desc">Date: New to Old</option>
                            </select>
                            <div class="input-group-append">
                            <button class="btn btn-success" id="sort-button">Sort</button>
                            </div>
                        </div>
                        </div>

                        <div class="form-group">
    <label for="date-range">Date Range:</label>
    <div class="input-group">
        <input type="date" id="date-start" name="date_start" class="form-control" placeholder="Start date">
        <span class="input-group-text">to</span>
        <input type="date" id="date-end" name="date_end" class="form-control" placeholder="End date">
        <div class="input-group-append">
            <button class="btn btn-success" id="filter-button">Filter</button>
        </div>
    </div>
</div>

<div class="form-group">
    <button id="clear-filter-button" class="btn btn-secondary btn-block">Clear Filters</button>
</div>


                       
                    </div>
                    
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card card-outline shadow mt-3 border-top rounded-0">
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div class="row justify-content-center mb-3">
                                                <div class="col-lg-8 col-md-10 col-sm-12">
                                                    <form action="" id="search-frm">
                                                        <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">Search</span></div>
                                                        <input type="search" id="search" name="search" class="form-control" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="row" id="harvest_list">
                                                <?php 
                                              $swhere = "";

                                              if (isset($_GET['search']) && !empty($_GET['search'])) {
                                                  $searchTerm = mysqli_real_escape_string($conn, $_GET['search']); // Sanitize the search term
                                                  $swhere .= " AND (hs.rice_type LIKE '%$searchTerm%' OR hs.location LIKE '%$searchTerm%' OR sa.shop_name LIKE '%$searchTerm%') ";
                                              }
                                              

                                                $sort_option = isset($_GET['sort']) ? $_GET['sort'] : '';

                                                // Define the default sorting SQL if no sort option is provided
                                                $defaultSortSql = " ORDER BY RAND()";
                                                
                                                switch ($sort_option) {
                                                    case 'name_asc':
                                                        $sortSql = " ORDER BY hs.rice_type ASC";
                                                        break;
                                                    case 'name_desc':
                                                        $sortSql = " ORDER BY hs.rice_type DESC";
                                                        break;
                                                    case 'starting_bid_asc':
                                                        $sortSql = " ORDER BY hs.starting_bid ASC";
                                                        break;
                                                    case 'starting_bid_desc':
                                                        $sortSql = " ORDER BY hs.starting_bid DESC";
                                                        break;
                                                        case 'date_asc':
                                                            $sortSql = " ORDER BY hs.date_scheduled ASC";
                                                            break;
                                                        case 'date_desc':
                                                            $sortSql = " ORDER BY hs.date_scheduled DESC";
                                                    default:
                                                        $sortSql = $defaultSortSql;
                                                        break;
                                                }
                                                
                                                // Retrieve the start and end dates from the URL parameters
$startDate = isset($_GET['date_start']) ? $_GET['date_start'] : '';
$endDate = isset($_GET['date_end']) ? $_GET['date_end'] : '';

// Create the date range condition only if both start and end dates are provided
$dateRangeCondition = '';
if (!empty($startDate) && !empty($endDate)) {
    $formattedStartDate = date("Y-m-d", strtotime($startDate));
    $formattedEndDate = date("Y-m-d", strtotime($endDate));
    $dateRangeCondition = " AND hs.date_scheduled BETWEEN '{$formattedStartDate}' AND '{$formattedEndDate}' ";
}
                                                
                                                
                                                $query = "SELECT hs.*, sa.shop_name as vendor FROM harvest_schedule hs 
                                                INNER JOIN seller_accounts sa ON hs.seller_id = sa.id 
                                                WHERE hs.status IN ('upcoming', 'ongoing') {$swhere}  {$dateRangeCondition}
                                                {$sortSql}";
                                      

                                                $products = $conn->query($query);
                                                while ($row = $products->fetch_assoc()):
                                                ?>
                                                <!-- Product Card -->
                                                <div class="col-lg-4 col-md-4 col-sm-12 product-item">
                                                <a href="viewschedule.php?id=<?= $row['id'] ?>" class="card shadow rounded-0 text-reset text-decoration-none">
                                            <div class="product-img-holder position-relative">
                                                <img src="<?= $row['harvest_image'] ?>" alt="Product-image" class="img-top product-img bg-gradient-gray">
                                            </div>
                                            <div class="card-body border-top border-gray">
                                                <h3 class="card-title text-truncate font-weight-bold display-1"><?= $row['rice_type'] ?></h3> <br>
                                                <div class="d-flex">
                                                    <div class="col-auto px-0"><small class="text-muted">Seller: </small></div>
                                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                                        <p class="text-truncate m-0"><small class="text-muted"><?= getSellerName($row['seller_id'], $conn) ?></small></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="col-auto px-0"><small class="text-muted">Harvest Status: </small></div>
                                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                                        <p class="text-truncate m-0"><small class="text-olive font-weight-bold"><?php
// Assuming $status contains one of the three enumerated values: 'upcoming', 'ongoing', or 'completed'
if ($row['status']  == 'upcoming') {
    // Output the specified badge for 'upcoming'
    echo '<span class="badge badge-info bg-gradient-info px-3 ml-1 rounded-pill"> Upcoming </span>';
} elseif ($row['status'] == 'ongoing') {
    // Output the specified badge for 'ongoing'
    echo '<span class="badge badge-primary bg-gradient-primary ml-1 px-3 rounded-pill"> Ongoing </span>';
} elseif ($row['status']  == 'completed') {
    // Output the specified badge for 'completed'
    echo '<span class="badge badge-success bg-gradient-success ml-1 px-3 rounded-pill"> Completed</span>';
} else {
    // Output the status without the badge for other cases
    echo $row['status'] ;
}
?></small></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="col-auto px-0"><small class="text-muted">Bidding Status: </small></div>
                                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                                        <p class="m-0 pl-1"><small class="text-olive font-weight-bold"> <?php
// Assuming $bidding_status contains one of the two values: 0 or 1
if ( $row['bidding_status'] == 0) {
    // Output the specified badge for bidding_status = 0
    echo '<span class="badge badge-danger px-3 ml-1 py-1 rounded-pill">Closed</span>';
} elseif ( $row['bidding_status'] == 1) {
    // Output the specified badge for bidding_status = 1
    echo '<span class="badge badge-success px-3 ml-1 py-1 rounded-pill">Open</span>';
} else {
    // Output a default badge for other cases (though this should not happen with only two possible values)
    echo '<span class="badge badge-danger px-3 ml-1 py-1 rounded-pill">N/A</span>';
}
?></small></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="col-auto px-0"><small class="text-muted">Starting Bid:</small></div>
                                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                                        <p class="m-0 pl-3"><small class="text-olive font-weight-bold">₱<?= $row['starting_bid'] ?> per kg</small></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="col-auto px-0"><small class="text-muted">Quantity:</small></div>
                                                    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                                        <p class="m-0 pl-3"><small class="text-olive font-weight-bold"><?= $row['quantity_available'] ?></small></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
    <div class="col-auto px-0"><small class="text-muted">Date Scheduled: </small></div>
    <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
        <p class="m-0 pl-3">
            <small class="text-olive font-weight-bold">
                <?= date('F j, Y', strtotime($row['date_scheduled'])) ?>
            </small>
        </p>
    </div>
</div>
                                            </div>
                                        </a>
                                                </div>
                                                <!-- End of Product Card -->
                                                <?php endwhile; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        <!-- Bids Offered Tab Content -->
        <div class="tab-pane fade" id="bids-offered" role="tabpanel" aria-labelledby="bids-offered-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Bids Offered</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table id="bids-offered-table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Bid Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Retrieve bids offered by the user
                                        $bidsOffered = getBidsOffered($conn, $id);

                                        // Loop through bids offered and populate rows
                                        foreach ($bidsOffered as $bidRow) {
                                            echo '<tr>';
                                            echo '<td>' . $bidRow['rice_type'] . '</td>';
                                            echo '<td>' . ($bidRow['bid_amount'] !== null ? '₱' . $bidRow['bid_amount'] : 'None') . '</td>';

                                            echo '<td class="text-center"><span class="badge badge-' . getBidStatusColor($bidRow['bid_status']) . '">' . $bidRow['bid_status'] . '</span></td>';
                                            echo '<td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-flat border btn-light btn-sm dropdown-toggle dropdown-icon" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    <a class="dropdown-item view_data" href="viewschedule.php?id=' . $bidRow['harvest_schedule_id'] . '">
                                                        <span class="fa-solid fa-eye text-dark"></span> View
                                                    </a>
                                                    
                                                    <div class="dropdown-divider"></div>';
                                        if ($bidRow['bid_status'] === 'pending') {
                                            echo '<a class="dropdown-item cancel-button" data-id="' . $bidRow['harvest_schedule_id'] . '">
                                                    <span class="fa-solid fa-circle-xmark text-danger"></span> Cancel
                                                </a>';
                                        }
                                        echo '</div>
                                            </div>
                                        </td>';
                                        
                                                               
                                        
                                        

                            
                                          echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
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
<!-- Animation JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="../Assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../Assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../Assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="../Assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="../Assets/plugins/jszip/jszip.min.js"></script>
        <script src="../Assets/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="../Assets/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>

$(document).ready(function() {

    $('#filter-button').click(function () {
    const startDate = $('#date-start').val();
    const endDate = $('#date-end').val();
    const currentURL = new URL(window.location.href);

    // Update the URL parameters with the selected start and end dates
    currentURL.searchParams.set('date_start', startDate);
    currentURL.searchParams.set('date_end', endDate);

    // Redirect to the updated URL
    window.location.href = currentURL.toString();
});


     // Capture the search form submit
     $('#search-frm').submit(function (e) {
        e.preventDefault(); // Prevent the default form submission
        
        const searchTerm = $('#search').val();
        const currentURL = new URL(window.location.href);

        // Update the URL parameter with the search term
        currentURL.searchParams.set('search', searchTerm);

        // Redirect to the updated URL
        window.location.href = currentURL.toString();
    });
// Capture the sort button click
$('#sort-button').click(function () {
    const selectedOption = $('#sort-select').val();
    const currentURL = new URL(window.location.href);

    // Update the URL parameter with the selected sorting option
    currentURL.searchParams.set('sort', selectedOption);

    // Redirect to the updated URL
    window.location.href = currentURL.toString();
});

});

    AOS.init();
     // Listen for click events on the "Cancel" buttons
     const cancelButtons = document.querySelectorAll('.cancel-button');
     cancelButtons.forEach(button => {
    button.addEventListener('click', function() {
        const scheduleId = this.getAttribute('data-id');
        // Display the confirmation popup
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the cancellation form
                const form = document.createElement('form');
                form.method = 'post';
                form.action = '../php/cancelschedule.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_cancel';
                input.value = scheduleId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();

                // Show success alert using showAlert function
                showAlert('Cancelled!', 'Your bid has been successfully cancelled.', 'success');
            }
        });
    });
});


// Add an event listener to the "Clear Filter" button
document.getElementById('clear-filter-button').addEventListener('click', function () {
    // Get the current URL
    const currentURL = new URL(window.location.href);

    // Remove the query parameters related to filters
    currentURL.searchParams.delete('search');
    currentURL.searchParams.delete('sort');
    currentURL.searchParams.delete('date_start');
    currentURL.searchParams.delete('date_end');

    // Redirect to the updated URL without filters
    window.location.href = currentURL.toString();
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

    $(document).ready(function() {
    $("#bids-offered-table").DataTable({
        "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    });
});
</script>
</body>
</html>

