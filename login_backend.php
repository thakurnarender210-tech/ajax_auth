<?php
    session_start();

    include "database.php";
    include "user.php";

    $db = new Database();
    $conn = $db->connect();

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $remember = isset($_POST['remember']);

        if(empty($email) || empty($password)){
            echo '<div class="alert alert-danger">All fields are required</div>';
            exit;
        }

        $user = new User($conn);
        $result = $user->login($email,$password);
        if(is_array($result)){
            $_SESSION['user'] = $result['name'];
            $_SESSION['id'] = $result['id'];
            $_SESSION['role'] = $result['role'];

            if($remember){
                $token = bin2hex(random_bytes(32));
                $user->setRememberToken($result['id'],$token);
                setcookie(
                    "remember_token",
                    $token,
                    time() + (86400 * 30), // 30 days
                    "/",
                    "",
                    false,
                    true
                );
            }else{
                setcookie("remember_token","",time()-3600,"/");
            }

            //......role response
            if($result['role'] == "admin"){
                echo "admin";
            }else{
                echo "user";
            }
        }else{
            echo $result;
        }
    }
?>