<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require dirname(__DIR__) . '/vendor/autoload.php';

class SendMail{
    function sendOneMail($destinataire, $objet, $message){
        try{
            if ($destinataire == '') {
                return 'Destinataire non défini';
            } elseif ($objet == ''){
                return 'Objet du mail non défini';
            }else if ($message == ''){
                return 'Message non défini';
            }

            $mail = new PHPMailer(TRUE);

            $mail->Helo = 'paulin-buland.fr'; //Nécessaire pour envoyer des mails
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'smtp.online.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'dev@paulin-buland.fr';
            $mail->Password = 'LoftCube2022';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('dev@paulin-buland.fr', 'LoftCube');
            $mail->addAddress($destinataire);

            $mail->Subject = $objet;

            $mail->isHTML(true);

            $mailContent = $message;
            $mail->Body = $mailContent;

            if($mail->send()){
                return 'Le mail a bien été envoyé';
            }else {
                return 'Une erreur est survenue pendant l\'envoi du mail : ' . $mail->ErrorInfo;
            }
        } catch(\Exception $e){
            return "Une erreur est survenue : $e";

        }
    }
}
