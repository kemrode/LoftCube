<?php

namespace Core;

use phpDocumentor\Reflection\Types\Boolean;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require dirname(__DIR__) . '/vendor/autoload.php';

class SendMail{
    public static function sendOneMail($destinataire, $objet, $message) : string{
        try{
            if ($destinataire == '') {
                return 'Erreur : Destinataire non défini';
            } elseif ($objet == ''){
                return 'Erreur : Objet du mail non défini';
            }else if ($message == ''){
                return 'Erreur : Message non défini';
            }

            $mail = new PHPMailer(TRUE);

            $mail->Helo = 'paulin-buland.fr'; //Nécessaire pour envoyer des mails
            $mail->CharSet = 'UTF-8';
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
                return 'Erreur : Une erreur est survenue pendant l\'envoi du mail : ' . $mail->ErrorInfo;
            }
        } catch(\Exception $e){
            return "Erreur : $e";

        }
    }
}
