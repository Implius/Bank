<?php

global $cnx;
session_start();
if (!isset($_SESSION['sessionid'])) {
header('location: ../po_login.php');
}

$NumSiren = $_SESSION['NumSiren'];
$password = $_SESSION['password'];

$req = $cnx->prepare("SELECT id_util FROM utilisateur WHERE identifiant=:NumSiren AND mdp=:password"); // Verifier si c'est un utilisateur
$req->bindParam(':NumSiren', $NumSiren);
$req->bindParam(':password', $password);
$req->execute();
$ligne=$req->fetch(PDO::FETCH_OBJ);

if (!$ligne) {

    header('location: ../po_login.php?error=3'); // Si l'utilisateur n'existe pas

}

$req_po = $cnx->prepare("SELECT * FROM po WHERE id_util=:id_util");
$req_po->bindParam(':id_util', $ligne->id_util);
$req_po->execute();
$ligne_po=$req_po->fetch(PDO::FETCH_OBJ);

$req_admin = $cnx->prepare("SELECT * FROM admin WHERE id_util=:id_util");
$req_admin->bindParam(':id_util', $ligne->id_util);
$req_admin->execute();
$ligne_admin=$req_admin->fetch(PDO::FETCH_OBJ);

if ($ligne_admin || $ligne_po) {
    header('location: ../po_login.php?error=3'); // Si l'utilisateur est un admin ou un po
}