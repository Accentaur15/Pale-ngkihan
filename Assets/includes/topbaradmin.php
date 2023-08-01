<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background: rgb(229, 235, 232);">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="sellermain.php" class="nav-link keychainify-checked">Pale-ngkihan - Admin Side</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto mr-2">
    <li class="nav-item">

        <button type="button" class="btn btn-rounded badge dropdown-toggle dropdown-icon my-auto" data-toggle="dropdown" aria-expanded="false">
          <span><img src="<?php echo "../".$profilePicture?>" class="avatar-image img-fluid" alt="User Image" style="width: 3em; height: 3em; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
</span>
          <span class="ml-1" style="font-size: 14px;"><?php echo $fname ." " . $lname ; ?></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right mr-2" role="menu">
          <a class="dropdown-item keychainify-checked" href="#"><span class="fa fa-user"></span> My Account</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item keychainify-checked" href="../php/logoutadmin.php"><span class="fas fa-sign-out-alt"></span> Logout</a>
        </div>

    </li>
  </ul>
</nav>

  <!-- /.navbar -->