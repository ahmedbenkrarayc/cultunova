<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../env.php';

class Mailer{
    private $username;
    private $to;
    private $subject;
    private $body;

    public function __construct($username, $to, $subject, $body){
        $this->username = $username;
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function send(){
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();                                             
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                     
            $mail->Username   = $GLOBALS['mail'];                 
            $mail->Password   = $GLOBALS['mailPass'];                    
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           
            $mail->Port       = 587;                                      
        
            $mail->setFrom("ahmed.benkrara12@gmail.com", 'cultunova');
            $mail->addAddress($this->to, $this->username);
        
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $this->body;
        
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
}