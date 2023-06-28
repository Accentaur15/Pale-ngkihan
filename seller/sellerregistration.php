<?php require_once('../php/config.php');


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Seller Registration</title>

  <!--css-->
  <link href="../seller/sellerregistration.css" rel="stylesheet">
  <!--fontawesome-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!--bootstrap-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!--title icon-->
  <link rel="apple-touch-icon" sizes="180x180" href="../Assets/logo/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="../Assets/logo/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="../Assets/logo/favicon-16x16.png" />
  <!--Animation-->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!--javascript-->
  <script src="js/sellerreg.js"></script>

  <!--Navigation Bar-->
  <nav class="navbar navbar-expand-md">
    <div class="container-fluid">
      <a class="navbar-brand" href="/index.html">
        <img src="../Assets/logo/Artboard 1.png" class="logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#buton"> <i
          class="fas fa-bars"></i></button>
      <div class="collapse navbar-collapse" id="buton">

        <ul class="navbar-nav ms-auto justify-content-end">
          <li class="nav-item">
            <a class="nav-link active mx-3" aria-current="page" href="../index.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link  mx-3" href="#">Marketplace</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle  mx-3" href="#" id="navbarDropdownMenuLink" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              Login
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="../buyerlogin.php">Buyer Login</a></li>
              <li><a class="dropdown-item" href="../seller/sellerlogin.php">Seller Login</a></li>
              <li><a class="dropdown-item" href="../admin/admin.php">Admin Login</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link mx-3" href="../aboutus.html">About Us</a>
          </li>
        </ul>
      </div>
    </div>

  </nav>
  <!--End of Navigation Bar-->

</head>

