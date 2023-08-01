<?php
require_once('../php/config.php');


if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT o.*, v.shop_name, v.unique_id as vcode , o.online_payment_receipt, o.buyer_id
    FROM `order_list` o
    INNER JOIN seller_accounts v ON o.seller_id = v.id
    WHERE o.id = '{$_GET['id']}'"); // Use 'id' instead of 'unique_id'
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }else{
?>
		<center>Unknown order</center>
		<style>
			#uni_modal .modal-footer{
				display:none
			}
		</style>
		<div class="text-right">
			<button class="btn btndefault bg-gradient-dark btn-flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
		</div>
		<?php
		exit;
		}
}
?>

<style>
	#uni_modal .modal-footer{
		display:none
	}
    .prod-img{
        width:calc(100%);
        height:auto;
        max-height: 10em;
        object-fit:scale-down;
        object-position:center center
    }

    .custom-modal {
  display: none;
  position: fixed;
  z-index: 1000;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);

}



</style>




<div class="custom-modal" id="update_order_status_modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal header -->
      <div class="modal-header">
        <h5 class="modal-title">Update Order Status - <span id="order_code_placeholder"></span></h5>
      </div>

      <!-- Modal body -->
      <div class="modal-body rounded-0">
        <div class="container-fluid">
        <form action="" id="update_status">
        <input type="hidden" name="id" value="3">
        <input type="hidden" name="order_list_id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">
    <input type="hidden" name="order_code" value="<?= isset($order_code) ? $order_code : '' ?>">
    <input type="hidden" name="buyer_id" value="<?= isset($buyer_id) ? $buyer_id : '' ?>">
        <div class="form-group">
            <label for="status" class="control-label">Status</label>
            <select name="status" id="status" class="form-control rounded-0" required="">
                <option value="0">Pending</option>
                <option value="1">Confirmed</option>
                <option value="2">Packed</option>
                <option value="3">Out for Delivery</option>
                <option value="4">Delivered</option>
                <option value="5">Cancelled</option>
            </select>
        </div>
    </form>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer rounded-0">
        <button type="button" class="btn btn-sm btn-round btn-success" id="submit">Save</button>
        <button type="button" class="btn btn-sm btn-round btn-secondary" id="customCloseModalBtn">Cancel</button>
      </div>
    </div>
  </div>
</div>





<div class="container-fluid">
	<div class="row">
        <div class="col-3 border bg-gradient-olive"><span class="">Order Code</span></div>
        <div class="col-9 border"><span class="font-weight-bolder"><?= isset($order_code) ? $order_code : '' ?></span></div>
        <div class="col-3 border bg-gradient-olive"><span class="">Vendor</span></div>
        <div class="col-9 border"><span class="font-weight-bolder"><?= isset($shop_name) ? $vcode.' - '.$shop_name : '' ?></span></div>
        <div class="col-3 border bg-gradient-olive"><span class="">Payment Method</span></div>
        <div class="col-9 border"><span class="font-weight-bolder"><?= isset($payment_method) ? $payment_method : '' ?></span></div>
        <div class="col-3 border bg-gradient-olive"><span class="">Delivery Method</span></div>
        <div class="col-9 border"><span class="font-weight-bolder"><?= isset($delivery_method) ? $delivery_method : '' ?></span></div>
        <?php
