<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Admin.css">
    <title>JeFinance</title>
</head>
<body>
<?php
global $cnx;
include("../include/connexion.inc.php");
include("../include/verifyconnexion_admin.inc.php");

$onit = "Supression";
include("../include/Admin_navbar.inc.php"); // Navbar
if (isset($_POST["Delete"])){
    print_r($_POST["Delete"]);
    $raison = $_POST["Delete"];

    $stmt = $cnx->prepare("SELECT id_util,num_siren FROM compte WHERE raison_social = :raison");
    $stmt->bindParam(":raison", $raison, PDO::PARAM_STR);
    $stmt->execute();
    $req = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $req->id_util;
    $num_siren = $req->num_siren;

    $req2 = $cnx->prepare("Select id_remise FROM remise WHERE num_siren = :num_siren");
    $req2->bindParam(":num_siren", $num_siren, PDO::PARAM_STR);
    $req2->execute();

    //Delete de tout
    while ($row1 = $req2->fetch(PDO::FETCH_OBJ)) {
        $transa = $cnx->prepare("Select id_trans FROM transaction WHERE id_remise = :id_remise");
        $transa->bindParam(":id_remise", $row1->id_remise, PDO::PARAM_INT);
        $transa->execute();
        while ($row2 = $transa->fetch(PDO::FETCH_OBJ)) {
            $detail = $cnx->prepare("DELETE FROM detail WHERE id_trans = :id_trans");
            $detail->bindParam(":id_trans", $row2->id_trans, PDO::PARAM_INT);
            $detail->execute();
        }
        $delete_trans = $cnx->prepare("DELETE FROM transaction WHERE id_remise = :id_remise");
        $delete_trans->bindParam(":id_remise", $row1->id_remise, PDO::PARAM_INT);
        $delete_trans->execute();
    }
    $req3 = $cnx->prepare("DELETE FROM remise WHERE num_siren = :num_siren");
    $req3->bindParam(":num_siren", $num_siren, PDO::PARAM_STR);
    $req3->execute();

    $req4 = $cnx->prepare("Delete FROM impaye WHERE num_siren = :num_siren");
    $req4->bindParam(":num_siren", $num_siren, PDO::PARAM_STR);
    $req4->execute();

    $req5 = $cnx->prepare("Delete FROM suppression WHERE  num_siren = :num_siren");
    $req5->bindParam(":num_siren", $num_siren, PDO::PARAM_STR);
    $req5->execute();

    $req6 = $cnx->prepare("Delete FROM compte WHERE id_util = :id");
    $req6->bindParam(":id", $id, PDO::PARAM_INT);
    $req6->execute();

    $req7 = $cnx->prepare("Delete FROM utilisateur WHERE id_util = :id");
    $req7->bindParam(":id", $id, PDO::PARAM_INT);
    $req7->execute();

    //$cnx->query("DELETE FROM Supression WHERE idSupression=".$_POST["Delete"]);
}
?>
<div class="scrollable-section-container">
    <h1>Demande de suppression</h1>
    <div class="list-group">
            <?php
            $stmt = $cnx->query("SELECT * FROM suppression");
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $raison_social = $cnx->query("SELECT raison_social FROM compte WHERE num_siren = '$row->num_siren'")->fetch(PDO::FETCH_OBJ)->raison_social;
                echo "<div class=\"list-item-sup\">";
                echo "<div class='item-text'>" . $raison_social . "</div>";
                echo "<button class='buttonsup' onclick=\"showConfirmation('$raison_social')\">Supprimer</button>";
                echo "</div>";
            }
            ?>
        <!-- Vous pouvez ajouter d'autres éléments de la liste ici de la même manière -->
    </div>
</div>
<div id="confirmation-popup" class="confirmation-popup">
    <div class="popup-content">
        <p>Êtes-vous sûr de vouloir supprimer cet élément ?</p>
        <form action="Admin_Supression.php" method="post">
            <!-- Champ caché pour transmettre la valeur -->
            <input type="hidden" name="Delete" id="hiddenInput">
            <button class="confirm-button" type="submit">Oui, supprimer</button>
            <button class="cancel-button" type="button" onclick="closeConfirmation()">Annuler</button>
        </form>
    </div>
</div>

</body>
<script>
    function showConfirmation(raisonSocial) {

        document.getElementById("confirmation-popup").style.display = "flex";
        document.getElementById("hiddenInput").value = raisonSocial;
    }

    // Fonction pour fermer le pop-up (annuler)
    function closeConfirmation() {
        document.getElementById("confirmation-popup").style.display = "none";
    }

    // Fonction pour confirmer la suppression
    function confirmDelete() {
        // Effectuer la suppression, par exemple en soumettant un formulaire ou en envoyant une requête
        alert("Élément supprimé !");
        closeConfirmation(); // Ferme le pop-up après la confirmation
    }
</script>
</html>
