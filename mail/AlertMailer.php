<?php

use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

function sendCapacityAlert($pdo,$eventId) {

    require_once '../pdf/report.php';

    $event = $pdo->query("
    SELECT events.*, users.email
    FROM events
    JOIN users ON users.id = events.organizer_id
    WHERE events.id = $eventId
    ")->fetch();

    $pdfPath = generateReport($pdo,$eventId);

    $mail = new PHPMailer(true);

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'yourmail@gmail.com';

    $mail->Password = 'APP_PASSWORD';

    $mail->SMTPSecure = 'tls';

    $mail->Port = 587;

    $mail->setFrom('yourmail@gmail.com');

    $mail->addAddress($event['email']);

    $mail->Subject = 'Alerte capacité';

    $mail->Body = 'Votre événement atteint 80%';

    $mail->addAttachment($pdfPath);

    $mail->send();
}