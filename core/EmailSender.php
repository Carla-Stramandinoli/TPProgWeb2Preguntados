<?php

// Defino los namespaces necesarios
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailSender
{

    public function send($usuario, $body)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->SMTPDebug = 0; // o SMTP::DEBUG_OFF
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'validadorpreguntatres@gmail.com';                     //SMTP username
            $mail->Password   = 'fkvy mblz hzfd nfwu';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('validadorpreguntatres@gmail.com', 'Preguntatres');
            $mail->addAddress($usuario['email'], $usuario['nombre_completo']);     //Add a recipient


            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Validar Cuenta Preguntatres';
            $mail->Body    = $body;

            $mail->send();
        } catch (Exception $e) {
            echo "El mail no pudo ser enviado: {$mail->ErrorInfo}";
        }
    }
}