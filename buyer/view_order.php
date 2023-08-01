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

        // Now, use a prepared statement for the buyer query
        $buyer_query = $conn->prepare("SELECT first_name, last_name FROM buyer_accounts WHERE id = ?");
        $buyer_query->bind_param("i", $buyer_id);
        $buyer_query->execute();
        $buyer_result = $buyer_query->get_result();

        if ($buyer_result->num_rows > 0) {
            $buyer_data = $buyer_result->fetch_assoc();
            $first_name = $buyer_data['first_name'];
            $last_name = $buyer_data['last_name'];
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
</style>
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
if (isset($payment_method) && $payment_method == "Online Payment" && $order_status != 5) {
    echo '
    <div class="col-3 border bg-gradient-olive"><span class="">Online Payment Receipt</span></div>
    <div class="col-9 border">
        <form id="upload_payment_form">
            <div class="input-group mt-2 mb-1">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="payment_receipt" id="payment_receipt" required onchange="previewFile(&quot;onlinepaymentpreview&quot;, this);" >
                    <label class="custom-file-label" for="payment_receipt">Choose file</label>
                </div>
            </div>
        </form>
        <img id="onlinepaymentpreview" src="' . (isset($online_payment_receipt) && !empty($online_payment_receipt) ? $online_payment_receipt : '../seller_profiles/no-image-available.png') . '" class="img-center prod-img border bg-gradient-gray" onclick="showFullImage(this)">
        <button class="btn btn-success btn-sm mt-2" onclick="uploadPaymentReceipt(\''.(isset($order_code) ? $order_code : '').'\')">Upload</button>
        <p class="small text-muted pt-2 ml-2">Upload Online Payment Receipt Here.</p>
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
                    <?php if ($prow['review_status'] == 1): ?>
                        <div class="col-auto">
                            <div class="d-flex justify-content-end">
                                <!-- Show reviewed content here -->
                                <span class="badge badge-primary">Reviewed</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($order_status == 4 && $prow['review_status'] == 0): ?>
                        <div class="col-auto">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-warning btn-sm text-white" onclick="showReviewForm(<?= $prow['product_id'] ?>)">Write a Review</button>
                            </div>
                        </div>
                    <?php endif; ?>
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
        <?php if(isset($status) && $status == 0): ?>
		    <button class="btn btn-default bg-gradient-danger text-light btn-sm btn-flat" type="button" id="cancel_order">Cancel Order</button>
        <?php endif; ?>
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

    $(function () {
        $('#cancel_order').click(function () {
            var order_id = '<?= isset($id) ? $id : '' ?>';

            // Use SweetAlert2 for the confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // User clicked "Yes, cancel it!"
                    cancel_order(order_id);
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


  function cancel_order(order_id) {
    start_loader();
    $.ajax({
      url: "../php/cancel_order.php",
      method: "POST",
      data: { id: order_id },
      dataType: "json",
      error: err => {
        console.log(err);
        Swal.fire('Error', 'An error occurred.', 'error');
        end_loader();
      },
      success: function (resp) {
        if (typeof resp == 'object' && resp.status == 'success') {
          showAlert('Success', 'Order successfully cancelled!', 'success');
        } else {
          Swal.fire('Error', 'Failed to cancel the order.', 'error');
        }
        end_loader();
      }
    });
  }

  $(function () {
  bsCustomFileInput.init();
});

function uploadPaymentReceipt(orderCode) {
    // Get the file input element and its value
    var paymentReceiptInput = document.getElementById('payment_receipt');
    var paymentReceiptFile = paymentReceiptInput.files[0];

    // Check if a file is selected
    if (!paymentReceiptFile) {
        showAlert('Error', 'Please choose a file to upload.', 'error');
        return;
    }

    // Create a FormData object and append the file and orderId to it
    var formData = new FormData();
    formData.append('order_code', orderCode); // Pass the order code as a parameter
    //console.log(orderCode);
    formData.append('payment_receipt', paymentReceiptFile);

    start_loader();
    $.ajax({
        url: '../php/online_payment_receipt.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        error: function (err) {
            console.log(err);
            showAlert('Error', 'An error occurred during file upload.', 'error');
            end_loader();
        },
        success: function (resp) {
            if (typeof resp === 'object' && resp.status === 'success') {
                showAlert('Success', 'File uploaded successfully.', 'success');
            } else {
                showAlert('Error', 'Failed to upload the file.', 'error');
            }
            end_loader();
        }
    });
}

function showReviewForm(productId) {
    $('#reviewModal').modal('show');
    currentProductId = productId;
  }



  function submitReview() {
    const productId = currentProductId; 
    const reviewText = document.getElementById('review_text').value;
    const rating = parseInt($('#star_rating').find('.star.active').last().data('rating'), 10);
    const reviewerFname = $('#reviewer_fname').val();
    const reviewerLname = $('#reviewer_lname').val();
    const reviewerName = reviewerFname + ' ' + reviewerLname;

    // Check if the review text is empty or rating is not selected
    if (!reviewText.trim() || isNaN(rating) || rating === 0) {
        showerror('Error', 'Please provide a rating and write your review.', 'error');
        return;
    }

    // If both rating and review are provided, you can proceed to submit the review.
    // Send the review data to the submit_review.php script using AJAX.
    $.ajax({
        url: '../php/submit_review.php', 
        method: 'POST',
        data: {
            product_id: productId,
            review_text: reviewText,
            rating: rating,
            reviewer_fname: reviewerFname, // Add the first name field
            reviewer_lname: reviewerLname // Add the last name field
        },
        dataType: 'json',
        error: function (err) {
            console.log(err);
            showAlert('Error', 'An error occurred while submitting the review.', 'error');
        },
        success: function (resp) {
            if (typeof resp === 'object' && resp.status === 'success') {
                showAlert('Success', resp.message, 'success');
                $('#reviewModal').modal('hide');
                // Optionally, you can reload the page to show the newly submitted review.
                // location.reload();
            } else {
                showAlert('Error', resp.message, 'error');
            }
        }
    });
}






  // Star rating script
  $(document).ready(function() {
    // Highlight selected stars on click
    $('.star-rating .star').click(function() {
      const selectedRating = parseInt($(this).data('rating'), 10);
      $('.star-rating .star').removeClass('active');
      $(this).addClass('active');
      $(this).prevAll('.star').addClass('active');

      // Update the value of the selected rating in a hidden input (optional)
      $('#selected_rating').val(selectedRating);
    });

    // Clear selected stars when hovering over the rating element
    $('.star-rating').hover(function() {
      const selectedRating = parseInt($(this).find('.star.active').last().data('rating'), 10);
      if (selectedRating === undefined || selectedRating === 0) {
        $(this).find('.star').removeClass('active');
      }
    });
  });

</script>

<style>
  .star-rating {
    font-size: 24px;
  }

  .star-rating .star {
    cursor: pointer;
    color: #ccc;
  }

  .star-rating .star.active {
    color: #ffc107; /* Yellow color for active stars */
  }
</style>

<div class="modal" id="reviewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Write a Review</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
        <input type="hidden" id="reviewer_fname" value="<?php echo $first_name; ?>">
<input type="hidden" id="reviewer_lname" value="<?php echo $last_name; ?>">
          <label for="review_text">Your Review:</label>
          <textarea id="review_text" class="form-control" rows="5"></textarea>
        </div>
        <div class="form-group">
          <label for="star_rating">Rating:</label>
          <div id="star_rating" class="star-rating">
            <span class="star" data-rating="1">&#9733;</span>
            <span class="star" data-rating="2">&#9733;</span>
            <span class="star" data-rating="3">&#9733;</span>
            <span class="star" data-rating="4">&#9733;</span>
            <span class="star" data-rating="5">&#9733;</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning text-white" onclick="submitReview()">Submit</button>
      </div>
    </div>
  </div>
</div>
