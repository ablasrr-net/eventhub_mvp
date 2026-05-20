<?php

use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

function sendConfirmation($pdo,$userId,$eventId,$token) {

    $user = $pdo->query("
    SELECT * FROM users WHERE id = $userId
    ")->fetch();

    $event = $pdo->query("
    SELECT * FROM events WHERE id = $eventId
    ")->fetch();

    $html = file_get_contents(
        __DIR__ . '/templates/confirmation.html'
    );

    $html = str_replace('{{name}}',$user['fullname'],$html);
    $html = str_replace('{{event}}',$event['title'],$html);
    $html = str_replace('{{date}}',$event['event_date'],$html);
    $html = str_replace('{{location}}',$event['location'],$html);

    $unsubscribe =
    "http://localhost/eventhub-pro/events/unregister.php?token=$token";

    $html = str_replace('{{unsubscribe}}',$unsubscribe,$html);

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'yourmail@gmail.com';
        $mail->Password = 'APP_PASSWORD';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('yourmail@gmail.com','EventHub Pro');

        $mail->addAddress($user['email']);

        $mail->isHTML(true);

        $mail->Subject = 'Confirmation inscription';

        $mail->Body = $html;

        $mail->send();

    } catch(Exception $e) {

        file_put_contents(
            '../logs/mail_errors.log',
            $e->getMessage(),
            FILE_APPEND
        );
    }
}