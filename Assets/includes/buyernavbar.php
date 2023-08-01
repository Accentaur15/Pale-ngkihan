    <!--primary navbar-->
 
        
    <nav class="navbar navbar-light navbar-expand-sm" style=" background: rgb(229, 235, 232);">
            <div class="container-fluid">
              <ul class="navbar-nav ms-auto justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                        echo '<img src="../' . $profilePicture . '" alt="Profile Picture" class="avatar-image img-fluid">';
                        echo $fname . ' ' . $lname;
                    ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="buyermyaccount.php">My Account</a></li>
                    <li><a class="dropdown-item" href="../php/logout.php?logout_id=<?php echo $unique_id?>">Log out</a></li>
                </ul>
            </li>
              </ul>
            </div>
          </nav>
      


            


    <!--end of primary navbar-->
    <!--Navigation Bar-->
    <nav class="navbar navbar-expand-md bg-light ">
  <div class="container-fluid">
  <a class="navbar-brand" href="index.html">
                <img src="../Assets/logo/Artboard 1.png" class="logo">
              </a>

        
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#buton">
      <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="buton">
      <ul class="navbar-nav text-center">
        <li class="nav-item">
          <a class="nav-link active mx-3" aria-current="page" href="buyermain.php">Home</a>
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
          <a class="nav-link mx-3" href="#"><i class="fa-solid fa-cart-shopping"></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>

    <!--End of Navigation Bar-->