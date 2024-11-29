<?php
//recupere les donnees (au format json)
$data = json_decode(file_get_contents('php://input'),true);

//des options du fichier
header('Content-Type: text/csv');
header("Content-Disposition: attachement; filename='export.csv'");

//Ouvre le fichier en mode write
$ouput = fopen("php://output", "w");

//Entre la ligne de titres
fputcsv($ouput, array('Remise N°','Date','Objet','Raison Social','N° Siren','Montant'),";");

//Entre les donnees recuperees
foreach ($data as $row) {
    fputcsv($ouput, $row, ";");
}

//ferme le fichier
fclose($ouput);
