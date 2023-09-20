<?php 
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;



class Email{
    public $email;
    public $nombre; 
    public $token; 
    public function __construct($email, $nombre, $token)
    {
        $this->email = $email; 
        $this->nombre =$nombre; 
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        //Crear objeto de email:
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '9ddd09e406f70f';
        $mail->Password = 'eaba275534f14a';

        $mail->setFrom('correo@correo.com');
        $mail->addAddress('beluuevora@gmail.com');
        $mail->Subject= 'Confirma tu cuenta';

        //Set html:
        $mail->isHTML(TRUE);
        $mail->CharSet= 'UTF-8';
        $contenido = "<html>";

        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salón, solo debes confirmarla haciendo click en el siguiente enlace:</p>";
        $contenido .= "<p>Presiona aquí: <a href='" .  $_ENV['APP_URL']  . "/confirmar-cuenta?token=" . $this->token . "'>Confirmá tu cuenta!</a> </p>";
        $contenido .= "<p> Si no solicitaste la creación de la cuenta, podés ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //enviar el mail:
        $mail->send();

    }

    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('correo@correo.com');
        $mail->addAddress('beluuevora@gmail.com');
        $mail->Subject= 'Reestablecé tu contraseña';

        //Set html:
        $mail->isHTML(TRUE);
        $mail->CharSet= 'UTF-8';
        $contenido = "<html>";

        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> ! Has solicitado reestablecer tu contraseña. Seguí el siguiente enlace para hacerlo:</p>";
        $contenido .= "<p>Presiona aquí: <a href='" .  $_ENV['APP_URL']  .  " /recuperar?token=" . $this->token . "'>Reestablecé tu contraseña</a> </p>";
        $contenido .= "<p> Si no solicitaste este cambio, podés ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->send();
    }
}