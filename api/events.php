<?php

require_once '../config/db.php';

function searchEvents($pdo, $filters = []) {

    /*
    Construction dynamique sécurisée.
    On ajoute uniquement les conditions nécessaires.
    Toutes les valeurs passent par PDO préparé.
    */

    $sql = "
        SELECT events.*, categories.name as category
        FROM events
        JOIN categories ON categories.id = events.category_id
        WHERE 1=1
    ";

    $params = [];

    if(!empty($filters['keyword'])) {

        $sql .= " AND title LIKE :keyword";
        $params['keyword'] = "%" . $filters['keyword'] . "%";
    }

    if(!empty($filters['category'])) {

        $sql .= " AND category_id = :category";
        $params['category'] = $filters['category'];
    }

    if(!empty($filters['date_from'])) {

        $sql .= " AND event_date >= :date_from";
        $params['date_from'] = $filters['date_from'];
    }

    if(!empty($filters['date_to'])) {

        $sql .= " AND event_date <= :date_to";
        $params['date_to'] = $filters['date_to'];
    }

    if(!empty($filters['available'])) {

        $sql .= " AND registered_count < capacity";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

$filters = $_GET;

$events = searchEvents($pdo, $filters);

header('Content-Type: application/json');

echo json_encode($events);