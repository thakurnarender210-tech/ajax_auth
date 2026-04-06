<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <i class="bi bi-envelope-lock display-4 text-primary"></i>
                    </div>
                    <h4 class="mb-3">Forgot Password</h4>
                    <form id="forgotForm">
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" >
                            <label><i class="bi bi-envelope"></i>  Email Address</label>
                            <div class="text-danger small" id="email_error"></div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn btn-primary btn-lg">
                                <i class="bi bi-send"></i>  Send OTP
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="login.php" class="text-decoration-none">
                            Back to Login
                            </a>
                        </div>
                    </form>
                    <div id="msg" class="mt-3"></div>

                        <!-- ......otp.......  -->
                    <div id="otpBox" style="display:none">
                        <div class="form-floating mt-3">
                            <input type="text" id="otp" class="form-control" placeholder="Enter OTP" maxlength="6">
                            <label>Enter OTP</label>
                            <div class="text-danger small" id="otp_error"></div>
                        </div>
                        <button id="verifyOtp" class="btn btn-success mt-3 w-100">
                            <i class="bi bi-patch-check"></i>  Verify OTP
                        </button>
                        <div class="text-center mt-2">
                            <button id="resendOtp" class="btn btn-link text-decoration-none">
                                Resend OTP
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/app.js"></script>
    
</body>
</html>