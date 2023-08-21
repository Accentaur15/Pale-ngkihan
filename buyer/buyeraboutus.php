<?php

session_start();
include_once('../php/config.php');
include_once('../php/cart_functions.php'); 
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
  header("Location: ./buyerlogin.php");
}

$qry = mysqli_query($conn, "SELECT * FROM buyer_accounts WHERE unique_id = '{$unique_id}'");

if (mysqli_num_rows($qry) > 0) {
  $row = mysqli_fetch_assoc($qry);
  if ($row) {
    $fname = $row['first_name'];
    $lname = $row['last_name'];
    $profilePicture = $row['profile_picture'];
    $id = $row['id'];
  }
}
$cartItemCount = getCartItemCount($conn, $id);
include_once('../php/notifications.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer | About Us</title>
</head>


 <!--css-->
 <link href="../buyer/buyeraboutus.css" rel="stylesheet">
 <!--fontawesome-->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
 <!--bootstrap-->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
 <!--logo-->
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
                    <a class="nav-link active mx-3" href="buyeraboutus.php">About Us</a>
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
<body>

    <header class="masthead text-white ">
        <div class="container d-flex  align-items-start flex-column" style="transform: translateY(250%);">
          <div data-aos="fade-up" data-aos-easing="linear" data-aos-easing="linear" data-aos-duration="750">
            <!-- Masthead Heading-->
            <h1 class="masthead-heading mb-0" style="font-weight: bolder;">About Us</h1>
            <!-- Masthead Subheading-->
        </div>
        </div>
    </header>

    <div class="firstbox">
        <h1 class="display-3" style=" font-weight:400;">We revolutionize the way Arayat rice traders operate</h1>
        <p class="fboxtxt">Connecting them through an innovative online market system known as PALE-NGKIHAN. Our platform is designed to empower Arayat rice traders by providing them with a seamless digital ecosystem that enhances their efficiency, expands their reach, and boosts their profitability. It fuels the passion and drive of rice traders, propelling them towards a brighter future. With our platform, every morning brings new opportunities, as traders can access a virtual marketplace that transcends geographical boundaries.</p>
    </div>

    <div class="accordion accordion-flush align-items-center" id="accordionFlushExample" style="padding: 2em;">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne" style="font-weight: bold;">
                Read our full mission statement
            </button>
          </h2>
    <div id="flush-collapseOne" class="atxt accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">  At PALE-NGKIHAN, our mission is to revolutionize the Arayat rice trade through an advanced online market system. We empower rice traders, enhance their efficiency, and transform their success in the global marketplace. Our platform serves as the heartbeat of Arayat's rice trade, connecting traders, amplifying their reach, and propelling their businesses towards a brighter future.
        <br><br>
        We are dedicated to providing a seamless digital ecosystem that empowers rice traders to seize new opportunities and maximize profitability. By leveraging cutting-edge technology and real-time connectivity, we enable traders to navigate the market landscape with agility, efficiency, and confidence. Our user-friendly interface seamlessly connects traders to a global network of buyers, offering enhanced visibility and access to a wider customer base.
        <br><br>
        Through PALE-NGKIHAN, we deliver real-time insights, equipping traders with valuable market data, pricing trends, and consumer preferences. By arming traders with this information, we enable them to make data-driven decisions, optimize supply chain management, and stay ahead in an ever-evolving market. We integrate reliable logistics services, streamlining transportation and ensuring timely deliveries to customers worldwide. We prioritize the security of transactions, implementing robust encryption and authentication protocols to safeguard sensitive information and financial exchanges.
        <br><br>
        Our mission is to transform the Arayat rice trade by harnessing the power of digital innovation. We envision a future where Arayat rice traders flourish in the global marketplace, propelled by the speed of now and driven by the limitless potential of our online market system. Together with our dedicated community of rice traders, we are redefining movement, empowering businesses, and shaping a better tomorrow for the Arayat rice trade.
        </div>
    </div>
  </div>

  <!--about section start -->
  <div class="services_section layout_padding">
    <div class="container">
       <div class="row">
          <div class="col-md-6">
             <div class="image_2" ><img clas="img-fluid" src="../Assets/images/whoweare.png"></div>
          </div>
          <div class="col-md-6">
             <div class="box_main">
                <h1 class="technology_text">Who We Are</h1>
                <p class="dummy_text">We are a passionate team dedicated to revolutionizing the Arayat rice trade. Through cutting-edge technology, we connect rice traders, amplify their reach, and propel their businesses towards success in the global marketplace. With a focus on collaboration, integrity, and customer-centricity, we provide real-time insights, secure transactions, and efficient logistics. Join us on this transformative journey as we shape the future of the rice trade, embracing digital innovation, and empowering businesses to thrive worldwide.</p>
             </div>
          </div>
       </div>
       <div class="layout_padding"></div>
       <div class="row">
          <div class="col-md-6">
             <div class="image_2"><img clas="img-fluid" src="../Assets/images/whatwedo.png"></div>
          </div>
          <div class="col-md-6">
             <div class="box_main">
                <h1 class="technology_text">What We do</h1>
                <p class="dummy_text">We provide an advanced online market system that transforms the Arayat rice trade. Through our platform, we empower rice traders by enhancing their efficiency, expanding their reach, and increasing their profitability in the global marketplace. We offer a seamless digital ecosystem that enables traders to showcase their products, negotiate deals, and conduct secure transactions. Our real-time insights, reliable logistics, and cutting-edge technology redefine movement within the industry, driving growth and success for Arayat rice traders. Join us as we revolutionize the way the world moves for the better in the Arayat rice trade.</p>
             </div>
            
          </div>
       </div>
    </div>
 </div>
 
 <!--services section end -->

  <!--works section start -->
  <div class="works_section layout_padding">
    <div class="container">
       <h1 class="work_taital">Key Impacts</h1>
       <div class="works_section_2 layout_padding">
          <div class="row">
             <div class="col-sm-4">
                <div class="info_img "><img src="../Assets/images/magnet.png" class="img-fluid" style="background-size: cover;"></div>
                <h3 class="fully_text">Expanded Market Access</h3>
                <p class="lorem_text">PALE-NGKIHAN provides Arayat rice traders with expanded market access, enabling them to reach a global customer base and explore new growth opportunities.</p>
             </div>
             <div class="col-sm-4">
                <div class="info_img "><img src="../Assets/images/supply-chain-management-vector.png" class="img-fluid" style="background-size: cover;"></div>
                <h3 class="fully_text">Streamlined Operations</h3>
                <p class="lorem_text">PALE-NGKIHAN streamlines operations for rice traders, optimizing supply chain management, reducing inefficiencies, and improving overall productivity.</p>
             </div>
             <div class="col-sm-4">
                <div class="info_img "><img src="../Assets/images/istockphoto-1146425090-612x612.png" class="img-fluid" style="background-size: cover;"></div>
                <h3 class="fully_text">Fair and Transparent Trading</h3>
                <p class="lorem_text">PALE-NGKIHAN promotes fair and transparent trading practices, empowering rice traders to establish direct relationships with buyers and ensuring a more equitable distribution of profits.</p>
             </div>
          </div>
       </div>
    </div>
 </div>
 <!--works section end -->
<!--footer start-->
 <div class="footer_section d-flex p-2"></div>
   
</div>
</div>
  <!-- footer section end-->


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


     <!--Animation java-->
     <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
     <script>
       AOS.init();
     </script>
     <!--Bootstrap java-->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
 



   </body>



</html>