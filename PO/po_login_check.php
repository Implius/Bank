<?php
global $cnx;
if (!(isset($_POST['NumSiren']) && isset($_POST['password']))) { /* Vérification que l'on passe bien par la page de formulaire */
    header('location: po_login.php');
}

include('../include/connexion.inc.php');

if (isset($_POST['NumSiren']) && isset($_POST['password'])) {

    $NumSiren = $_POST['NumSiren'];
    $password = md5($_POST['password']);

    $req = $cnx->prepare("SELECT id_util FROM utilisateur WHERE identifiant=:NumSiren AND mdp=:password"); // Verifier si c'est un utilisateur
    $req->bindParam(':NumSiren', $NumSiren);
    $req->bindParam(':password', $password);
    $req->execute();
    $ligne=$req->fetch(PDO::FETCH_OBJ);

    if ($ligne) {

        $req_po = $cnx->query("SELECT COUNT(*) as res FROM po WHERE id_util = $ligne->id_util"); // Verifier si c'est un po
        $res=$req_po->fetch(PDO::FETCH_OBJ);

        if ($res->res == 1) {

            setcookie('identifiant', $NumSiren, time() + 60*60*24*31);
            setcookie('mdp', $password, time() + 60*60*24*31);
            header('location: po_compte.php');

        } else {
            header('location: po_login.php?error=2'); // Si l'utilisateur n'est pas un po
        }

    } else {
        header('location: po_login.php?error=1'); // Si l'utilisateur n'existe pas
    }
}
