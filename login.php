<?php
    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include "database.php";

    $db = new Database();
    $conn = $db->connect();

    //.......session login
    if(isset($_SESSION['id'])){
        if($_SESSION['role']=="admin"){
            header("Location:admin_dashboard.php");
        }else{
            header("Location:dashboard.php");
        }
        exit();
    }

    //...remember me
    if(isset($_COOKIE['remember_token'])){

        $token = $_COOKIE['remember_token'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token=?");
        $stmt->bind_param("s",$token);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows==1){

            $user = $result->fetch_assoc();

            $_SESSION['user'] = $user['name'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if($user['role']=="admin"){
                header("Location:admin_dashboard.php");
            }else{
                header("Location:dashboard.php");
            }
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">
                            <i class="bi bi-person"></i> Login
                        </h3>

                        <form id="loginForm">
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

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                                <a href="forgot_password.php" class="text-decoration-none">
                                    Forgot Password?
                                </a>
                            </div>

                            <div class="d-grid mb-3">
                                <button class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </button>
                            </div>

                            <div class="text-center mb-1">
                                Don't have an account?
                                <a href="index.php" class="text-decoration-none">Register</a>
                            </div>
                            <!-- <div class="mb-3 text-center" id="msg"></div> -->

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