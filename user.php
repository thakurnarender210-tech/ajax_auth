<?php
    include_once "database.php";

    class User 
    {
        private $conn;
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // ..........register 
        public function register($name,$email,$password,$mobile,$imageName,$role="user")
        {
            if(empty($name) || empty($email) || empty($password) || empty($mobile)){
                return '<div class="alert alert-danger">All fields are required</div>';
            }

            //.........check email exist
            $check = $this->conn->prepare("SELECT id FROM users WHERE email=?");
            $check->bind_param("s",$email);
            $check->execute();
            $check->store_result();

            if($check->num_rows > 0){
                return 'email Exist';
                exit;
            }

            //.......... md5 password
            $password = md5($password);

            $sql = $this->conn->prepare("INSERT INTO users (name,email,password,mobile,image,role)
            VALUES (?,?,?,?,?,?)");

            $sql->bind_param("ssssss",$name,$email,$password,$mobile,$imageName,$role);

            if($sql->execute()){
                return $this->conn->insert_id;
            }else{
                return false;
            }
        }

        // ..........login

        public function login($email,$password)
        {
            if(empty($email) || empty($password)){
                return '<div class="alert alert-danger">All fields are required</div>';
            }
            $password = md5($password);

            $sql = $this->conn->prepare("SELECT id,name,role FROM users WHERE email=? AND password=?");

            $sql->bind_param("ss",$email,$password);
            $sql->execute();

            $result = $sql->get_result();

            if($result->num_rows == 1){
                return $result->fetch_assoc();
            }else{
                return '<div class="alert alert-danger">Invalid email or password</div>';
            }
        }

        //.........remember me
        public function setRememberToken($id,$token)
        {
            $sql = $this->conn->prepare("UPDATE users SET remember_token=? WHERE id=?");
            $sql->bind_param("si",$token,$id);

            return $sql->execute();
        }

        //........get all users

        public function getAllUsers()
        {
            $sql = $this->conn->prepare("SELECT * FROM users");
            $sql->execute();

            return $sql->get_result();
        }

        // .......delete user

        public function deleteUser($id)
        {
            $sql = $this->conn->prepare("DELETE FROM users WHERE id=? AND role!='admin'");
            $sql->bind_param("i",$id);

            return $sql->execute();
        }

        //.......... send otp

        public function sendOTP($email)
        {
            if(empty($email)){
                return "Email required";
            }
            $otp = rand(100000,999999);

            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            $subject = "Email OTP Verification";
            $message = "Your OTP is: ".$otp;
            $headers = "From: noreply@test.com";

            if(mail($email,$subject,$message,$headers)){
                return "OTP sent successfully";
            }else{
                return "Failed to send OTP";
            }
        }
    }
?>