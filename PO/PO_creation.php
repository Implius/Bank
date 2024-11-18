<?php
global$cnx;
include("../include/connexion.inc.php");
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
    <?php
    global$cnx;
    include("../include/connexion.inc.php");
    include('../include/verifyconnexion.inc.php');

    if (isset($_POST["Raison"]) && isset($_POST["NumCompte"]) && isset($_POST["NumSiren"]) && isset($_POST["money"])) {

        $nextid = $cnx->query("SELECT max(cast(id_demande as int)) as nextid from demande_compte;")->fetch(PDO::FETCH_OBJ)->nextid;
        $nextid = $nextid + 1;
        $num_siren=$_POST["NumSiren"];
        $check = $cnx->prepare("SELECT COUNT(*) as count FROM creation WHERE num_siren = :num_siren;");
        $check->bindParam(':num_siren', $num_siren);
        $check->execute();
        $count = $check->fetch(PDO::FETCH_OBJ)->count;

        // Si la requête existe déjà
        if ($count > 0) {
            echo "Cette requête a déjà été faite.";
        } else {
            $req = $cnx->prepare("INSERT INTO demande_compte (id_demande, date_demande, libelle_demande) VALUES (:nextid, :date_demande, :name)");
            $req->bindParam(':nextid', $nextid, PDO::PARAM_STR);
            $req->bindParam(':date_demande', $date_demande, PDO::PARAM_STR);
            $req->bindParam(':name', $name, PDO::PARAM_STR);
            $req->execute();

            $req = $cnx->prepare("insert into creation (id_demande, devise, num_siren, raison, num_compte) values('" . $nextid . "', '" . $_POST["money"] . "', '" . $_POST["NumSiren"] . "', '" . $_POST["Raison"] . "', '" . $_POST["NumCompte"] . "');");

            $req->execute();

            unset($_POST["Raison"]);
            unset($_POST["NumCompte"]);
            unset($_POST["NumSiren"]);
            unset($_POST["money"]);

            header("PO_creation.php?inserted=1");
        }
    }
    ?>
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
