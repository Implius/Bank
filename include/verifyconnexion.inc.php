<?php

global $cnx;
session_start();
if (!isset($_SESSION['sessionid'])) {
header('location: po_login.php');
}

$NumSiren = $_SESSION['NumSiren'];
$password = $_SESSION['password'];

$req = $cnx->prepare("SELECT id_util FROM utilisateur WHERE identifiant=:NumSiren AND mdp=:password"); // Verifier si c'est un utilisateur
$req->bindParam(':NumSiren', $NumSiren);
$req->bindParam(':password', $password);
$req->execute();
$ligne=$req->fetch(PDO::FETCH_OBJ);

if ($ligne) {

$req_po = $cnx->query("SELECT COUNT(*) as res FROM po WHERE id_util = $ligne->id_util"); // Verifier si c'est un po
$res = $req_po->fetch(PDO::FETCH_OBJ);

if ($res->res != 1) {
header('location: po_login.php?error=3'); // Si l'utilisateur n'est pas un po
}
} else {
header('location: po_login.php?error=3'); // Si l'utilisateur n'existe pas
}