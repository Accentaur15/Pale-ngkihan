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

?>
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seller | Harvest Schedule List</title>

  <!--title icon-->
  <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png"/>
  <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png"/>
  <!-- summernote -->
  <link rel="stylesheet" href="../Assets/plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../Assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!--title icon-->
  <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png"/>
  <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png"/>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../Assets/plugins/fontawesome-free/css/all.min.css">
  <script src="https://kit.fontawesome.com/0ad1512e05.js" crossorigin="anonymous"></script>
  <!-- Theme style -->
  <link rel="stylesheet" href="../Assets/dist/css/adminlte.min.css">
  <!-- css style -->
  <link rel="stylesheet" href="../Assets/avatar.css">
  <!-- Image JS -->
  <script src="../js/imagehandling.js"></script>
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


  <!-- Add Harvest Schedule Modal -->
<div class="modal fade" id="addHarvestSchedule" tabindex="-1" role="dialog" aria-labelledby="addHarvestScheduleLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addHarvestScheduleLabel">Add Harvest Schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Add Harvest Schedule Form -->
        <form id="addHarvestScheduleForm" action="../php/add_harvest_schedule.php" method="POST">
          <input type="hidden" name="sellerid" value="<?= $sellerid; ?>">
          <div class="form-group">
            <label for="schedule_date">Date</label>
            <input type="date" class="form-control" id="schedule_date" name="schedule_date" required>
          </div>
          <div class="form-group">
            <label for="location">Location</label>
            <div class="input-group">
              <input type="text" class="form-control" id="location" name="location" required>
              <div class="input-group-append">
                <button type="button" class="btn btn-success btn-sm" id="getLocationBtn"><i class="fa-solid fa-location-dot"></i> Get My Location</button>
              </div>
            </div>
            <small class="form-text text-muted">Click the "Get My Location" button to fetch GPS coordinates.</small>
          </div>
          <div class="form-group">
  <label for="quantity_available">Estimated Quantity Available</label>
  <div class="input-group">
    <input type="number" class="form-control" id="quantity_available" name="quantity_available" min="1" required>
    <div class="input-group-append">
      <span class="input-group-text">sacks</span>
    </div>
  </div>
</div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
              <option value="upcoming">Upcoming</option>
              <option value="ongoing">Ongoing</option>
            </select>
          </div>
          <div class="form-group">
            <label for="bidding_status">Bidding Status</label>
            <select class="form-control" id="bidding_status" name="bidding_status" required>
              <option value="1">Open for Bidding</option>
              <option value="0">Closed for Bidding</option>
            </select>
          </div>
          <div class="form-group">
            <label for="starting_bid">Starting Bid Price (Optional)</label>
            <input type="number" class="form-control" id="starting_bid" name="starting_bid" min="0" step="0.01">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- Use type="submit" for the Save Changes button -->
        <button type="submit" form="addHarvestScheduleForm" class="btn btn-success">Save Changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Edit Harvest Schedule Modal -->
<div class="modal fade" id="editHarvestSchedule" tabindex="-1" role="dialog" aria-labelledby="editHarvestScheduleLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editHarvestScheduleLabel">Edit Harvest Schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Edit Harvest Schedule Form -->
        <form id="editHarvestScheduleForm" action="../php/edit_harvest_schedule.php" method="POST">
          <!-- Add necessary input fields for updating the schedule details -->
          <input type="hidden" name="schedule_id" id="edit_schedule_id">
          <div class="form-group">
            <label for="edit_schedule_date">Date</label>
            <input type="date" class="form-control" id="edit_schedule_date" name="schedule_date" required>
          </div>
          <div class="form-group">
            <label for="edit_location">Location</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_location" name="location" required>
              <div class="input-group-append">
                <button type="button" class="btn btn-success btn-sm" id="getLocationBtn"><i class="fa-solid fa-location-dot"></i> Get My Location</button>
              </div>
            </div>
            <small class="form-text text-muted">Click the "Get My Location" button to fetch GPS coordinates.</small>
          </div>
          <div class="form-group">
            <label for="edit_quantity_available">Estimated Quantity Available</label>
            <div class="input-group">
              <input type="number" class="form-control" id="edit_quantity_available" name="quantity_available" min="1" required>
              <div class="input-group-append">
                <span class="input-group-text">sacks</span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="edit_status">Status</label>
            <select class="form-control" id="edit_status" name="status" required>
              <option value="upcoming">Upcoming</option>
              <option value="ongoing">Ongoing</option>
              <option value="completed">Completed</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_bidding_status">Bidding Status</label>
            <select class="form-control" id="edit_bidding_status" name="bidding_status" required>
              <option value="1">Open for Bidding</option>
              <option value="0">Closed for Bidding</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_starting_bid">Starting Bid Price (Optional)</label>
            <input type="number" class="form-control" id="edit_starting_bid" name="starting_bid" min="0" step="0.01">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- Use type="submit" for the Save Changes button -->
        <button type="submit" form="editHarvestScheduleForm" class="btn btn-success">Save Changes</button>
      </div>
    </div>
  </div>
