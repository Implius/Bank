<?php
global$cnx;
include("../include/connexion.inc.php");
?>
<?php
global$cnx;
include("../include/connexion.inc.php");
include('../include/verifyconnexion.inc.php');

if (isset($_POST["Raison"]) && isset($_POST["NumCompte"]) && isset($_POST["NumSiren"]) && isset($_POST["money"])) {

    $nextid = $cnx->query("SELECT max(id_demande) as nextid from creation;")->fetch(PDO::FETCH_OBJ)->nextid;
    $nextid = (int)$nextid + 2;
    $nextid = (string)$nextid;

    $req = $cnx->prepare("insert into creation (id_demande, devise, num_siren, raison, num_compte) values('".$nextid."', '".$_POST["money"]."', '".$_POST["NumSiren"]."', '".$_POST["Raison"]."', '".$_POST["NumCompte"]."');");

    $req->execute();

    unset($_POST["Raison"]);
    unset($_POST["NumCompte"]);
    unset($_POST["NumSiren"]);
    unset($_POST["money"]);

    header("PO_creation.php?inserted=1");
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="PO.css">
    <title>JeFinance</title>
    <script>
        function sortTable() {
            const select = document.getElementById('sort_by');
            const selectedValue = select.value;
            // Redirige vers la même page avec le paramètre de tri
            window.location.href = `?sort_by=${selectedValue}`;
        }
    </script>
</head>

<body>
<?php
$onit = "Creation";
include("../include/po_navbar.inc.php"); // Navbar
?>

<div class="Compte_tableau">
    <form action="PO_creation.php" method="post">
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
<?php
if (isset($_GET["inserted"])) {
    if ($_GET["inserted"] == 1) {
        echo "<p class='red'>La demande de création de compte a bien été prise en compte</p>";
    }
    else if ($_GET["inserted"] == 0) {
        echo "<p class='red'>Une erreur est survenue</p>";
    }
}
?>
</body>

</html>
