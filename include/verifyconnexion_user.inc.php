<?php

global $cnx;
session_start();
if (!isset($_SESSION['sessionid'])) {
header('location: login.php');
}

$NumSiren = $_SESSION['NumSiren'];
$password = $_SESSION['password'];

$req = $cnx->prepare("SELECT id_util FROM utilisateur WHERE identifiant=:NumSiren AND mdp=:password"); // Verifier si c'est un utilisateur
$req->bindParam(':NumSiren', $NumSiren);
$req->bindParam(':password', $password);
$req->execute();
$ligne=$req->fetch(PDO::FETCH_OBJ);

if (!$ligne) {

    header('location: login.php?error=3'); // Si l'utilisateur n'existe pas

}