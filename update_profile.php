<?php
    session_start();

    include "database.php";

    if(!isset($_SESSION['id'])){
        exit("Unauthorized");
    }

    $db = new Database();
    $conn = $db->connect();

    $id = $_SESSION['id'];
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $imageName = "";

    if(empty($name) || empty($email) || empty($password) || empty($mobile) ){
        echo "<div class='alert alert-danger'>Please enter proper details...</div>";
        exit;
    }

    // ....obile validation
    if(!preg_match('/^[6-9][0-9]{9}$/', $mobile)){
        echo "<div class='alert alert-danger'>Please enter a valid number</div>";
        exit();
    }

    //.....image 
    $stmt = $conn->prepare("SELECT image FROM users WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $oldImage = $user['image'] ?? "";

    //.......image upload

    $password = md5($password);
    if(isset($_FILES['image']) && $_FILES['image']['error']==0){
        $allowed = ['jpg','jpeg','png'];

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if(!in_array($ext,$allowed)){
            echo "<div class='alert alert-danger'>Only JPG, JPEG, PNG allowed</div>";
            exit;
        }

        $imageName = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'],"uploads/".$imageName);

        //.....delete old image

        if(!empty($oldImage) && file_exists("uploads/".$oldImage)){
            unlink("uploads/".$oldImage);
        }

        $sql = $conn->prepare("UPDATE users SET name=?, email=?, password=?, mobile=?, image=? WHERE id=?");
        $sql->bind_param("sssssi",$name,$email,$password,$mobile,$imageName,$id);
    }else{
        $sql = $conn->prepare("UPDATE users SET name=?, email=?, password=?, mobile=? WHERE id=?");
        $sql->bind_param("ssssi",$name,$email,$password,$mobile,$id);
    }

    if($sql->execute()){
        $_SESSION['user'] = $name;
        echo "<div class='alert alert-success'>Profile Updated Successfully</div>";
    }else{
        echo "<div class='alert alert-danger'>Update Failed</div>";
    }
?>