</div>





      <div class="card">
            <div class="card-header">
              <h4 class="card-title">Upcoming Harvest Schedules</h4>
              <a href="#" data-toggle="modal" data-target="#addHarvestSchedule" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i>&nbsp;Add Harvest Schedule</a>
            </div>
            <div class="card-body">
              <table id="harvestSchedulesTable" class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Quantity Available</th>
                    <th>Status</th>
                    <th>Bidding Status</th>
                    <th>Starting Bid</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $harvestQuery = mysqli_query($conn, "SELECT * FROM harvest_schedule WHERE seller_id = '{$sellerid}'");
                  if (mysqli_num_rows($harvestQuery) > 0) {
                    $count = 1;
                    while ($scheduleRow = mysqli_fetch_assoc($harvestQuery)) {
                      $scheduleId = $scheduleRow['id'];
                      $date = $scheduleRow['date_scheduled'];
                      $location = $scheduleRow['location'];
                      $quantity = $scheduleRow['quantity_available'];
                      $status = $scheduleRow['status'];
                      $biddingStatus = $scheduleRow['bidding_status'] == 1 ? 'Open for Bidding' : 'Closed for Bidding';
                      $startingbid = $scheduleRow['starting_bid'];
                      ?>
                      <tr>
                        <td><?= $count++; ?></td>
                        <td><?= $date; ?></td>
                        <td class="text-center" >  <?php
  // Check if the location contains GPS coordinates
  if (preg_match('/Latitude: ([+-]?\d+\.\d+), Longitude: ([+-]?\d+\.\d+)/', $location, $matches)) {
    $latitude = $matches[1];
    $longitude = $matches[2];
    // Create the Google Maps link
    $googleMapsLink = "https://www.google.com/maps?q={$latitude},{$longitude}";
    // Display the location as a clickable link
    echo "<a href=\"{$googleMapsLink}\" target=\"_blank\">{$location}</a>";
  } else {
    // For non-GPS coordinates, display the location as normal text
    echo $location;
  }
  ?></td>
                        <td><?= $quantity; ?> Sacks</td>
                        <td><?= $status == 'upcoming' ? 'Active' : 'Inactive'; ?></td>
                        <td><?= $biddingStatus; ?></td>
                        <td><?= $startingbid; ?></td>
                        <td class="text-center">
  <div class="btn-group">
    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
      Action
      <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu dropdown-menu-right" role="menu">
    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#editHarvestSchedule" data-schedule-id="<?= $scheduleId; ?>">
      <i class="fas fa-edit text-primary"></i> Edit
    </a>
      <div class="dropdown-divider"></div>
      <form action="../php/delete_harvest_schedule.php" method="POST">
        <input type="hidden" name="schedule_id" value="<?= $scheduleId; ?>">
        <button type="submit" class="dropdown-item delete_data" onclick="return confirm('Are you sure you want to delete this schedule?');">
          <i class="fas fa-trash text-danger"></i> Delete
        </button>
      </form>
    </div>
  </div>
</td>

                      </tr>
                      <?php
                    }
                  } else {
                    echo '<tr><td colspan="7" class="text-center">No Harvest Schedules Found.</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <script>
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

// Define the showerror function
function showerror(title, text, icon) {
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
  });
}

      document.getElementById("getLocationBtn").addEventListener("click", function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        const locationInput = document.getElementById("location");
        locationInput.value = `Latitude: ${latitude}, Longitude: ${longitude}`;
      }, function(error) {
        console.error("Error getting location:", error.message);
      });
    } else {
      console.error("Geolocation is not supported by this browser.");
    }
  });

  document.getElementById("addHarvestScheduleForm").addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch(event.target.action, {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // If the insertion is successful, show a success message
        showAlert('Success!', 'Harvest schedule has been added successfully.', 'success');
        // Optionally, you can refresh the page or update the table with the new data
        // window.location.reload();
      } else {
        // If there's an error, show an error message
        showError('Error!', data.message, 'error');
      }
    })
    .catch(error => {
      // If there's an error with the fetch request, show an error message
      showError('Error!', 'An error occurred while processing the request.', 'error');
    });
  });

    // JavaScript to handle the "Edit" button click event
    document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".dropdown-item[data-target='#editHarvestSchedule']");
    const editScheduleIdInput = document.getElementById("edit_schedule_id");

    editButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        const scheduleId = this.dataset.scheduleId;
        editScheduleIdInput.value = scheduleId;
      });
    });
  });

  document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit_button");
    const editScheduleIdInput = document.getElementById("edit_schedule_id");
    const editDateInput = document.getElementById("edit_schedule_date");
    const editLocationInput = document.getElementById("edit_location");
    const editQuantityInput = document.getElementById("edit_quantity_available");
    const editStatusInput = document.getElementById("edit_status");
    const editBiddingStatusInput = document.getElementById("edit_bidding_status");
    const editStartingBidInput = document.getElementById("edit_starting_bid");

    editButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        const scheduleId = this.dataset.scheduleId;

        // Fetch the data for the corresponding schedule using AJAX
        fetch(`../php/get_harvest_schedule.php?schedule_id=${scheduleId}`)
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              const schedule = data.schedule;
              editScheduleIdInput.value = schedule.id;
              editDateInput.value = schedule.date_scheduled;
              editLocationInput.value = schedule.location;
              editQuantityInput.value = schedule.quantity_available;
              editStatusInput.value = schedule.status;
              editBiddingStatusInput.value = schedule.bidding_status;
              editStartingBidInput.value = schedule.starting_bid;
            } else {
              // Show an error message if data retrieval fails
              console.error(data.message);
            }
          })
          .catch((error) => {
            console.error("An error occurred while fetching data:", error);
          });
      });
    });
  });

  
    </script>


<?php
    include('../Assets/includes/footer.php');
?>