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
$onit = "Creation";
include("../include/Admin_navbar.inc.php"); // Navbar
?>

<div class="scrollable-section-container">
    <h1>Demande de création</h1>
    <div class="list-item" onclick="showDetails(this,'Leroy Merlin', '123 456 789', '01234567891', '€')">Leroy Merlin</div>
    <div class="list-item"  onclick="showDetails(this,'Leroy Merlin', '123 456 789', '01234567891', '€')">Item 2</div>
    <div class="list-item"  onclick="showDetails(this,'Leroy Merlin', '123 456 789', '01234567891', '€')">Item 3</div>
    <div class="list-item">Item 4</div>
    <div class="list-item">Item 5</div>
    <div class="list-item">Item 6</div>
    <div class="list-item">Item 7</div>
    <div class="list-item">Item 8</div>
    <div class="list-item">Item 9</div>
    <div class="list-item"onclick="showDetails(this,'Leroy Merlin', '123 456 789', '01234567891', '€')">Item 10</div>
    <div class="list-item" onclick="showDetails(this,'Leroy Merlin', '123 456 789', '01234567891', '€')">Leroy Merlin</div>
    <div class="list-item"  onclick="showDetails(this,'Leroy Merlin', '123 456 789', '01234567891', '€')">Item 2</div>
    <div class="list-item"  onclick="showDetails(this,'Leroy Merlin', '123 456 789', '01234567891', '€')">Item 3</div>
    <div class="list-item">Item 4</div>
    <div class="list-item">Item 5</div>
    <div class="list-item">Item 6</div>
    <div class="list-item">Item 7</div>
    <div class="list-item">Item 8</div>
    <div class="list-item">Item 9</div>
    <div class="list-item"onclick="showDetails(this,'Leroy Merlin', '123 456 789', '01234567891', '€')">Item 10</div>
</div>
<div class="details-panel" id="detailsPanel">
    <div class="back-arrow" onclick="closeDetails()"><img alt="fleche" class="arrow" src="../images/backArrow.svg"/></div>
    <h3 id="accountName">Nom du compte : </h3>
    <p id="sirenNumber">Num SIREN : </p>
    <p id="accountNumber">Numéro du compte : </p>
    <p id="currency">Devise : </p>
</div>
<div class="form-container">
    <form action="Admin_Creation.php" method="post">
        <div class="formcrea">
            <input class="forminput" type="text" id="Raison" name="Raison" placeholder="Raison social" required><br>
        </div>
        <div class="formcrea">
            <input class="forminput" type="text" id="NumCompte" name="NumCompte" placeholder="Numéro du compte"
                   required><br>
        </div>
        <div class="formcrea">
            <input class="forminput" type="text" id="NumSiren" name="NumSiren" placeholder="Numéro de SIREN" required><br>
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

