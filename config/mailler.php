<?php
// ============================================================
// config/mailer.php — Configuration PHPMailer centralisée
// ============================================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Variables manquantes complétées :
define('MAIL_HOST',       'smtp.gmail.com');       // Serveur SMTP
define('MAIL_USERNAME',   'no-reply@eventhub.ma'); // Adresse expéditeur
define('MAIL_PASSWORD',   'votre_mot_de_passe');   // Mot de passe SMTP / App Password
define('MAIL_PORT',       587);                     // Port TLS
define('MAIL_FROM_NAME',  'EventHub Pro');
define('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);

/**
 * Crée et retourne une instance PHPMailer pré-configurée.
 * Factoriser la configuration évite la duplication dans chaque fichier.
 */
function createMailer(): PHPMailer
{
    $mail = new PHPMailer(true); // true = activer les exceptions

    $mail->isSMTP();
    $mail->Host       = MAIL_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = MAIL_USERNAME;
    $mail->Password   = MAIL_PASSWORD;
    $mail->SMTPSecure = MAIL_ENCRYPTION;
    $mail->Port       = MAIL_PORT;
    $mail->CharSet    = 'UTF-8';
    $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);

    return $mail;
}