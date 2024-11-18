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
                echo "<button name='supprimer' class='buttonsup' type='submit' onclick='showConfirmation()'>Supprimer</button>";
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
        <button class="confirm-button" onclick="confirmDelete()">Oui, supprimer</button>
        <button class="cancel-button" onclick="closeConfirmation()">Annuler</button>
        </form>
    </div>
</div>

</body>
<script>
    function showConfirmation() {
        document.getElementById("confirmation-popup").style.display = "flex";
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
