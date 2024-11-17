<?php
$data = json_decode(file_get_contents('php://input'),true);
header('Content-Type: text/csv');
header("Content-Disposition: attachement; filename='export.csv'");

$ouput = fopen("php://output", "w");

fputcsv($ouput, array('Impayé N°','Date','Autre parti','N° Siren','Raison social','Objet','Motif','Montant'),";");

foreach ($data as $row) {
    fputcsv($ouput, $row, ";");
}

fclose($ouput);