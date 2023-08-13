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
  <title>Seller | Admin Support</title>
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
<link href="admin_support.css" rel="stylesheet">
  <!-- Theme style -->
  <link rel="stylesheet" href="../Assets/dist/css/adminlte.min.css">
  <!-- Image JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<link rel="stylesheet" href="../Assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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
        <!-- Chat Container -->
        <div class="card shadow rounded">
          <div class="card-body">
            <!-- Support Chat Section -->
            <div class="wrapper">
              <section class="support-chat">
                <header>
                  <div class="content">
                    <img src="../<?php echo $shoplogo ?>" alt="">
                    <div class="details">
                      <span><?php echo  $shopname ?></span>
                      <!-- Show support status, e.g., "Connected to Support" -->
                      <p>Connected to Support</p>
                    </div>
                  </div>
                </header>
                <div class="chat-box">
                  <!-- The chat messages with the admin will appear here -->
                </div>
                <form action="#" class="typing-area">
                  <input type="text" class="input-field" placeholder="Type your message here..." autocomplete="off">
                  <button><i class="fab fa-telegram-plane"></i></button>
                </form>
              </section>
            </div>
            <!-- End of Support Chat Section -->
          </div>
        </div>
        <!-- End of Chat Container -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <script>
    const form = document.querySelector(".typing-area"),
      inputField = form.querySelector(".input-field"),
      sendBtn = form.querySelector("button"),
      chatBox = document.querySelector(".chat-box");

    // Prevent the form from being submitted on Enter key press
    form.onsubmit = (e) => {
      e.preventDefault();
    }

    inputField.focus();
    inputField.onkeyup = () => {
      if (inputField.value.trim() !== "") {
        sendBtn.classList.add("active");
      } else {
        sendBtn.classList.remove("active");
      }
    }

    // Send messages to the admin
    sendBtn.onclick = () => {
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "../php/send_to_admin.php", true);
      xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            inputField.value = "";
            scrollToBottom();
          }
        }
      }
      let formData = new FormData(form);
      xhr.send(formData);
    }

    // Load chat messages with the admin
    function loadChat() {
      let xhr = new XMLHttpRequest();
      xhr.open("GET", "../php/load_admin_chat.php", true);
      xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            let data = xhr.response;
            chatBox.innerHTML = data;
            scrollToBottom();
          }
        }
      }
      xhr.send();
    }

    // Load chat messages when the page loads
    loadChat();

    // Scroll to the bottom of the chat box
    function scrollToBottom() {
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Poll for new messages every X seconds (adjust the interval as needed)
    setInterval(loadChat, 5000);
  </script>

  <?php
    include('../Assets/includes/footer.php');
  ?>
