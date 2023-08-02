 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4 ">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link bg-success">
      <img src="../Assets/logo/logo P whitebg.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Pale-ngkihan</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-4">
        <ul id="sidebar-menu"class="nav nav-pills nav-sidebar flex-column nav-flat nav-compact" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="../seller/sellermain.php" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../seller/productlist.php" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>
                Product List
              </p>
            </a>
          </li>
          <li class="nav-item">
<a href="#" class="nav-link keychainify-checked">
<i class="nav-icon fa-solid fa-calendar-days"></i>
<p>
Harvest Schedule
<i class="right fas fa-angle-left"></i>
</p>
</a>
<ul class="nav nav-treeview" style="display: block;">
<li class="nav-item">
<a href="../seller/harvest_schedule_listing.php" class="nav-link keychainify-checked">
<i class="nav-icon fa-brands fa-readme"></i>
<p>Listings</p>
</a>
</li>
<li class="nav-item">
<a href="#" class="nav-link keychainify-checked">
<i class="nav-icon fa-solid fa-money-bill"></i>
<p>Bid Offers</p>
</a>
</li>
</ul>
</li>
          <li class="nav-item">
            <a href="../seller/orderlist.php" class="nav-link">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>
                Order List
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../seller/categorylistseller.php" class="nav-link">
            <i class="nav-icon fas fa-filter"></i>
              <p>
                Category List
              </p>
            </a>
          </li>
          <li class="nav-header">Reports</li>
          <li class="nav-item">
            <a href="../seller/monthlyorderreport.php" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Monthly Order Report
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-star"></i>
              <p>
                Ratings & Reviews
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-comments"></i>
              <p>
               Customer Messages
              </p>
            </a>
          </li>
          <li class="nav-header">Maintenance</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-headset"></i>
              <p>
               Admin Support
              </p>
            </a>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <script>
  // Get the current page URL
  var currentURL = window.location.href;
  
  // Get the sidebar menu items
  var sidebarItems = document.querySelectorAll('#sidebar-menu a.nav-link');

  // Loop through the sidebar menu items
  for (var i = 0; i < sidebarItems.length; i++) {
    var item = sidebarItems[i];

    // Check if the href attribute matches the current URL
    if (item.href === currentURL) {
      // Add the "bg-success" class to set the background color
      item.classList.add('bg-success');

      // If the menu item has a parent <li> element, add the "menu-open" class to expand the parent menu
      var listItem = item.parentNode;
      if (listItem.tagName === 'LI') {
        listItem.classList.add('menu-open');
      }
    }
  }
</script>
