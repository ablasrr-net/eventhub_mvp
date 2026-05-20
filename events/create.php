<?php

require_once '../config/db.php';

function createEvent($pdo, $data) {

    /*
    Correction 1 :
    Utilisation des requêtes préparées
    => protection contre SQL Injection
    */

    $sql = "
        INSERT INTO events(
            title,
            description,
            location,
            category_id,
            organizer_id,
            event_date,
            capacity
        )
        VALUES(
            :title,
            :description,
            :location,
            :category_id,
            :organizer_id,
            :event_date,
            :capacity
        )
    ";

    $stmt = $pdo->prepare($sql);

    /*
    Correction 2 :
    execute() retourne true/false réel
    */

    return $stmt->execute([

        'title' => $data['title'],
        'description' => $data['description'],
        'location' => $data['location'],
        'category_id' => $data['category_id'],
        'organizer_id' => $data['organizer_id'],
        'event_date' => $data['event_date'],
        'capacity' => $data['capacity']
    ]);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $success = createEvent($pdo, $_POST);

    if($success) {

        echo "Événement créé";
    }
}
?>