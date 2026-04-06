<?php
    require 'vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function sendMail($email,$subject,$message,$attachments=[]){
        $mail = new PHPMailer(true);
        try{
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "thakurhoney302@gmail.com";
            $mail->Password = "rmjficiyccthyeir";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            $mail->setFrom("thakurhoney302@gmail.com","AJAX AUTH");
            $mail->addAddress($email);

            /* ATTACHMENT */
            if(!empty($attachments)){
                foreach($attachments as $file){
                    if(file_exists($file)){
                        $mail->addAttachment($file);
                    }
                }
            }
            if(!empty($showLogo)){
                $mail->addEmbeddedImage(__DIR__.'/attachments/image.png', 'logo');
            }
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();
            return true;
        }catch(Exception $e){
            return false;
        }
    }
?>