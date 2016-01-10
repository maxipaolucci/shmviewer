<?php

include($path . '/includes/lib/class.smtp.php');
include($path . '/includes/lib/class.phpmailer.php');
include($path . '/includes/lib/PHPMailerAutoload.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mail
 *
 * @author maxi
 */
class Mail {
  private static $instance = null;
  private $mail = null;

  private function __construct() {
    $this->mail = new PHPMailer(true);
    $this->mail->IsSMTP(); // telling the class to use SMTP
    $this->mail->SMTPAuth = true; // enable SMTP authentication
    $this->mail->SMTPSecure = "ssl"; // sets the prefix to the servier
    $this->mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
    $this->mail->Port = 465; // set the SMTP port for the GMAIL server
    $this->mail->Username = "maxinoesta@gmail.com"; // GMAIL username
    $this->mail->Password = "mapaxipi"; // GMAIL password

    //Typical mail data
    $this->mail->AddAddress("maxipaolucci@gmail.com", "Maxi Paolucci");
    $this->mail->SetFrom($this->mail->Username, "SHM VIEWER");
  }
  
  public static function getInstance() {
    if (empty(self::$instance)) {
      self::$instance = new Mail();
    }
    return self::$instance;
  }
  
  public function send($subject, $body) {
    $this->mail->Subject = $subject . " <" . time() . ">"; //added time to get different 
                    //subjects and avoid gmail to groups all the mails under the same thread
    $this->mail->Body = $body;

    try{
        $this->mail->Send();
    } catch(Exception $e){
        //Something went bad
        echo "Fail - " . $this->mail->ErrorInfo . $this->mail->body;
    }
  }
  
  
}

?>
