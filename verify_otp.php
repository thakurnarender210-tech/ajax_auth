<?php

    include "database.php";
    $conn = (new Database())->connect();

    $email = $_POST['email'];
    $otp = $_POST['otp'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? AND otp=? AND otp_expire > NOW()");
    $stmt->bind_param("ss",$email,$otp);
    $stmt->execute();

    $result = $stmt->get_result();

    if(empty($otp)){
        echo '<div class="alert alert-danger">Please Enter Otp</div>';
        exit;
    }
    if($result->num_rows == 1){
        echo "success";
    }else{
        echo '<div class="alert alert-danger">Invalid or expired OTP</div>';
    }

?>