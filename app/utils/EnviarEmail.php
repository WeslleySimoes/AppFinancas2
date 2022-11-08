<?php 

namespace app\utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class EnviarEmail{

    private $host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    private $Username   = 'weslleyFinancastcc@gmail.com';                     //SMTP username
    private $Password   = '#';                               //SMTP password

    public $EmailDestinatario = '';

    public $Subject = 'Alteracao de senha - Finanças pessoais';
    public $body    = '<h3><b>Clique no link abaixo para alterar sua senha: </br></br> <a href="https://www.google.com/">Teste</a></br></br>';

    public function enviar()
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = 'UTF-8';
            //Server settings
           // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $this->host;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $this->Username;                     //SMTP username
            $mail->Password   = $this->Password;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($this->Username, 'Alteracao de senha - Finanças pessoais');
            
            $mail->addAddress($this->EmailDestinatario, '');     //Add a recipient

            $mail->addReplyTo($this->Username, 'Information');

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $this->Subject;
            $mail->Body    = $this->body;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}