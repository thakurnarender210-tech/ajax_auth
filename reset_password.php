<?php
    include "database.php";

    $conn = (new Database())->connect();

    if(!isset($_GET['email'])){
        die("Invalid request");
    }

    $email = $_GET['email'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows == 0){
        die("User not found");
    }
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    $message = "";

    if(isset($_POST['password']) && isset($_POST['confirm_password'])){
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if(empty($password) || empty($confirm_password)){
            $message = '<div class="alert alert-danger">All fields are required</div>';
        }
        elseif($password !== $confirm_password){
            $message = '<div class="alert alert-danger">Passwords do not match</div>';
        }
        elseif(strlen($password) < 8){
            $message = "<div class='alert alert-warning'>Password must be at least 8 characters</div>";
        }
        elseif(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/',$password)){
            $message = "<div class='alert alert-warning'>Password must contain uppercase, lowercase, number and special character</div>";
        }else{
                $password = md5($password);
                $stmt = $conn->prepare("UPDATE users SET password=?, otp=NULL, otp_expire=NULL WHERE id=?");
                $stmt->bind_param("si",$password,$user_id);
                if($stmt->execute()){
                    $message = '<div class="alert alert-success">Password updated successfully</div>';
                }else{
                    $message = '<div class="alert alert-danger">Something went wrong</div>';
                }
        }
    }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">
<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4 text-center">
                    <h4 class="mb-3">Reset Password</h4>

                    <p class="text-muted mb-4">
                        Enter your new password
                    </p>
                    <?php echo $message; ?>
                    <form method="POST" id="resetPassword">
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

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Reset Password
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="login.php" class="text-decoration-none">
                                Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/app.js"></script>

</body>
</html>