 <?php
    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    if(isset($_SESSION['id'])){
        if($_SESSION['role']=="admin"){
            header("Location: admin_dashboard.php");
        }else{
            header("Location: dashboard.php");
        }
        exit();
    }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">
                            <i class="bi bi-person-add"></i> Create Account
                        </h3>
                        <form id="registerForm" enctype="multipart/form-data">
                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control" placeholder="Name" oninput="this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                <label><i class="bi bi-person"></i> Name</label>
                                <div class="text-danger small" id="name_error"></div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email">
                                <label><i class="bi bi-envelope"></i> Email</label>
                                <div class="text-danger small" id="email_error"></div>
                            </div>

                            <div class="mb-3 position-relative">
                                <div class="form-floating">
                                    <input type="password" class="form-control" name="password"
                                        id="password" placeholder="Password">
                                    <label><i class="bi bi-lock"></i> Password</label>
                                </div>
                                <i class="bi bi-eye togglePassword"
                                style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer;"></i>
                                <div class="text-danger small mt-1" id="confirm_error"></div>
                            </div>
                            <div class="mb-3 position-relative">
                                <div class="form-floating">
                                    <input type="password" class="form-control" name="confirm_password"
                                        id="confirm_password" placeholder="Password">
                                    <label><i class="bi bi-lock"></i> Confirm Password</label>
                                </div>
                                <i class="bi bi-eye togglePassword"
                                style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer;"></i>
                                <div class="text-danger small mt-1" id="confirm_error"></div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="tel" id="mobile" name="mobile" class="form-control" maxlength="10" placeholder="Mobile">
                                <label><i class="bi bi-phone"></i> Mobile</label>
                                <div class="text-danger small" id="mobile_error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-image"></i> Upload Image
                                </label>
                                <input type="file" name="image" class="form-control">
                            </div>

                            <div class="d-grid mb-3">
                                <button id="registerBtn" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Register
                                </button>
                            </div>

                            <div class="text-center">
                                Already have an account?
                                <a href="login.php" class="text-decoration-none">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/app.js"></script>

</body>
</html>
