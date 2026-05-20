<?php

header('Content-Type: application/json');

require_once '../config/db.php';

/*
|--------------------------------------------------------------------------
| Désactiver temporairement les mails
|--------------------------------------------------------------------------
| Décommente ces lignes après installation de Composer + PHPMailer
|
| require_once '../mail/SendConfirmation.php';
| require_once '../mail/AlertMailer.php';
|
*/

try {

    /*
    |--------------------------------------------------------------------------
    | Vérification des données POST
    |--------------------------------------------------------------------------
    */

    if(
        !isset($_POST['user_id']) ||
        !isset($_POST['event_id'])
    ){
        throw new Exception("Données manquantes");
    }

    /*
    |--------------------------------------------------------------------------
    | Sécurisation des données
    |--------------------------------------------------------------------------
    */

    $userId = (int) $_POST['user_id'];
    $eventId = (int) $_POST['event_id'];

    /*
    |--------------------------------------------------------------------------
    | Vérifie si utilisateur déjà inscrit
    |--------------------------------------------------------------------------
    */

    $check = $pdo->prepare("
        SELECT id
        FROM registrations
        WHERE user_id = ?
        AND event_id = ?
    ");

    $check->execute([$userId, $eventId]);

    if($check->fetch()){

        echo json_encode([
            'success' => false,
            'message' => 'Utilisateur déjà inscrit'
        ]);

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Génération token
    |--------------------------------------------------------------------------
    */

    $token = bin2hex(random_bytes(32));

    /*
    |--------------------------------------------------------------------------
    | Insertion inscription
    |--------------------------------------------------------------------------
    */

    $sql = "
        INSERT INTO registrations(
            user_id,
            event_id,
            token
        )
        VALUES(?,?,?)
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $userId,
        $eventId,
        $token
    ]);

    /*
    |--------------------------------------------------------------------------
    | Mise à jour compteur
    |--------------------------------------------------------------------------
    */

    $update = $pdo->prepare("
        UPDATE events
        SET registered_count = registered_count + 1
        WHERE id = ?
    ");

    $update->execute([$eventId]);

    /*
    |--------------------------------------------------------------------------
    | Récupération événement
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT *
        FROM events
        WHERE id = ?
    ");

    $stmt->execute([$eventId]);

    $event = $stmt->fetch();

    if(!$event){

        throw new Exception("Événement introuvable");
    }

    /*
    |--------------------------------------------------------------------------
    | Calcul remplissage
    |--------------------------------------------------------------------------
    */

    $percentage =
    ($event['registered_count'] / $event['capacity']) * 100;

    /*
    |--------------------------------------------------------------------------
    | Alertes capacité
    |--------------------------------------------------------------------------
    */

    if(
        $percentage >= 80 &&
        !$event['alert_sent']
    ){

        /*
        |--------------------------------------------------------------------------
        | Décommente après activation mails
        |--------------------------------------------------------------------------
        |
        | sendCapacityAlert($pdo,$eventId);
        |
        */

        $pdo->prepare("
            UPDATE events
            SET alert_sent = 1
            WHERE id = ?
        ")->execute([$eventId]);
    }

    /*
    |--------------------------------------------------------------------------
    | Email confirmation
    |--------------------------------------------------------------------------
    |
    | Décommente après installation PHPMailer
    |
    | sendConfirmation($pdo,$userId,$eventId,$token);
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Réponse succès
    |--------------------------------------------------------------------------
    */

    echo json_encode([
        'success' => true,
        'message' => 'Inscription réussie'
    ]);

} catch(Exception $e){

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>