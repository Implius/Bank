<?php
//recupere les donnees tansmis au fichier (en format json)
$data = json_decode(file_get_contents('php://input'),true);

//Des options du ficher
header('Content-Type: text/csv');
header("Content-Disposition: attachement; filename='export.csv'");

//Ouvre le fichier en mode write
$ouput = fopen("php://output", "w");

//entre les donnees qui servent de ligne de titre
fputcsv($ouput, array('Impayé N°','Date','Autre parti','N° Siren','Raison social','Objet','Motif','Montant'),";");

//Pour toute les donnees recuperees on les ajoutent au fichier
foreach ($data as $row) {
    fputcsv($ouput, $row, ";");
}

//On ferme la connexion au fichier
fclose($ouput);
