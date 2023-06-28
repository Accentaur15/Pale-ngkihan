<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background: rgb(229, 235, 232);">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="sellermain.php" class="nav-link keychainify-checked">Pale-ngkihan - Seller Side</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto mr-2">
    <li class="nav-item">

        <button type="button" class="btn btn-rounded badge dropdown-toggle dropdown-icon my-auto" data-toggle="dropdown" aria-expanded="false">
          <span><img src="../<?php echo $shoplogo; ?>" class="avatar-image img-fluid" alt="User Image"></span>
          <span class="ml-1" style="font-size: 14px;"><?php echo $shopname; ?></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right mr-2" role="menu">
          <a class="dropdown-item keychainify-checked" href="selleraccount.php"><span class="fa fa-user"></span> My Account</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item keychainify-checked" href="../php/logoutseller.php"><span class="fas fa-sign-out-alt"></span> Logout</a>
        </div>

    </li>
  </ul>
</nav>

  <!-- /.navbar -->