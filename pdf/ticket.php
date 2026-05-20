<?php

/*
Choix TCPDF :
meilleur support natif des QR Codes
et primitives graphiques.
*/

require_once '../vendor/autoload.php';

use TCPDF;

function generateTicket($event,$user,$token) {

    $pdf = new TCPDF();

    $pdf->AddPage();

    $pdf->Image('../assets/img/logo.png',10,10,30);

    $pdf->SetFont('helvetica','B',20);

    $pdf->Cell(0,20,'Event Ticket',0,1);

    $pdf->SetFont('helvetica','',12);

    $pdf->Cell(0,10,'Participant : '.$user['fullname'],0,1);

    $pdf->Cell(0,10,'Event : '.$event['title'],0,1);

    $style = [
        'border' => 2,
        'padding' => 4
    ];

    $qr = $event['id'].'|'.$user['id'].'|'.$token;

    $pdf->write2DBarcode(
        $qr,
        'QRCODE,H',
        130,
        50,
        50,
        50,
        $style
    );

    $path = '../pdf/samples/ticket_'.$user['id'].'.pdf';

    $pdf->Output($path,'F');

    return $path;
}