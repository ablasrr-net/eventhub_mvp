<?php
require_once 'config/db.php';

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EventHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h1 class="title">Événements Disponibles</h1>

    <div class="events-grid">

        <?php foreach($events as $event): ?>

            <?php

            $percentage = ($event['registered'] / $event['capacity']) * 100;
            $remaining = $event['capacity'] - $event['registered'];

            $categoryClass = strtolower($event['category']);

            if($remaining <= 0){
                $status = "COMPLET";
                $statusClass = "status-full";
                $progressClass = "progress-full";
                $buttonClass = "btn-disabled";
            }
            elseif($remaining <= 5){
                $status = "QUASI PLEIN";
                $statusClass = "status-warning";
                $progressClass = "progress-warning";
                $buttonClass = "btn-orange";
            }
            else{
                $status = "";
                $statusClass = "";
                $progressClass = "progress-tech";
                $buttonClass = "btn-green";
            }

            ?>

            <div class="event-card <?= $categoryClass ?>">

                <div class="card-header">

                    <span class="badge badge-<?= $categoryClass ?>">
                        <?= strtoupper($event['category']) ?>
                    </span>

                    <?php if($status): ?>
                        <span class="status <?= $statusClass ?>">
                            <?= $status ?>
                        </span>
                    <?php endif; ?>

                </div>

                <h2 class="event-title">
                    <?= htmlspecialchars($event['title']) ?>
                </h2>

                <div class="event-info">
                    📅 <?= date('D d M, H:i', strtotime($event['event_date'])) ?>
                </div>

                <div class="event-info">
                    📍 <?= htmlspecialchars($event['location']) ?>
                </div>

                <p class="description">
                    <?= htmlspecialchars($event['description']) ?>
                </p>

                <div class="capacity">

                    <div class="capacity-header">
                        <span>Capacité</span>
                        <span>
                            <?= $event['registered'] ?> / <?= $event['capacity'] ?>
                        </span>
                    </div>

                    <div class="progress-bar">
                        <div class="progress <?= $progressClass ?>"
                             style="width: <?= $percentage ?>%">
                        </div>
                    </div>

                    <div class="remaining">
                        <?= $remaining ?> places restantes
                    </div>

                </div>

                <?php if($remaining > 0): ?>

                    <a href="events/register.php?id=<?= $event['id'] ?>"
                       class="btn <?= $buttonClass ?>">
                        S'inscrire →
                    </a>

                <?php else: ?>

                    <button class="btn btn-disabled">
                        Complet
                    </button>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

</div>

</body>
</html>