<body>

  <section class="h-100 h-custom gradient-custom-2 border">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12">
          <div class="card card-registration card-registration-2" style="border-radius: 15px;">

            <form action="../php/sellersignup.php" method="POST" enctype="multipart/form-data">

              <div class="card-body p-0">
                <div class="row g-0">
                  <div class="col-lg-6" style="background: #F3EFE2;">
                    <div class="p-5">
                      <h1 class="fw-bolder mb-3">Create Seller Account</h1>

                      <hr class="mx-n3">
                      <div class="error-text alert alert-danger text-center fs-5" style="display:none;">Error</div>
                      <div class="row">


                        <div class="col-md-6 mb-4 pb-2">
                          <div class="form-outline">
                            <label class="form-label" for="form3Example1m1" style="font-weight: bold;">Shop name</label>
                            <input name="sname" type="text" id="shopname" class="form-control form-control-md" required
                              placeholder="Enter Shop Name" />
                          </div>
                        </div>

                        <div class="col-md-6 mb-4 pb-2">
                          <div class="form-outline">
                            <label class="form-label" for="form3Example1m1" style="font-weight: bold;">Username</label>
                            <input name="uname" type="text" id="username"
                              class="form-control form-control-md fst-italic" required placeholder="Enter Username" />
                          </div>
                        </div>

                        <div class="mb-4 pb-2">
                          <div class="form-outline">
                            <label class="form-label" for="form3Example1m1" style="font-weight: bold;">Shop Owner Full
                              name</label>
                            <input name="shopowner" type="text" id="form3Examplev4" class="form-control form-control-md"
                              required placeholder="Enter Shop Owner Full Name" />
                          </div>
                        </div>

                      </div>

                      <div class="col-md-6 mb-4">

                        <h5 class="mb-2 pb-1"><i class="fa-solid fa-venus-mars"></i>&nbsp;Gender:</h5>
                        <select name="gender" class="form-select form-select-md form-select-border" required>
                          <option value="" selected disabled>Select Gender</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>


                      </div>

                      <div class="mb-4 pb-2">
                        <div class="form-outline">
                          <i class="fa-regular fa-address-book"></i><label class="form-label" for="form3Example1m1"
                            style="font-weight: bold;">&nbsp;Contact
                            Number</label>
                          <input name="cnumber" type="number" id="form3Examplev4" class="form-control form-control-md"
                            required required pattern="[0-9]{10}"
                            placeholder="Enter 10 Digit Number example (0927241241)" />
                        </div>
                      </div>

                      <div class="mb-4 pb-2">
                        <div class="form-outline">
                          <i class="fa-solid fa-location-dot"></i> <label class="form-label" for="form3Example1m1"
                            style="font-weight: bold;">&nbsp;Address</label>
                          <input name="address" type="text" id="form3Examplev4" class="form-control form-control-md"
                            required placeholder="Enter Address" />
                        </div>
                      </div>

                      <div class="mb-4 pb-2">
                        <div class="form-outline">
                          <i class="fa-regular fa-envelope"></i> <label class="form-label" for="form3Example1m1"
                            style="font-weight: bold;">&nbsp;Email
                            Address</label>
                          <input name="email" type="email" id="form3Examplev4" class="form-control form-control-md"
                            required placeholder="Enter Email Address" />
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-6 mb-4 pb-2">
                          <i class="fa-sharp fa-solid fa-lock"></i> <label class="form-label" for="form3Example1m1"
                            style="font-weight: bold;">&nbsp;Password</label>
                          <input name="password" type="password" id="form3Example4cg"
                            class="form-control form-control-md" required placeholder="Enter Password" />
                        </div>

                        <div class="col-md-6 mb-4 pb-2">
                          <i class="fa-solid fa-key"></i> <label class="form-label" for="form3Example1m1"
                            style="font-weight: bold;">&nbsp;Confirm
                            Password</label>
                          <input name="cpassword" type="password" id="form3Example4cg"
                            class="form-control form-control-md" required placeholder="Enter Confirm Password" />
                        </div>

                      </div>



                    </div>
                  </div>
                  <div class="col-lg-6 bg-indigo text-black fw-bold">
                    <div class="p-5">

                      <h4 class="fw-bold mb-2 mt-3">Additional Information</h4>

                      <hr class="mx-n3">

                      <div class="input-group mb-4 pb-2">
                        <label class="input-group-text" for="inputGroupSelect01">Do you have a
                          Physical Store?</label>
                        <select name="ispstore" class="form-select" id="inputGroupSelect01" onchange="showDiv(this)"
                          required>
                          <option value="" selected disabled>Choose...</option>
                          <option value="Yes">Yes</option>
                          <option value="No">No</option>
                        </select>
                      </div>


                      <div id="showpermit">

                        <div class="row align-items-center py-3">
                          <div class="col-md-3 ps-5">

                            <div class="text-center mb-2"></div>
                            <h6 class="mb-1 fw-bold text-center">Business Permit</h6>

                          </div>
                          <div class="col-md-9 pe-5">
                            <div class="form-group col-lg-6 text-center mb-2 mx-auto">
                              <img src="../seller_profiles/no-image-available.png" alt="Business Permit"
                                id="previewbpermit" class="border border-gray img-thumbnail"
                                onclick="showFullImage(this)">
                            </div>

                            <input name="bpermit" class="form-control form-control-sm" id="businesspermit" type="file"
                              onchange="previewFile('previewbpermit', this);" />
                            <div class="small text-muted mt-2">Upload your Business Permit.
                              Max file
                              size 30 MB</div>

                          </div>
                        </div>

                        <div class="row align-items-center py-3">
                          <div class="col-md-3 ps-5">

                            <div class="text-center mb-2"></div>
                            <h6 class="mb-1 fw-bold text-center">DTI Permit</h6>

                          </div>
                          <div class="col-md-9 pe-5">

                            <div class="form-group col-lg-6 text-center mb-2 mx-auto">
                              <img src="../seller_profiles/no-image-available.png" alt="DTI Permit"
                                id="previewdtipermit" class="border border-gray img-thumbnail"
                                onclick="showFullImage(this)">
                            </div>

                            <input name="dtipermit" class="form-control form-control-sm" id="dtipermit" type="file"
                              onchange="previewFile('previewdtipermit', this);" />
                            <div class="small text-muted mt-2">Upload your DTI Permit. Max
                              file
                              size 30 MB</div>

                          </div>
                        </div>

                        <div class="row align-items-center py-3">
                          <div class="col-md-3 ps-5">

                            <div class="text-center mb-2"></div>
                            <h6 class="mb-1 fw-bold text-center">Mayor's Permit</h6>

                          </div>
                          <div class="col-md-9 pe-5">
                            <div class="form-group col-lg-6 text-center mb-2 mx-auto">
                              <img src="../seller_profiles/no-image-available.png" alt="Mayors Permit"
                                id="previewmayorspermit" class="border border-gray img-thumbnail"
                                onclick="showFullImage(this)">
                            </div>
                            <input name="mayorspermit" class="form-control form-control-sm" id="mayorspermit"
                              type="file" onchange="previewFile('previewmayorspermit', this);" />
                            <div class="small text-muted mt-2">Upload your Mayor's Permit.
                              Max file
                              size 30 MB</div>

                          </div>
                        </div>

                      </div>


                      <hr class="mx-n3">

                      <h4 class="fw-bold mb-2 mt-5">Proof of Details</h4>

                      <hr class="mx-n3">

                      <div class="row align-items-center py-3">
                        <div class="col-md-3 ps-5">

                          <div class="text-center mb-2"> <i class="fa-solid fa-id-card fa-2xl"></i></div>
                          <h6 class="mb-1 fw-bold text-center">Valid I.D</h6>
                        </div>

                        <div class="col-md-9 pe-5">
                          <div class="form-group col-lg-6 text-center mb-2 mx-auto">
                            <img src="../seller_profiles/no-image-available.png" alt="Valid ID" id="previewValidID"
                              class="border border-gray img-thumbnail" onclick="showFullImage(this)">
                          </div>
                          <input name="validid" class="form-control form-control-sm" id="formFileLg" type="file"
                            onchange="previewFile('previewValidID', this);" required />
                          <div class="small text-muted mt-2">Upload your Valid Identification
                            (I.D). Max file
                            size 30 MB</div>
                        </div>

                      </div>

                      <hr class="mx-n3">

                      <div class="row align-items-center py-3">
                        <div class="col-md-3 ps-5">

                          <div class="text-center mb-2"> <i class="fa-solid fa-circle-user fa-2xl"></i></div>
                          <h6 class="mb-1 fw-bold text-center">Shop Logo</h6>

                        </div>
                        <div class="col-md-9 pe-5">
                          <div class="form-group col-lg-6 text-center mb-2 mx-auto">
                            <img src="../seller_profiles/no-image-available.png" alt="Shop Logo" id="previewShopLogo"
                              class="border border-gray img-thumbnail" onclick="showFullImage(this)">
                          </div>
                          <input name="shoplogo" class="form-control form-control-sm" id="formFileLg" type="file"
                            onchange="previewFile('previewShopLogo', this);" required />
                          <div class="small text-muted mt-2">Upload your Shop Logo. Max file
                            size 30 MB</div>

                        </div>
                      </div>

                      <hr class="mx-n3">

                      <button type="submit" name="submit" class="button btn btn-lg text-white w-100 mb-3 mt-4"
                        style="background: #6BB25A;">Register</button>

                      <div class="d-flex align-items-center justify-content-center pb-4">
                        <p class="mb-0 me-2">Already have an account?</p>
                        <a href="../seller/sellerlogin.php" class="button" style="color: #5AC9E8;">Sign In</a>
                      </div>




                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Copyright Section-->

  <div class="copyright py-4 text-center text-white d-flex p-2">
    <div class="container"><small>Copyright &copy; Pale-ngkihan 2023</small></div>
  </div>

  <!-- Form Section-->
  <script>
    const form = document.querySelector('.card-registration form');
    console.log(form);
    const submitBtn = form.querySelector('.button');
    const errorText = form.querySelector('.error-text');


    form.onsubmit = (e) => {
      e.preventDefault();
    }



    submitBtn.onclick = () => {
      // Start AJAX
      let xhr = new XMLHttpRequest(); // Create XML object
      xhr.open("POST", "../php/sellersignup.php", true);
      xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            let data = xhr.response.trim(); // Trim whitespace from the response
            let comparisonResult = data === "success";
            console.log(comparisonResult);
            if (comparisonResult) { // Use comparisonResult variable instead of repeating the comparison
              location.href = "sellerlogin.php";
            } else {
              errorText.textContent = data;
              errorText.style.display = "block";
            }
          }
        }
      }
      // Send data through AJAX to PHP
      let formData = new FormData(form); // Create new FormData object from the form data
      xhr.send(formData); // Send data to PHP
    }
  </script>
  <!--Animation java-->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>

  <!--Bootstrap java-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>