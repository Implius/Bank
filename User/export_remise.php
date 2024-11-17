<?php
$data = json_decode(file_get_contents('php://input'),true);
header('Content-Type: text/csv');
header("Content-Disposition: attachement; filename='export.csv'");

$ouput = fopen("php://output", "w");

fputcsv($ouput, array('Remise N°','Date','Objet','Raison Social','N° Siren','Montant'),";");

foreach ($data as $row) {
    fputcsv($ouput, $row, ";");
}

fclose($ouput);