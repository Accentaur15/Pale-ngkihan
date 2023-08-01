<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Login</title>

    <!--css-->
    <link href="admin.css" rel="stylesheet">
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

    <!--Navigation Bar-->
    <nav class="navbar navbar-expand-md">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.html">
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
                            <li><a class="dropdown-item" href="admin.php">Admin Login</a></li>
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
    <section class="h-100 gradient-form border"
        style="background-image: url(../Assets/images/Background-palay.png); min-height: 83vh;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6" style="background: #F3EFE2;">
                                <div class="card-body p-md-5 mx-md-4">

                                    <div class="text-center">
                                        <img src="../Assets/logo/logo P.png" style="width: 150px; height:125px;"
                                            alt="logo">
                                        <h2 class="mt-1 mb-5 pb-1" style="font-weight: bolder;">Admin Login</h2>
                                    </div>
                                    <div class="this">
                                        <form action="php/adminsignin.php" method="POST" >
                                            <div class="error-text alert alert-danger text-center fs-5"
                                                style="display:none;">Error</div>
                                            <p class="text-center">Sign in to start your session</p>

                                            <div class="form-outline mb-4">
                                            <i class="fa-sharp fa-regular fa-user" style="color: #1b2c4b;"></i>
                                                <label
                                                    class="form-label"  style="font-weight: bold;">Username
                                                </label>
                                                <input name="uname" type="text" class="form-control"
                                                    placeholder="Enter Username" />
                                            </div>

                                            <div class="form-outline mb-4">
                                                <i class="fa-solid fa-lock"></i> <label class="form-label"
                                                     style="font-weight: bold;">Password</label>
                                                <input name="password" type="password" class="form-control"
                                                    placeholder="Enter Password" />
                                            </div>


                                            <div class="text-center pt-1 mb-5 pb-1">
                                                <button type="submit" name="submit"
                                                    class="button btn text-white  w-100 btn btn-lg"
                                                    style="background: #6BB25A;">Login</button>
                                            </div>


                                        </form>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                                <div class="text-black px-3 py-4 p-md-5 mx-md-4">
                                    <h2 class="mb-4">Empowering Control and
                                        Management</h1>
                                        <p class="medium mb-0">Effortlessly manage and oversee the Arayat Rice
                                            Marketplace with Pale-Ngkihan's secure and intuitive admin login, granting
                                            you full control to streamline operations, monitor transactions, and ensure
                                            a smooth and thriving online trading ecosystem.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Copyright Section-->

    <div class="copyright py-4 text-center text-white d-flex p-2">
        <div class="container"><small>Copyright &copy; Pale-ngkihan 2023</small></div>
    </div>

    <!--Animation java-->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
        const form = document.querySelector('.this form');
        const submitBtn = form.querySelector('.button');
        const errorText = form.querySelector('.error-text');

        form.onsubmit = (e) => {
            e.preventDefault();
        }

        submitBtn.onclick = () => {
            // Start AJAX
            let xhr = new XMLHttpRequest(); // Create XML object
            xhr.open("POST", "../php/adminsignin.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response.trim(); // Trim whitespace from the response
                        let comparisonResult = data === "success";
                        if (comparisonResult) {
                            location.href = "../admin/adminmain.php";
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

    <!--Bootstrap java-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
</body>

</html>