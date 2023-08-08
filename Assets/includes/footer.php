  <!-- Main Footer -->
  <footer class="main-footer text-center text-white" style = "background-color: #678A71;">
    <!-- Default to the left -->
    <strong>Copyright &copy; Pale-ngkihan 2023.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->


<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../Assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../Assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../Assets/dist/js/adminlte.min.js"></script>
<!-- Summernote -->
<script src="../Assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- CodeMirror -->
<script src="../Assets/plugins/codemirror/codemirror.js"></script>
<script src="../Assets/plugins/codemirror/mode/css/css.js"></script>
<script src="../Assets/plugins/codemirror/mode/xml/xml.js"></script>
<script src="../Assets/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<!-- bs-custom-file-input -->
<script src="../Assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
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
        <!-- SweetAlert2 -->
        <script src="../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>


<script>
// Function to generate star rating HTML based on the rating value
function generateStarRating(rating) {
  const fullStar = '<i class="fas fa-star"></i>';
  const halfStar = '<i class="fas fa-star-half-alt"></i>';
  const emptyStar = '<i class="far fa-star"></i>';

  const roundedRating = Math.round(rating * 2) / 2; // Round to nearest 0.5

  const fullStarsCount = Math.floor(roundedRating);
  const halfStarPresent = roundedRating % 1 !== 0;
  const emptyStarsCount = 5 - Math.ceil(roundedRating);

  let starRatingHTML = '';

  for (let i = 0; i < fullStarsCount; i++) {
    starRatingHTML += fullStar;
  }

  if (halfStarPresent) {
    starRatingHTML += halfStar;
  }

  for (let i = 0; i < emptyStarsCount; i++) {
    starRatingHTML += emptyStar;
  }

  return `<span class="star-rating">${starRatingHTML}</span>`;
}

// Function to fetch and display reviews for a product
function displayProductReviews(productId, page = 1, reviewsPerPage = 5) {
  // Make an Ajax request to fetch reviews for the given product
  $.ajax({
    url: '../php/get_product_reviews.php',
    method: 'POST',
    data: { product_id: productId, page: page, reviews_per_page: reviewsPerPage },
    dataType: 'json',
    success: function(response) {
      // Display the reviews in the modal
      var reviewsHTML = '';

      if (response.reviews.length > 0) {
        response.reviews.forEach(review => {
          // Generate star rating HTML based on the rating value
          const starRating = generateStarRating(review.rating);

          reviewsHTML += `
            <div class="card mb-2">
              <div class="card-header">
                <h5 class="card-title">Reviewer Name: ${review.reviewer_name}</h5>
              </div>
              <div class="card-body">
                <p class="card-text">Rating: ${starRating}</p>
                <p class="card-text">Review Text: ${review.review_text}</p>
              </div>
            </div>
          `;
        });
      } else {
        reviewsHTML = '<p>No reviews for this product yet.</p>';
      }

      // Show the reviews in the modal's body
      $('#reviewsContainer').html(reviewsHTML);

      // Update pagination controls
      updatePaginationControls(response.total_reviews, page, reviewsPerPage);
    },
    error: function(xhr, status, error) {
      // Handle any errors if needed
      console.error(xhr.responseText);
    }
  });
}

// Function to update pagination controls
function updatePaginationControls(totalReviews, currentPage, reviewsPerPage) {
  const totalPages = Math.ceil(totalReviews / reviewsPerPage);
  let paginationHTML = '';

  for (let i = 1; i <= totalPages; i++) {
    if (i === currentPage) {
      paginationHTML += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
    } else {
      paginationHTML += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
    }
  }

  // Show pagination controls
  $('#paginationContainer').html(paginationHTML);

  // Attach click event handler to pagination links
  $('#paginationContainer .page-link').on('click', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    displayProductReviews(currentProductId, page, reviewsPerPage);
  });
}

// Attach click event handler to each "View Reviews" button
$('.view-reviews-btn').on('click', function() {
  const productId = $(this).data('product-id');
  currentProductId = productId; // Store the current product ID for pagination
  displayProductReviews(productId);
});

          $(function () {
            bsCustomFileInput.init();
        });

        $(document).ready(function() {

});
$("#harvestScheduleTable").DataTable({
          "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
      });

      
      $("#productListTable").DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
  });
  $("#productlist").DataTable({
    "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
  });
//summernote
  $(document).ready(function() {
  $('#summernote').summernote({
    toolbar: [
  ['style', ['style']],
  ['font', ['bold', 'underline', 'clear']],
  ['fontname', ['fontname']],
  ['color', ['color']],
  ['para', ['ul', 'ol', 'paragraph']],
  ['view', ['fullscreen', 'help']],
],
  });
  
});

//edit category
$(document).ready(function() {
  $('#editcategory').on('hidden.bs.modal', function () {
    $('body').css('overflow', 'auto');
  });
});


$(document).ready(function() {
                $('.editbtn').on('click', function() {
                    $('#editcategory').modal({
      backdrop: false
    });
// Open the modal when the button is clicked
$('#editcategory').modal('show');
                    $tr = $(this).closest('tr');
                    var tabdata = $tr.children("td").map(function() {
                        return $(this).text();
                    }).get();

                    //console.log(tabdata);
                    $('#update_id').val(tabdata[0].toString().trim());
                    $('#cname').val(tabdata[2]);
                    $('#description').val(tabdata[3]);
                    var statusValue = tabdata[4].trim() === 'Active' ? '1' : '2';
        $('#status').val(statusValue);

                   
                });
            });


        //summernote productedit
        $(document).ready(function() {
    var productDescription = $('#summernoteproductedit').data('value');
  
    $('#summernoteproductedit').summernote({
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['fullscreen', 'help']]
        ],
    });
  
    $('#summernoteproductedit').summernote('code', productDescription);
});
</script>



</body>
</html>