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
    <title>Buyer | Support Page</title>
</head>

  <!-- summernote -->
  <link rel="stylesheet" href="../Assets/plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../Assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../Assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="../Assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="../Assets/plugins/fontawesome-free/css/all.min.css">
<script src="https://kit.fontawesome.com/0ad1512e05.js" crossorigin="anonymous"></script>
<!-- Theme style -->
<link rel="stylesheet" href="../Assets/dist/css/adminlte.min.css">
<!--css-->
<link href="../buyer/support.css" rel="stylesheet">
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
                    <a class="nav-link mx-3" href="../buyer/marketplace.php">Marketplace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  mx-3" href="../buyer/wholesale.php">Wholesale</a>
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
                    <a class="nav-link mx-2" href="../buyer/chat_users.php"><i class="fa-solid fa-message"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        echo '<img src="../' . $profilePicture . '" alt="Profile Picture" class="avatar-image img-fluid">';
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right custom-dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
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

<!--Add Ticket Modal-->
<form action="../php/process_ticket.php" method="POST">
        <div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="error-text alert alert-danger text-center fs-5" style="display:none;">Error</div>
                            <div class="form-group">
                                <label class="form-label" for="category">Category</label>
                                <select name="category" class="form-control" required>
                                <option value="" selected disabled>Select Category</option>
                                    <option value="Bug Report">Bug Report</option>
                                    <option value="General Inquiry">General Inquiry</option>
                                    <option value="Feature Request">Feature Request</option>
                                    <option value="Other">Others</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="urgency">Urgency Level</label>
                                <select name="urgency" class="form-control" required>
                                    <option value="" selected disabled>Select Urgency Level</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="subject">Subject</label>
                                <input name="subject" type="text" class="form-control" placeholder="Enter Subject" required>
                            </div>
                            <div class="form-group">
    <label for="ticketMessage">Message</label>
    <textarea class="form-control" id="ticketMessage" name="ticketMessage" rows="5" required></textarea>
</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button name="submit" type="submit" class="buttons btn btn-success">Save Ticket</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

<!-- View Modal -->
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
                        <label for="sellerReply">Seller's Reply:</label>
                        <textarea class="form-control" id="sellerReply" name="sellerReply" rows="5"></textarea>
                    </div>
                </form>

      </div>
      <div class="modal-footer justify-content-end">
               
                <button type="button" id="submitReply" class="btn btn-success">Submit Reply</button>
                <button type="button" id="closeModalBtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
    </div>
  </div>
</div>


<div class="container flex-grow-1">

<div class="content py-3">
<div class="card card-outline card-success rounded-0 shadow">
                        <div class="card-header">
                            <h2 class="card-title">Ticket List</h2>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#addTicketModal" class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i>&nbsp;Add Ticket</a>
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
                                  $sql = "SELECT * FROM ticket WHERE user_uniqueid = '{$unique_id}'"; // Replace with your table name
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
    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon"  data-bs-toggle="dropdown" aria-expanded="false">
      Action
      <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu dropdown-menu-right" role="menu">
    <a href="#" class="dropdown-item view-ticket-btn" data-ticket-id="<?= $ticket['ticket_id']; ?>" data-bs-toggle="modal" data-bs-target="#viewTicketModal">
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




</div>
   
    </div>









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



 <!-- REQUIRED SCRIPTS -->
 <!-- Summernote -->
<script src="../Assets/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- AdminLTE App -->
    <script src="../Assets/dist/js/adminlte.min.js"></script>
    <!--Animation java-->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- SweetAlert2 -->
    <script src="../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="../Assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../Assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../Assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="../Assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="../Assets/plugins/jszip/jszip.min.js"></script>
        <script src="../Assets/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="../Assets/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="../Assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script>


 // Handle the click event of the "Submit Reply" button
 $('#submitReply').click(function() {
    var ticketId = $('#viewTicketId').text();
    var sellerReply = $('#sellerReply').val();
    
    if (sellerReply.trim() === '') {
        showerror('Error', 'Please enter a reply.', 'error');
        return;
    }

    $.ajax({
        url: '../php/process_seller_reply.php', // Replace with the actual path to process_seller_reply.php
        method: 'POST',
        data: { ticketId: ticketId, sellerReply: sellerReply },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Show success alert and update conversation
                showAlert('Success', 'Reply submitted successfully.', 'success');
                // Update conversation history here if needed
            } else if (response.status === 'error') {
                // Show error alert
                showerror('Error', response.message, 'error');
            }
        },
        error: function() {
            // AJAX request failed, show a general error alert
            showerror('Error', 'An error occurred. Please try again later.', 'error');
        }
    });
});


        AOS.init();

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

     // Handle the click event of the "View" button
     $('.view-ticket-btn').click(function() {
        var ticketId = $(this).data('ticket-id'); // Get the ticket ID from the data attribute
        
        // Call a function to fetch ticket data by ID and populate the modal
        getTicketDataById(ticketId);
    });
    
    
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
                                conversationHtml += '<p class="no-history-message text-muted text-center py-3">No conversation history found.</p>';

                            } else {
                                // Loop through conversation messages and build HTML
                                for (var i = 0; i < conversation.length; i++) {
                                    var sender = conversation[i].user_uniqueid ? 'You' : 'Admin';
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





    $('#ticketMessage').summernote({
            placeholder: 'Enter your message here...',
            height: 200, // Set the desired height
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link']],
            ]
        });
    // Initialize the summernote editor
    $('#sellerReply').summernote({
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


        // Handle form submission
        $('form').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        
        $.ajax({
            url: $(this).attr('action'), // Use the form's action attribute
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.status === 'success') {
                    // Ticket added successfully, show success alert
                    showAlert('Success', 'Ticket added successfully.', 'success');
                } else if (response.status === 'error') {
                    // Error adding ticket, show error alert
                    showerror('Error', response.message, 'error');
                }
            },
            error: function() {
                // AJAX request failed, show a general error alert
                showerror('Error', 'An error occurred. Please try again later.', 'error');
            }
        });
    });


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


    </script>
</body>
</html>