<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'db6fc84d367982';
        $mail->Password = '70eb7a429660a8';

        // Configuración del correo
        $mail->setFrom('alvaxG@example.com');
        $mail->addAddress('alvaxG@example.com', 'BienesRaices.com');
        $mail->Subject = 'Confirma tu cuenta';


        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';


        $contenido = "<html>";
        $contenido = "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";

        $mail->Body = $contenido;


        // Enviar el mail
        $mail->send();
    }

    public function enviarInstrucciones()
    {
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'db6fc84d367982';
        $mail->Password = '70eb7a429660a8';

        // Configuración del correo
        $mail->setFrom('alvaxG@example.com');
        $mail->addAddress('alvaxG@example.com', 'BienesRaices.com');
        $mail->Subject = 'Reestablece tu password';


        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';


        $contenido = "<html>";
        $contenido = "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado restablecer tu password, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Restablecer Password</a></p>";
        $contenido .= "<p>Si tú no solicitaste este cambio, puedes ignorar el mensaje</p>";

        $mail->Body = $contenido;


        // Enviar el mail
        $mail->send();
    }
}
