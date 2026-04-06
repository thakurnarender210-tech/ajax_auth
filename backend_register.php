<?php
    session_start();

    include "database.php";
    include "user.php";
    include "sendmail.php";

    $db = new Database();
    $conn = $db->connect();

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $mobile = $_POST['mobile'] ?? '';

        // Validation
        if(empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($mobile)){
            echo "<div class='alert alert-danger'>All fields are required.</div>";
            exit;
        }
        if(strlen($password) < 8){
            echo "<div class='alert alert-warning'>Password must be at least 8 characters</div>";
            exit;
        }
        if(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/',$password)){
            echo "<div class='alert alert-warning'>Password must contain uppercase, lowercase, number and special character.</div>";
            exit;
        }
        if($password != $confirm_password){
            echo "<div class='alert alert-warning'>Password not Match</div>";
            exit;
        }

        //......image upload

        $imageName = "";
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
            $allowed = ['jpg','jpeg','png'];
            $ext = strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));

            if(!in_array($ext,$allowed)){
                echo "<div class='alert alert-danger'>Only JPG, JPEG, PNG allowed</div>";
                exit;
            }
            $imageName = time().'_'.basename($_FILES['image']['name']);
            if(!is_dir("uploads")){
                mkdir("uploads");
            }
            move_uploaded_file($_FILES['image']['tmp_name'],"uploads/".$imageName);
        }

        //.......register

        if(!preg_match('/^[6-9][0-9]{9}$/', $mobile)){
            echo "<div class='alert alert-danger'>Please enter a valid number</div>";
            exit();
        }

        $user = new User($conn);
        $result = $user->register($name,$email,$password,$mobile,$imageName,"user");

        if($result === "email Exist"){
            echo "email Exist";
        }
        elseif($result){
            $_SESSION['id'] = $result;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = "user";

            /* SEND EMAIL */
            $message = '
                <div style="font-family:Arial;background:#f4f6f9;padding:20px">
                    <div style="max-width:600px;margin:auto;background:white;border-radius:8px;padding:30px">
                        <div style="text-align:center">
                            <img src="cid:logo" width="120">
                        </div>
                        <h2 style="text-align:center;color:#333">
                            Welcome '.$name.'
                        </h2>

                        <p style="font-size:16px;color:#555;text-align:center">
                            Your account has been created successfully.
                        </p>

                        <div style="background:#f8f9fa;padding:15px;border-radius:6px;margin-top:20px">
                            <b>Email:</b> '.$email.' <br>
                            <b>Mobile:</b> '.$mobile.'
                        </div>

                        <p style="margin-top:25px;text-align:center;color:#777">
                            Thank you for joining <b>AJAX AUTH</b>.
                        </p>

                        <div style="text-align:center;margin-top:20px">
                            <a href="http://localhost/ajax_auth/login.php" 
                                style="background:#0d6efd;color:white;padding:10px 20px;text-decoration:none;border-radius:5px">
                                Login Now
                            </a>
                        </div>
                    </div>
                </div>
            ';
            sendMail(
                $email,
                "Registration Successful",
                $message,
                [
                    __DIR__."/attachments/welcome.pdf",
                    __DIR__."/attachments/image.png"
                ]
            );
            echo "success";
        }
        else{
            echo "error";
        }
    }
?>