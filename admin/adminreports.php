<?php



session_start();
include_once('../php/config.php');
$unique_id = $_SESSION['unique_id'];

if (empty($unique_id)) {
  header("Location: ../admin/admin.php");
}

$qry = mysqli_query($conn, "SELECT * FROM admin_accounts WHERE id = '{$unique_id}'");

if (mysqli_num_rows($qry) > 0) {
  $row = mysqli_fetch_assoc($qry);
  if ($row) {
    $fname = $row['first_name'];
    $lname = $row['last_name'];
    $username = $row['username'];
    $profilePicture = $row['profile_picture'];
    $role = $row['role'];
  }
}



?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | User Reports</title>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="../Assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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
  <!-- Image JS -->
  <script src="../js/imagehandling.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php 
include('../Assets/includes/topbaradmin.php');
include('../Assets/includes/sidebaradmin.php');
?>

<!-- View Ticket Modal -->
<div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">View Ticket Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Ticket ID:</th>
                                <td><span id="viewTicketId"></span></td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td><span id="viewTicketCategory"></span></td>
                            </tr>
                            <tr>
                                <th>Urgency Level:</th>
                                <td><span id="viewTicketUrgency"></span></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><span id="viewTicketStatus"></span></td>
                            </tr>
                            <tr>
                                <th>Subject:</th>
                                <td><span id="viewTicketSubject"></span></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td><p id="viewTicketDescription"></p></td>
                            </tr>
                            <tr>
                                <th>Created At:</th>
                                <td><span id="viewTicketCreatedAt"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <!-- Display User's Reply -->
                <div class="mt-4">
    <h6 class="font-weight-bold">Conversation History:</h6>
    <div id="conversationContainer"></div>
</div>
                <!-- Admin Reply Form and Status Update -->
                <form id="replyForm">
                    <div class="form-group">
                        <label for="adminReply">Admin's Reply:</label>
                        <textarea class="form-control" id="adminReply" name="adminReply" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="statusUpdate">Update Status:</label>
                        <select class="form-control" id="statusUpdate" name="statusUpdate">

                            <option value="open">Open</option>
                            <option value="in progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" id="submitReplyAndStatusBtn" class="btn btn-success">Submit Reply and Status</button>
                <button type="button" id="closeModalBtn" class="btn btn-secondary">Close</button>
            </div>
        </div>
    </div>
</div>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content mt-4">
      <div class="container-fluid">

      <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Ticket List</h2>
                        </div>
                        <div class="card-body">
                            <table id="ticketTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Category</th>
                                      
                                        <th>Status</th>
                                        <th>Subject</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $sql = "SELECT * FROM ticket"; // Replace with your table name
                                  $result = $conn->query($sql);
                                  
                                  $tickets = array(); // Array to store ticket data
                                  
                                  if ($result->num_rows > 0) {
                                      while ($row = $result->fetch_assoc()) {
                                          $tickets[] = $row;
                                      }
                                  }
                                  
                                  foreach ($tickets as $ticket) {
                                  
                                      $ticketId = $ticket['ticket_id'];
                                      $category = $ticket['category'];
                                      $urgencyLevel = $ticket['urgency_level'];
                                      $status = $ticket['status'];
                                      $subject = $ticket['subject'];
                                      $createdAt = $ticket['created_at'];
                                  
                                      // Process the data as needed
                                      // For example, format the date
                                      $formattedCreatedAt = date("F j, Y", strtotime($createdAt));
                                  
                                      ?>
                                      <tr>
                                          <td><?=  $ticketId ?></td>
                                          <td><?= $category ?></td>
                                         
                                          <td class="text-center">  <?php
  // Check the status value and set the appropriate badge class
  $status = strtolower($status);
  switch ($status) {
    case 'open':
      echo '<span class="badge badge-primary px-3 rounded-pill">Open</span>';
      break;
    case 'in progress':
      echo '<span class="badge badge-success px-3 rounded-pill">In Progress</span>';
      break;
    case 'completed':
      echo '<span class="badge badge-info px-3 rounded-pill">Completed</span>';
      break;
    default:
      echo '<span class="badge badge-secondary px-3 rounded-pill">Unknown</span>';
      break;
  }
  ?></td>
                                          <td><?= $subject ?></td>
                                          <td><?= $formattedCreatedAt ?></td>
                                          <td class="text-center">
  <div class="btn-group">
    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
      Action
      <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu dropdown-menu-right" role="menu">
    <a href="#" class="dropdown-item view-ticket-btn" data-ticket-id="<?= $ticket['ticket_id']; ?>">
                        <span class="fa-solid fa-eye text-dark"></span> View
                    </a>
      <div class="dropdown-divider"></div>
      <button class="dropdown-item delete-data-btn" data-schedule-id="<?= $ticketId; ?>">
                <i class="fas fa-trash text-danger"></i> Delete
            </button>
    </div>
  </div>
</td>
                                      </tr>
                                      <?php
                                  
                                      
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
<style>
            #conversationContainer .no-history-message {
            text-align: center;
            color: gray;
            font-style: italic;
            margin-top: 10px;
        }
</style>

<?php
    include('../Assets/includes/footer.php');
?>

