<?php

require_once '../config/db.php';

header('Content-Type: application/json');

$stats = [];

$stats['top_events'] = $pdo->query("
SELECT title, registered_count
FROM events
ORDER BY registered_count DESC
LIMIT 3
")->fetchAll();

$stats['last_registrations'] = $pdo->query("
SELECT COUNT(*) as total
FROM registrations
WHERE registered_at >= NOW() - INTERVAL 1 DAY
")->fetch();

echo json_encode($stats);