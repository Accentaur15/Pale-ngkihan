<?php



session_start();
include_once('../php/config.php');
$unique_id = $_SESSION['unique_id'];


if (empty($unique_id)) {
  header("Location: sellerlogin.php");
}

$qry = mysqli_query($conn, "SELECT * FROM seller_accounts WHERE unique_id = '{$unique_id}'");

if (mysqli_num_rows($qry) > 0) {
  $row = mysqli_fetch_assoc($qry);
  if ($row) {
    $shopname = $row['shop_name'];
    $shoplogo = $row['shop_logo'];
    $sellerid = $row['id'];
  }
}

  // Fetch products associated with the seller
  $productsQuery = mysqli_query($conn, "SELECT * FROM product_list WHERE seller_id = '{$sellerid}'");

  // Function to get product reviews for a given product_id
  function getProductReviews($conn, $product_id) {
    $reviewsQuery = mysqli_query($conn, "SELECT * FROM product_reviews WHERE product_id = '{$product_id}'");
    $reviews = array();
    while ($reviewRow = mysqli_fetch_assoc($reviewsQuery)) {
      $reviews[] = $reviewRow;
    }
    return $reviews;
  }
?>
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seller | Customer Messages</title>


  <!--title icon-->
  <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png"/>
  <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png"/>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../Assets/plugins/fontawesome-free/css/all.min.css">
  <script src="https://kit.fontawesome.com/0ad1512e05.js" crossorigin="anonymous"></script>
    <!-- css style -->
<link href="getchat_users.css" rel="stylesheet">
  <!-- Theme style -->
  <link rel="stylesheet" href="../Assets/dist/css/adminlte.min.css">
  <!-- Image JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<link rel="stylesheet" href="../Assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">


</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php 
include('../Assets/includes/topbar.php');
include('../Assets/includes/sidebar.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  
    <!-- Main content -->
    <div class="content mt-4">
      <div class="container-fluid">

     <!-- Card Container -->
<div class="card shadow rounded">
    <div class="card-body">
        <!-- User Profile Section -->
        <div class="wrapper">
            <section class="users">
                <header>
                    <div class="content">
                        <img src="../<?php echo $shoplogo ?>" alt="">
                        <div class="details">
                            <span><?php echo  $shopname ?></span>
                           <!-- Conditional statement to display "Active Now" -->
<p>
    <?php
    if ($row['online_status'] == 1) {
        echo "Active Now";
    } else {
        echo"Offline Now";
    }
    ?>
</p>

                        </div>
                    </div>
                    <div class="searchdiv">
                <!-- Combined search input and button as an input group -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search users...">
                    <button class="btn btn-success" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
                </header>
                <div class="search">
                    <span class="text">Select a message to start a chat</span>
                </div>
                <div class="users-list">
    <!-- Include the dynamic user list from chat.php using AJAX -->
</div>
            </section>
        </div>
        <!-- End of User Profile Section -->
    </div>
</div>
<!-- End of Card Container -->



        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <script>
 const searchBar = document.querySelector("input[type='text'][placeholder='Search users...']");
    const usersList = document.querySelector(".users-list");

    // Function to load existing conversations and update the user list
    function loadConversations() {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "../php/get_buyerusers.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    usersList.innerHTML = data;
                }
            }
        };
        xhr.send();
    }

    // Load existing conversations when the page loads
    loadConversations();

    // Handle search functionality
    searchBar.onkeyup = () => {
        let searchTerm = searchBar.value.trim();
        if (searchTerm === "") {
            // If the search term is empty, load existing conversations
            loadConversations();
        } else {
            // Perform a search for matching sellers
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../php/get_buyerusers.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;
                        usersList.innerHTML = data;
                    }
                }
            };
            xhr.send("searchTerm=" + searchTerm);
        }
    };
    
// Handle click on a user (seller) to start a chat
usersList.addEventListener("click", (event) => {
    // Check if the clicked element is an <a> tag (seller link)
    if (event.target.tagName === "A") {
        // Prevent the default link behavior
        event.preventDefault();
        // Extract the unique_id from the link's data attribute
        let uniqueId = event.target.getAttribute("data-buyer-id");
        // Navigate to the chat page with the selected seller's unique_id
        window.location.href = "chat.php?unique_id=" + uniqueId;
    }
});

    </script>



<?php
    include('../Assets/includes/footer.php');
?>