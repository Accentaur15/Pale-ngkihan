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
    <!-- css style -->
    <link rel="stylesheet" href="../admin/chat.css">
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content mt-4">
      <div class="container-fluid">

      

<!-- Card Container -->
<div class="card shadow rounded">
        <div class="card-body">

        <!-- Card Container -->
<div class="card shadow rounded">
    <div class="card-body">
        <div class="wrapper">
            <section class="chat-area">
                <header>
                    <?php 
                    // Get the unique_id from the URL parameter
                    $clicked_unique_id = mysqli_real_escape_string($conn, $_GET['unique_id']);

                    // Check the user type associated with the unique_id in the support_messages table
                    $sql = mysqli_query($conn, "SELECT user_type FROM support_messages WHERE unique_id = '{$clicked_unique_id}'");
                    if(mysqli_num_rows($sql) > 0){
                        $row = mysqli_fetch_assoc($sql);
                        $user_type = $row['user_type']; // Retrieve the user_type

                        // Now you can perform actions based on the user_type
                        if ($user_type === 'seller') {
                            // Logic for seller user type
                            $seller_query = mysqli_query($conn, "SELECT * FROM seller_accounts WHERE unique_id = '{$clicked_unique_id}'");
                            if(mysqli_num_rows($seller_query) > 0){
                                $seller_row = mysqli_fetch_assoc($seller_query);
                                // Retrieve and use seller-specific data
                                $shop_name = $seller_row['shop_name'];
                                $shop_logo = $seller_row['shop_logo'];
                                // ...
                            }
                        } elseif ($user_type === 'buyer') {
                            // Logic for buyer user type
                            $buyer_query = mysqli_query($conn, "SELECT * FROM buyer_accounts WHERE unique_id = '{$clicked_unique_id}'");
                            if(mysqli_num_rows($buyer_query) > 0){
                                $buyer_row = mysqli_fetch_assoc($buyer_query);
                                // Retrieve and use buyer-specific data
                                $first_name = $buyer_row['first_name'];
                                $last_name = $buyer_row['last_name'];
                                $profile_picture =$buyer_row['profile_picture'];
                                // ...
                            }
                        } 
                    }
                    ?>
                    <a href="adminreports.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                    <!-- Adjust the image and name based on the user type -->
                    <?php if ($user_type === 'seller') { ?>
                        <img src="../<?php echo $shop_logo; ?>" alt="">
                        <div class="details">
                            <span><?php echo $shop_name; ?></span>
                        </div>
                    <?php } elseif ($user_type === 'buyer') { ?>
                        <img src="../<?php echo $profile_picture; ?>" alt="">
                        <div class="details">
                            <span><?php echo $first_name . ' ' . $last_name; ?></span>
                        </div>
                    <?php } ?>
                </header>
                <div class="chat-box">
                    <!-- Display chat messages here -->
                </div>
                <form action="#" class="typing-area">
                    <input type="text" class="incoming_id" name="incoming_id" value="admin" hidden>
                    <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                    <button><i class="fab fa-telegram-plane"></i></button>
                </form>
            </section>
        </div>
    </div>
</div>
<!-- End of Card Container -->

            
           
           
        </div>
    </div>
    <!-- End of Card Container -->






        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script>
  const form = document.querySelector(".typing-area"),
incoming_id = form.querySelector(".incoming_id").value,
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box");

form.onsubmit = (e)=>{
    e.preventDefault();
}

inputField.focus();
inputField.onkeyup = ()=>{
    if(inputField.value != ""){
        sendBtn.classList.add("active");
    }else{
        sendBtn.classList.remove("active");
    }
}

sendBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/send_chatadmin.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              inputField.value = "";
              scrollToBottom();
          }
      }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}
chatBox.onmouseenter = ()=>{
    chatBox.classList.add("active");
}

chatBox.onmouseleave = ()=>{
    chatBox.classList.remove("active");
}

setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/get_chatadmin.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.responseText;
                chatBox.innerHTML = data;
                if (!chatBox.classList.contains("active")) {
                    scrollToBottom();
                }
            }
        }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("incoming_id=<?php echo $clicked_unique_id; ?>");
}, 500);


function scrollToBottom(){
    chatBox.scrollTop = chatBox.scrollHeight;
  }
  


</script>

<?php
    include('../Assets/includes/footer.php');
?>