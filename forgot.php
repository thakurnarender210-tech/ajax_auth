<?php

    include "database.php";

    $conn = (new Database())->connect();

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $email = trim($_POST['email']);
        if(empty($email)){
            echo '<div class="alert alert-danger">Please Enter Email</div>';
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows == 0){
            echo '<div class="alert alert-danger">Email not found</div>';
            exit;
        }

        //.......generate otp
        $otp = random_int(100000,999999);

        date_default_timezone_set("Asia/Kolkata");
        $expire = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $stmt = $conn->prepare("UPDATE users SET otp=?, otp_expire=? WHERE email=?");
        $stmt->bind_param("sss",$otp,$expire,$email);
        
        if(!$stmt->execute()){
            echo '<div class="alert alert-danger">Something went wrong</div>';
            exit;
        }

        require_once "sendmail.php";

        $message = "Your OTP for password reset is: ".$otp;
        if(sendMail($email,"Password Reset OTP",$message)){
            echo '<div class="alert alert-success"><i class="bi bi-check-circle"></i> OTP sent to your email</div>';
        }else{
            echo '<div class="alert alert-danger">Failed to send email</div>';
        }
    }
?>