if (isset($payment_method) && $payment_method == "Online Payment") {
    echo '
    <div class="col-3 border bg-gradient-olive"><span class="">Online Payment Receipt</span></div>
    <div class="col-9 border">
        <form id="upload_payment_form">
            <div class="input-group mt-2 mb-1">
            </div>
        </form>
        <img id="onlinepaymentpreview" src="' . (isset($online_payment_receipt) && !empty($online_payment_receipt) ? $online_payment_receipt : '../seller_profiles/no-image-available.png') . '" class="img-center prod-img border bg-gradient-gray" onclick="showFullImage(this)">
    </div>';
}
?>
        <div class="col-3 border bg-gradient-olive"><span class="">Delivery Address</span></div>
        <div class="col-9 border"><span class="font-weight-bolder"><?= isset($delivery_address) ? $delivery_address : '' ?></span></div>
        <div class="col-3 border bg-gradient-olive"><span class="">Status</span></div>
        <div class="col-9 border"><span class="font-weight-bolder">
            <?php 
            $status = isset($order_status) ? $order_status : '';
                switch($status){
                    case 0:
                        echo '<span class="badge badge-secondary bg-gradient-secondary px-3 rounded-pill">Pending</span>';
                        break;
                    case 1:
                        echo '<span class="badge badge-primary bg-gradient-primary px-3 rounded-pill">Confirmed</span>';
                        break;
                    case 2:
                        echo '<span class="badge badge-info bg-gradient-info px-3 rounded-pill">Packed</span>';
                        break;
                    case 3:
                        echo '<span class="badge badge-warning bg-gradient-warning px-3 rounded-pill">Out for Delivery</span>';
                        break;
                    case 4:
                        echo '<span class="badge badge-success bg-gradient-success px-3 rounded-pill">Delivered</span>';
                        break;
                    case 5:
                        echo '<span class="badge badge-danger bg-gradient-danger px-3 rounded-pill">Cancelled</span>';
                        break;
                    default:
                        echo '<span class="badge badge-light bg-gradient-light border px-3 rounded-pill">N/A</span>';
                        break;
                }
            ?>
             <button class="btn btn-sm btn-success float-right my-1" id="updateOrderStatusBtn">Update Status</button>
        </div>
    </div>
    <div class="clear-fix mb-2"></div>
    <div id="order-list" class="row">
    <?php 
        $gtotal = 0;
        $products = $conn->query("SELECT o.*, p.product_name as `name`, p.price,p.product_image, review_status FROM `order_items` o inner join product_list p on o.product_id = p.id where o.order_id='{$id}' order by p.product_name asc");
        while($prow = $products->fetch_assoc()):
            $total = $prow['price'] * $prow['quantity'];
            $gtotal += $total;
        ?>
        <div class="col-12 border product-item" data-product-id="<?= $prow['product_id'] ?>">
            <div class="d-flex align-items-center p-2">
                <div class="col-2 text-center">
                    <a href="../buyer/view_product.php?id=<?= $prow['product_id'] ?>"><img src="../<?= $prow['product_image'] ?>" alt="" class="img-center prod-img border bg-gradient-gray"></a>
                </div>
                <div class="col-auto flex-shrink-1 flex-grow-1">
                    <h4><b><?= $prow['name'] ?></b></h4>
                    <div class="d-flex">
                        <div class="col-auto px-0"><small class="text-muted">Price: </small></div>
                        <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive"><?= $prow['price'] ?></small></p></div>
                    </div>
                    <div class="d-flex">
                        <div class="col-auto px-0"><small class="text-muted">Qty: </small></div>
                        <div class="col-auto px-0 flex-shrink-1 flex-grow-1"><p class="m-0 pl-3"><small class="text-olive"><?= $prow['quantity'] ?></small></p></div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php endwhile; ?>
        <div class="col-12 border">
            <div class="d-flex">
                <div class="col-9 h4 font-weight-bold text-right text-muted">Total</div>
                <div class="col-3 h4 font-weight-bold text-right"><?= $gtotal ?></div>
            </div>
        </div>
    </div>
	<div class="clear-fix mb-3"></div>
	<div class="text-right">
	</div>
</div>
<script>
    function start_loader() {
        // Add your code to show a loading indicator (if any)
        // For example, you can display a spinner or loading overlay.
    }

    function end_loader() {
        // Add your code to hide the loading indicator (if any)
        // For example, you can remove the spinner or loading overlay.
    }

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

    // Define the showAlert function
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

 // Use jQuery to wait for the document to be ready
  $(document).ready(function () {
    // Get the "Update Status" button by its ID
    var updateOrderStatusBtn = $("#updateOrderStatusBtn");

    // Get the modal by its ID
    var updateOrderStatusModal = $("#update_order_status_modal");

    // Get the custom close button by its ID
    var closeModalBtn = $("#closeModalBtn");

    // Add an event listener to the "Update Status" button to show the modal when clicked
    updateOrderStatusBtn.on("click", function () {
      showUpdateOrderStatusModal(); // Call the function to show the modal
    });

    // Add an event listener to the custom close button to hide the modal when clicked
    closeModalBtn.on("click", function () {
      hideUpdateOrderStatusModal(); // Call the function to hide the modal
    });

      // Add an event listener to the "Save" button inside the modal to handle form submission
  $("#submit").on("click", function () {
    updateOrderStatus(); // Call the function to update the order status
  });
  });

  function showUpdateOrderStatusModal() {
  var orderCodePlaceholder = document.getElementById('order_code_placeholder');
  if (orderCodePlaceholder) {
    orderCodePlaceholder.innerText = '<?= isset($order_code) ? $order_code : '' ?>';
  }
  document.getElementById('update_order_status_modal').style.display = 'block';
}

function hideUpdateOrderStatusModal() {
  document.getElementById('update_order_status_modal').style.display = 'none';
}

var customCloseModalBtn = $("#customCloseModalBtn");

// Add an event listener to the custom close button to hide the modal when clicked
customCloseModalBtn.on("click", function () {
  hideUpdateOrderStatusModal(); // Call the function to hide the modal
});





// Function to update the order status using AJAX
function updateOrderStatus() {
  var formData = $("#update_status").serialize(); // Serialize the form data
  var orderCode = "<?= isset($order_code) ? $order_code : '' ?>"; // Get the order code

  // Append the order code to the form data
  formData += "&order_code=" + encodeURIComponent(orderCode);

  // Use AJAX to submit the form data to the server
  $.ajax({
    type: "POST", // Use "POST" method to submit data
    url: "../php/update_order_status.php", // Replace with the URL of your PHP script to handle the form submission
    data: formData,
    beforeSend: function () {
      start_loader(); // Show loading indicator before AJAX request is sent
    },
    success: function (response) {
      end_loader(); // Hide loading indicator after successful AJAX request

      // Process the response from the server
      if (response.success) {
        // If the server response indicates success, show a success message
        showAlert("Success", response.message, "success");
      } else {
        // If the server response indicates an error, show an error message
        showerror("Error", response.message, "error");
      }

      // Hide the modal after form submission
      hideUpdateOrderStatusModal();
    },
    error: function (xhr, status, error) {
      end_loader(); // Hide loading indicator on AJAX request error

      // Show an error message in case of AJAX request error
      showerror("Error", "An error occurred. Please try again later.", "error");
    },
  });
}
</script>

