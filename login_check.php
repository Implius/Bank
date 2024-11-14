<?php
session_start();
global $cnx;
if (!(isset($_POST['NumSiren']) && isset($_POST['password']))) { /* Vérification que l'on passe bien par la page de formulaire */
    header('location: po_login.php');
}

include('include/connexion.inc.php');

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

        $admin_po = $cnx->query("SELECT COUNT(*) as res FROM admin WHERE id_util = $ligne->id_util"); // Verifier si c'est un admin
        $res_admin=$admin_po->fetch(PDO::FETCH_OBJ);

        if ($res->res == 1) {
            $_SESSION['sessionid'] = session_id();
            $_SESSION['NumSiren'] = $NumSiren;
            $_SESSION['password'] = $password;
            header('location: PO/po_compte.php');
        }
        elseif ($res_admin->res == 1) {
            $_SESSION['sessionid'] = session_id();
            $_SESSION['NumSiren'] = $NumSiren;
            $_SESSION['password'] = $password;
            header('location: Admin/Admin_Creation.php');
        }
        else {
            // C'est un utilisateur
            $_SESSION['sessionid'] = session_id();
            $_SESSION['NumSiren'] = $NumSiren;
            $_SESSION['password'] = $password;
            header('location: User/User_tresorerie.php');
        }

    } else {
        header('location: po_login.php?error=1'); // Si l'utilisateur n'existe pas
    }
}
