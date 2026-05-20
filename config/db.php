<?php

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3307');

define('DB_NAME', 'eventhub_pro');
define('DB_USER', 'root');
define('DB_PASS', '');

try {

    $pdo = new PDO(
        "mysql:host=" . DB_HOST .
        ";port=" . DB_PORT .
        ";dbname=" . DB_NAME .
        ";charset=utf8mb4",

        DB_USER,
        DB_PASS
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch(PDOException $e) {

    die("Erreur connexion : " . $e->getMessage());
}
?>