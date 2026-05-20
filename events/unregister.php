<?php

require_once '../config/db.php';

$token = $_GET['token'];

$registration = $pdo->prepare("
SELECT *
FROM registrations
WHERE token = ?
");

$registration->execute([$token]);

$data = $registration->fetch();

if($data) {

    $pdo->prepare("
    DELETE FROM registrations
    WHERE id = ?
    ")->execute([$data['id']]);

    $pdo->prepare("
    UPDATE events
    SET registered_count = registered_count - 1
    WHERE id = ?
    ")->execute([$data['event_id']]);

    echo "Désinscription effectuée";
}
?>