<?php

require_once '../vendor/autoload.php';

use TCPDF;

function generateReport($pdo,$eventId) {

    $event = $pdo->query("
    SELECT *
    FROM events
    WHERE id = $eventId
    ")->fetch();

    $registrations = $pdo->query("
    SELECT users.fullname, users.email
    FROM registrations
    JOIN users ON users.id = registrations.user_id
    WHERE registrations.event_id = $eventId
    ORDER BY users.fullname ASC
    ")->fetchAll();

    $pdf = new TCPDF();

    /*
    PAGE 1
    */

    $pdf->AddPage();

    $pdf->SetFont('helvetica','B',18);

    $pdf->Cell(0,10,'Rapport Organisateur',0,1);

    $pdf->SetFont('helvetica','',12);

    $pdf->Cell(0,10,'Event : '.$event['title'],0,1);

    $fillRate =
    ($event['registered_count'] / $event['capacity']) * 100;

    $pdf->Cell(0,10,'Taux : '.$fillRate.'%',0,1);

    /*
    PAGE 2
    */

    $pdf->AddPage();

    foreach($registrations as $r) {

        $pdf->Cell(90,10,$r['fullname'],1);

        $pdf->Cell(90,10,$r['email'],1);

        $pdf->Ln();
    }

    /*
    PAGE 3
    */

    $pdf->AddPage();

    $pdf->Cell(0,10,'Statistiques',0,1);

    // graphique simple

    $x = 20;
    $y = 100;

    for($i=0;$i<5;$i++) {

        $height = rand(20,80);

        $pdf->Rect(
            $x,
            $y - $height,
            20,
            $height,
            'DF'
        );

        $x += 30;
    }

    $path = '../pdf/samples/report_'.$eventId.'.pdf';

    $pdf->Output($path,'F');

    return $path;
}