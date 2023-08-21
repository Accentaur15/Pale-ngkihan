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



$cartItemCount = getCartItemCount($conn, $id);
include_once('../php/notifications.php'); 
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buyer | Chat</title>
</head>
<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="../Assets/plugins/fontawesome-free/css/all.min.css">
<script src="https://kit.fontawesome.com/0ad1512e05.js" crossorigin="anonymous"></script>
<!--css-->
<link href="chat_users.css" rel="stylesheet">
<!-- Theme style -->
<link rel="stylesheet" href="../Assets/dist/css/adminlte.min.css">
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
                    <a class="nav-link  mx-3" href="../buyer/marketplace.php">Marketplace</a>
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
                <li class="nav-item">
                    <a class="nav-link active mx-2" href="../buyer/chat_users.php"><i class="fa-solid fa-message"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        echo '<img src="../' . $profilePicture . '" alt="Profile Picture" class="avatar-image ">';
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

    <div class="container  flex-grow-1">
        <div class="content py-5">

<!-- Card Container -->
<div class="card shadow rounded">
    <div class="card-body">
        <!-- User Profile Section -->
        <div class="wrapper">
            <section class="users">
                <header>
                    <div class="content">
                        <img src="../<?php echo $profilePicture ?>" alt="">
                        <div class="details">
                            <span><?php echo $fname. " " . $lname ?></span>
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



<script>

    const searchBar = document.querySelector("input[type='text'][placeholder='Search users...']");
    const usersList = document.querySelector(".users-list");

    // Function to load existing conversations and update the user list
    function loadConversations() {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "../php/get_users.php", true);
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
            xhr.open("POST", "../php/get_users.php", true);
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
        let uniqueId = event.target.getAttribute("data-seller-id");
        // Navigate to the chat page with the selected seller's unique_id
        window.location.href = "chat.php?unique_id=" + uniqueId;
    }
});

</script>


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
