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
    <title>Buyer | Home</title>
</head>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../Assets/plugins/fontawesome-free/css/all.min.css">
    <script src="https://kit.fontawesome.com/0ad1512e05.js" crossorigin="anonymous"></script>
    <!-- Theme style -->
    <link rel="stylesheet" href="../Assets/dist/css/adminlte.min.css">
    <!--css-->
    <link href="buyermain.css" rel="stylesheet">
    <!--title icon-->
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
                    <a class="nav-link active mx-3" aria-current="page" href="buyermain.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  mx-3" href="../buyer/marketplace.php">Marketplace</a>
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
                    <a class="nav-link mx-3" href="#"><i class="fas fa-calendar-day"></i></a>
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
                    <div class="dropdown-divider"></div>
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

      <header class="masthead text-center">
        <div class="container d-flex align-items-center flex-column">
          <div data-aos="fade-up" data-aos-easing="linear" data-aos-easing="linear" data-aos-duration="750">
            <!-- Masthead Heading-->
            <h1 class="masthead-heading mb-0">Fresh from the  <span style="color: #f5bb47;">fields</span> of<br> Arayat</h1>
            <!-- Icon Divider-->
            <div class="divider-custom divider-light">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fa fa-wheat"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Masthead Subheading-->
            <p class="masthead-subheading font-italic font-weight-normal mb-0 ">Our direct relationships with Arayat rice farmers mean that we can offer rice that's not only fresh, but also sustainably grown and ethically sourced.</p>
        </div>
        </div>
    </header>

    <!--Introduciton-->

    <div class="marketing_section layout_padding">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <div class="job_section">
              <div data-aos="fade-right" data-aos-easing="linear" data-aos-duration="1000">
                <h1 class="jobs_text" style="color:#F4BE52">Introduction</h1>
                <p class="dummy_text">Welcome to PALE-NGKIHAN, your gateway to the authentic flavors of Arayat rice. Our site offers a convenient and reliable way to purchase fresh, sustainably grown rice directly from the farmers of Arayat. By working with local growers and producers, we're able to offer a unique selection of rice varieties that you won't find anywhere else.</p>
              </div>
              </div>
          </div>
          <div class="col-md-6">
            
            <div class="image_1 padding_0 rounded float-left img-fluid max-width: 100% height: auto"><img src="../Assets/images/introduction-image.png"></div>
          
          </div>
        </div>
      </div>
    </div>
    <!--Second Box-->
    <div class="marketing_section layout_padding" style="margin-bottom: 1em;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
           
            <div class="image_1 padding_0"><img src="../Assets/images/image_2.png"></div>
          
          </div>
          <div class="col-md-6" style="background-color: #BEA35B; padding: 3em 0em 3em 3em;">
            <div class="job_section_2">
                <div data-aos="fade-right" data-aos-easing="linear" data-aos-duration="1000">
                <h1 class="jobs_text">Delight in our Wide Variety of Choices</h1>
                <p class="dummy_text">Whether you're a chef looking for a specific rice to complete a recipe, or a food lover interested in exploring the different flavors and textures of Arayat rice, we have something for you. Our site features a user-friendly ordering process, fast and reliable shipping, and a commitment to quality and authenticity that you won't find anywhere else.</p>
              </div>
              
              </div>
          </div>
        </div>
      </div>
    </div>

    <!-- footer section start-->
	<div class="footer_section d-flex p-2">
    <img src="../Assets/images/farmer cutout.png" class="farmer">
		<div class="container align-self-center">
      
			<h1 class="subscribr_text display-3">Explore Now</h1>
      
			<p class="lorem_text lead">Navigate through our website today and discover the rich history and flavors of Arayat rice for yourself. Thank you for choosing PALE-NGKIHAN!</p>
      <button type="button" class="btn btn-success btn-rounded btn-lg">Shop Now</button>
    
    </div>
    
</div>
</div>
	<!-- footer section end-->

    <!-- Copyright Section-->
    
    <div class="copyright py-4 text-center text-white d-flex p-2">
      <div class="container"><small>Copyright &copy; Pale-ngkihan 2023</small></div>
  </div>
    <!-- REQUIRED SCRIPTS -->
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

          // Add event listener for the click on the notification item
    document.addEventListener('DOMContentLoaded', function () {
        const notificationItems = document.querySelectorAll('.notification-item');

        notificationItems.forEach(function (item) {
            item.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the default link behavior

                // Get the notification ID from the data attribute
                const notificationId = item.getAttribute('data-notification-id');

                // Make an AJAX request to update the is_seen status
                // Replace 'your_update_endpoint.php' with the actual URL of the PHP script that updates the database
                fetch('your_update_endpoint.php?notification_id=' + encodeURIComponent(notificationId), {
                    method: 'POST', // Or 'GET', depending on your server-side implementation
                })
                    .then(function (response) {
                        if (response.ok) {
                            // Update the UI or perform any other actions to indicate that the notification has been seen
                            console.log('Notification with ID ' + notificationId + ' marked as seen.');
                        } else {
                            console.error('Failed to mark notification as seen.');
                        }
                    })
                    .catch(function (error) {
                        console.error('An error occurred:', error);
                    });
            });
        });
    });
    </script>
</body>
</html>

