<?php
    include "sendmail.php";

    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $attachments = [];

    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
        $path = 'uploads/'.time().'_'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'],$path);
        $attachments[]=$path;

        sendMail($to,$subject,$message,$attachments);
        echo "Email sent Successfully";
    }
?>