<script>

 // Handle the click event of the "Delete" button
 $('.delete-data-btn').click(function() {
    var ticketId = $(this).data('schedule-id'); // Get the ticket ID from the data attribute
    
    // Show a confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send an AJAX request to delete the ticket
            $.ajax({
                url: '../php/delete_ticket.php', // Replace with the actual path to your delete script
                type: 'POST',
                data: {
                    ticketId: ticketId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Show a success message
                        showAlert('Success', response.message, 'success');
                        // Refresh the page after deletion
                       
                    } else {
                        // Show an error message
                        showerror('Error', response.message, 'error');
                    }
                },
                error: function() {
                    // Show an error message if AJAX request fails
                    showerror('Error', 'An error occurred while deleting the ticket.', 'error');
                }
            });
        }
    });
});

          $("#ticketTable").DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
  });

  $(document).ready(function() {

    // Function to submit admin's reply and update status
    $('#submitReplyAndStatusBtn').click(function() {
        var ticketId = $('#viewTicketId').text();
        var adminReply = $('#adminReply').val();
        var statusUpdate = $('#statusUpdate').val();
        
        $.ajax({
            url: '../php/admin_reply_update.php', // Replace with the actual path to your PHP script
            type: 'POST',
            data: {
                ticketId: ticketId,
                adminReply: adminReply,
                statusUpdate: statusUpdate
            },
            dataType: 'json',
            success: function(response) {
                console.log(response); 
                if (response.status == 'success') {
                    // Success message
                    showAlert('Success', 'Admin reply and status update saved successfully.', 'success');
                    // Refresh the modal content
                    //getTicketDataById(ticketId);
                } else {
                    // Error message
                    showerror('Error', 'Failed to save admin reply and status update.', 'error');
                }
            },
            error: function() {
                // Error message
                showerror('Error', 'An error occurred. Please try again later.', 'error');
            }
        });
    });

    $('#adminReply').summernote({
            placeholder: 'Enter your reply here...',
            height: 150, // Set the desired height
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
            ]
        });

    $('#closeModalBtn').click(function() {
    $('#viewTicketModal').modal('hide');
  });
  
  // Handle the click event of the "View" button
  $('.view-ticket-btn').click(function() {
      var ticketId = $(this).data('ticket-id'); // Get the ticket ID from the data attribute
      
      // Call a function to fetch ticket data by ID and populate the modal
      getTicketDataById(ticketId);
  });
  
  
  // Function to get ticket data by ID and populate the modal
  // Function to get ticket data by ID and populate the modal
function getTicketDataById(ticketId) {
    $.ajax({
        url: '../php/get_ticket_data.php', // Replace with the actual path to fetch ticket details
        method: 'POST',
        data: { ticketId: ticketId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Populate the modal with the retrieved data
                $('#viewTicketId').text(response.data.ticket_id);
                $('#viewTicketCategory').text(response.data.category);
                $('#viewTicketUrgency').text(response.data.urgency_level);
                $('#viewTicketStatus').text(response.data.status);
                $('#viewTicketSubject').text(response.data.subject);
                $('#viewTicketDescription').html(response.data.description);
                $('#viewTicketCreatedAt').text(response.data.created_at);
                $('#statusUpdate').val(response.data.status);

                // Fetch and display conversation history
                $.ajax({
                    url: '../php/get_conversation.php', // Replace with the actual path to fetch conversation history
                    method: 'POST',
                    data: { ticketId: ticketId },
                    dataType: 'json',
                    success: function(conversationResponse) {
                        if (conversationResponse.status === 'success') {
                            var conversationHtml = ''; // Store HTML for conversation history
                            var conversation = conversationResponse.data;

                            // Initialize variables to keep track of the current sender
                            var currentSender = null;
                            conversationHtml = '<div class="direct-chat-messages">';

                            if (conversation.length === 0) {
                                conversationHtml += '<p class="no-history-message">No conversation history found.</p>';
                            } else {
                                // Loop through conversation messages and build HTML
                                for (var i = 0; i < conversation.length; i++) {
                                    var sender = conversation[i].user_uniqueid ? 'User' : 'Admin';
                                    var message = conversation[i].message;

                                    // Check if the sender has changed
                                    if (currentSender !== sender) {
                                        // Close the previous conversation container if it exists
                                        if (conversationHtml !== '<div class="direct-chat-messages">') {
                                            conversationHtml += '</div></div></div>';
                                        }

                                        // Start a new conversation container
                                        conversationHtml += '<div class="direct-chat-msg">' +
                                            '<div class="direct-chat-infos clearfix">' +
                                            '<span class="direct-chat-name float-left">' + sender + '</span>' +
                                            '</div>' +
                                            '<div class="direct-chat-text">' +
                                            '<div class="direct-chat-msgs">' +
                                            '<div class="direct-chat-text">' + message + '</div>';

                                        // Update the current sender
                                        currentSender = sender;
                                    } else {
                                        // Add the message to the current conversation container
                                        conversationHtml += '<div class="direct-chat-text">' + message + '</div>';
                                    }
                                }

                                // Close the last conversation container if it exists
                                if (conversationHtml !== '<div class="direct-chat-messages">') {
                                    conversationHtml += '</div></div></div>';
                                }
                            }

                            // Append the conversation containers to the conversation container
                            $('#conversationContainer').html(conversationHtml);
                        } else if (conversationResponse.status === 'error') {
                            // Display a message when no conversation history is found
                            $('#conversationContainer').html('<p>No conversation history found.</p>');
                        } else {
                            // Handle other cases as needed
                        }
                    },
                    error: function() {
                        // Handle AJAX error for conversation history fetch
                        console.error('An error occurred while fetching conversation history.');
                    }
                });

                // Open the modal
                $('#viewTicketModal').modal('show');
            } else {
                // Show an error message if data retrieval fails
                alert('Failed to retrieve ticket data.');
            }
        },
        error: function() {
            // Show an error message if AJAX request fails
            alert('An error occurred while fetching ticket data.');
        }
    });
}


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
</script>