<?php
    session_start();

    if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
        exit();
    }
    include "database.php";
    include "user.php";

    $db = new Database();
    $conn = $db->connect();
    $user = new User($conn);

    if(isset($_POST['id'])){
        $id = $_POST['id'];
        if($user->deleteUser($id)){
            echo "success";
        }else{
            echo "error";
        }
    }
?>