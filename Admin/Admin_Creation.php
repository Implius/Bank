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

if (isset($_POST['Raison']) && isset($_POST['NumCompte']) && isset($_POST['NumSiren']) && isset($_POST['money'])) {

    // Ajoute un nouvelle utilisateur dans Utilisateur

    $stmt = $cnx->query("SELECT max(id_util) as max FROM utilisateur");
    $max_id = $stmt->fetch(PDO::FETCH_OBJ)->max + 1;
    echo $max_id;

    $mdp = md5($_POST['NumSiren']);
    $sql = "INSERT INTO utilisateur (id_util, identifiant, mdp) VALUES (:id_util, :identifiant, :mdp)";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id_util', $max_id, PDO::PARAM_INT);
    $stmt->bindParam(':identifiant', $_POST['NumSiren'], PDO::PARAM_STR);
    $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $stmt->execute();

    // Ajoute un nouveau compte dans Compte

    $Raison = $_POST['Raison'];
    $NumCompte = $_POST['NumCompte'];
    $NumSiren = $_POST['NumSiren'];
    $money = $_POST['money'];

    $tresorerie = 0;

    $sql = "INSERT INTO compte (num_siren, num_compte, tresorerie, devise, raison_social, id_util) VALUES (:num_siren, :num_compte, :tresorerie, :devise, :raison_social, :id_util)";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':num_siren', $NumSiren, PDO::PARAM_STR);
    $stmt->bindParam(':num_compte', $NumCompte, PDO::PARAM_STR);
    $stmt->bindParam(':tresorerie', $tresorerie, PDO::PARAM_INT);
    $stmt->bindParam(':devise', $money, PDO::PARAM_STR);
    $stmt->bindParam(':raison_social', $Raison, PDO::PARAM_STR);
    $stmt->bindParam(':id_util', $max_id, PDO::PARAM_INT);
    $stmt->execute();

    // Supprime la requête dans la table Creation

    $sql = "DELETE FROM creation WHERE num_siren = :num_siren";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':num_siren', $NumSiren, PDO::PARAM_STR);
    $stmt->execute();

    echo "<script>alert('Compte créé avec succès !');</script>";
}

$onit = "Creation";
include("../include/Admin_navbar.inc.php"); // Navbar
?>

<div class="scrollable-section-container">
    <h1>Demande de création</h1>

    <?php
    $stmt = $cnx->query("SELECT * FROM creation");
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        echo "<div class='list-item' onclick='showDetails(this,\"$row->raison\", \"$row->num_siren\", \"$row->num_compte\", \"$row->devise\")'>$row->raison</div>";
    }
    ?>
</div>
    <!--Popup permettant de voir les informations d'un compte pour sa création-->
<div class="details-panel" id="detailsPanel">
    <div class="back-arrow" onclick="closeDetails()"><img alt="fleche" class="arrow" src="../images/backArrow.svg"/></div>
    <h3 id="accountName">Nom du compte : </h3>
    <p id="sirenNumber">Num SIREN : </p>
    <p id="accountNumber">Numéro du compte : </p>
    <p id="currency">Devise : </p>
</div>
    <!--Formularie de création-->
<div class="form-container">
    <form action="Admin_Creation.php" method="post">
        <div class="formcrea">
            <input class="forminput" type="text" id="Raison" name="Raison" placeholder="Raison social" required maxlength="50"><br>
        </div>
        <div class="formcrea">
            <input class="forminput" type="text" id="NumCompte" name="NumCompte" placeholder="Numéro du compte"
                   required maxlength="11"><br>
        </div>
        <div class="formcrea">
            <input class="forminput" type="text" id="NumSiren" name="NumSiren" placeholder="Numéro de SIREN" required maxlength="9"><br>
        </div>
        <div class="devise">
            <select name="money" id="money" required>
                <option value="" disabled selected>Devise</option>
                <option value="EUR">€</option>
                <option value="USD">$</option>
                <option value="GBP">£</option>
            </select>
        </div>
        <button class="buttoncreate" type="submit">Créer le compte</button>
    </form>
</div>
<script>
    // Fonction pour afficher le panneau de détails et mettre à jour son contenu
    function showDetails(element,name, siren, account, currency) {

        const items = document.querySelectorAll(".list-item");
        items.forEach(item => item.classList.remove("selected"));
        element.classList.add("selected");
        document.getElementById("accountName").textContent = "Nom du compte : " + name;
        document.getElementById("sirenNumber").textContent = "Num SIREN : " + siren;
        document.getElementById("accountNumber").textContent = "Numéro du compte : " + account;
        document.getElementById("currency").textContent = "Devise : " + currency;

        // Affiche le panneau de détails
        const detailsPanel = document.getElementById("detailsPanel");
        detailsPanel.style.display = "block";


    }

    // Fonction pour fermer le panneau de détails
    function closeDetails() {
        document.getElementById("detailsPanel").style.display = "none";
        const items = document.querySelectorAll(".list-item");
        items.forEach(item => item.classList.remove("selected"));
    }
</script>

</body>

</html>

