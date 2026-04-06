<?php
    session_start();
    include "database.php";

    $conn = (new Database())->connect();
    $id = $_POST['id'] ?? '';

    if(empty($id)){
        echo "invalid";
        exit;
    }

    //.....check user exist
    $stmt = $conn->prepare("SELECT id FROM users WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows == 0){
        echo "not_found";
        exit;
    }

    //.....delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i",$id);
    if($stmt->execute()){
        echo "success";
    }else{
        echo "error";
    }
?>